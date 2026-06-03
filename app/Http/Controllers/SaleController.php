<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SaleController extends Controller
{
    private const TAX_RATE = 0.16;

    /** Pantalla del punto de venta. */
    public function create(Request $request)
    {
        $search = trim((string) $request->get('q'));

        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products', 'search'));
    }

    /** Guarda la venta confirmada desde la ventana modal. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'        => ['nullable', 'string', 'max:150'],
            'customer_email'       => ['nullable', 'email', 'max:150'],
            'payment_method'       => ['required', 'string', 'in:Efectivo,Tarjeta,Transferencia'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.id'           => ['required', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ], [
            'items.required'       => 'Agrega al menos un producto a la venta.',
            'customer_email.email' => 'El correo del cliente no es válido.',
        ]);

        try {
            $sale = DB::transaction(function () use ($data) {
                $subtotal = 0;
                $lines    = [];

                foreach ($data['items'] as $item) {
                    /** @var Product $product */
                    $product = Product::lockForUpdate()->findOrFail($item['id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \RuntimeException("No hay suficiente existencia de «{$product->name}». Disponible: {$product->stock}.");
                    }

                    $lineSubtotal = $product->price * $item['quantity'];
                    $subtotal += $lineSubtotal;

                    $lines[] = [
                        'product'  => $product,
                        'quantity' => $item['quantity'],
                        'price'    => $product->price,
                        'subtotal' => $lineSubtotal,
                    ];
                }

                $tax   = round($subtotal * self::TAX_RATE, 2);
                $total = $subtotal + $tax;

                // El folio sale del id real de la venta: dos ventas simultáneas nunca chocan.
                $sale = Sale::create([
                    'receipt_number' => 'TMP-' . bin2hex(random_bytes(6)),
                    'user_id'        => Auth::id(),
                    'customer_name'  => $data['customer_name'] ?? null,
                    'customer_email' => $data['customer_email'] ?? null,
                    'subtotal'       => $subtotal,
                    'tax'            => $tax,
                    'total'          => $total,
                    'payment_method' => $data['payment_method'],
                ]);
                $sale->update(['receipt_number' => 'MD-' . str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT)]);

                foreach ($lines as $line) {
                    SaleItem::create([
                        'sale_id'      => $sale->id,
                        'product_id'   => $line['product']->id,
                        'product_name' => $line['product']->name,
                        'quantity'     => $line['quantity'],
                        'unit_price'   => $line['price'],
                        'subtotal'     => $line['subtotal'],
                    ]);

                    // Descuenta la existencia automáticamente al confirmar la venta.
                    $line['product']->decrement('stock', $line['quantity']);
                }

                return $sale;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'No se pudo registrar la venta. Intenta de nuevo.')->withInput();
        }

        // Cada venta manda su ticket por Gmail: siempre al correo de la tienda
        // y, si el cliente dejó su correo, también a él.
        $this->sendTicket($sale);

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', "Venta {$sale->receipt_number} registrada correctamente. El ticket se envió por correo.");
    }

    /** Historial de ventas con filtro por fecha. */
    public function index(Request $request)
    {
        $range = $request->get('range', 'all');

        $query = Sale::with('cashier')->when($range !== 'all', fn ($q) => $this->applyRange($q, $range));

        $totals = (clone $query)->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as income')->first();
        $summary = [
            'count'  => (int) $totals->count,
            'income' => (float) $totals->income,
        ];

        $sales = $query->latest()->paginate(10)->withQueryString();

        return view('sales.index', compact('sales', 'range', 'summary'));
    }

    /** Ticket / detalle de una venta. */
    public function show(Sale $sale)
    {
        $sale->load('items', 'cashier');

        return view('sales.show', compact('sale'));
    }

    /** Envía el ticket por correo sin tumbar la venta si Gmail falla. */
    private function sendTicket(Sale $sale): void
    {
        $recipients = array_filter(array_unique([
            env('TICKET_EMAIL'),
            $sale->customer_email,
        ]));

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new TicketMail($sale));
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    private function applyRange($query, string $range)
    {
        return match ($range) {
            'today' => $query->whereDate('created_at', now()->toDateString()),
            'week'  => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month),
            default => $query,
        };
    }
}
