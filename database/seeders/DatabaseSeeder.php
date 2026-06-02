<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Las tres cuentas del proyecto. Contraseña de todas: 1234.
        // Sus códigos de verificación llegan al Gmail configurado en el .env.
        $gmail = env('DEMO_NOTIFY_EMAIL', 'duodedos52@gmail.com');

        // Ya vienen verificadas: entran directo sin pedir código.
        User::updateOrCreate(
            ['email' => 'admin@made.com'],
            ['name' => 'Administrador', 'role' => 'admin', 'notify_email' => $gmail, 'password' => Hash::make('1234'), 'verified_at' => now()]
        );
        User::updateOrCreate(
            ['email' => 'vendedor@made.com'],
            ['name' => 'Vendedor', 'role' => 'employee', 'notify_email' => $gmail, 'password' => Hash::make('1234'), 'verified_at' => now()]
        );
        User::updateOrCreate(
            ['email' => 'made@1'],
            ['name' => 'Made', 'role' => 'admin', 'notify_email' => $gmail, 'password' => Hash::make('1234'), 'verified_at' => now()]
        );

        $bebidas   = Category::firstOrCreate(['name' => 'Bebidas']);
        $botanas   = Category::firstOrCreate(['name' => 'Botanas y dulces']);
        $abarrotes = Category::firstOrCreate(['name' => 'Abarrotes']);
        $limpieza  = Category::firstOrCreate(['name' => 'Limpieza y hogar']);
        $lacteos   = Category::firstOrCreate(['name' => 'Lácteos']);

        $products = [
            // Bebidas
            ['sku' => 'BEB-01', 'name' => 'Agua Ciel 1L',           'price' => 14.00, 'stock' => 48, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-02', 'name' => 'Coca-Cola 600ml',        'price' => 22.00, 'stock' => 60, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-03', 'name' => 'Sprite 600ml',           'price' => 20.00, 'stock' => 35, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-04', 'name' => 'Fanta Naranja 600ml',    'price' => 20.00, 'stock' => 32, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-05', 'name' => 'Boing Mango 500ml',      'price' => 17.00, 'stock' => 28, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-06', 'name' => 'Jugo Del Valle',         'price' => 18.00, 'stock' => 24, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-07', 'name' => 'Powerade Mora 500ml',    'price' => 25.00, 'stock' => 20, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-08', 'name' => 'Red Bull 250ml',         'price' => 42.00, 'stock' => 15, 'category_id' => $bebidas->id],
            ['sku' => 'BEB-09', 'name' => 'Topo Chico 355ml',       'price' => 19.00, 'stock' => 26, 'category_id' => $bebidas->id],
            // Botanas y dulces
            ['sku' => 'BOT-01', 'name' => 'Sabritas Original',      'price' => 17.00, 'stock' => 40, 'category_id' => $botanas->id],
            ['sku' => 'BOT-02', 'name' => 'Doritos Nacho',          'price' => 18.00, 'stock' => 38, 'category_id' => $botanas->id],
            ['sku' => 'BOT-03', 'name' => 'Cheetos Torciditos',     'price' => 17.00, 'stock' => 30, 'category_id' => $botanas->id],
            ['sku' => 'BOT-04', 'name' => 'Ruffles Queso',          'price' => 18.00, 'stock' => 25, 'category_id' => $botanas->id],
            ['sku' => 'BOT-05', 'name' => 'Cacahuates Japoneses',   'price' => 18.00, 'stock' => 22, 'category_id' => $botanas->id],
            ['sku' => 'BOT-06', 'name' => 'Galletas Emperador',     'price' => 16.00, 'stock' => 30, 'category_id' => $botanas->id],
            ['sku' => 'BOT-07', 'name' => 'Gansito Marinela',       'price' => 19.00, 'stock' => 28, 'category_id' => $botanas->id],
            ['sku' => 'BOT-08', 'name' => 'Chocolate Carlos V',     'price' => 15.00, 'stock' => 45, 'category_id' => $botanas->id],
            ['sku' => 'BOT-09', 'name' => 'Mazapán De la Rosa',     'price' => 8.00,  'stock' => 60, 'category_id' => $botanas->id],
            ['sku' => 'BOT-10', 'name' => 'Paleta Payaso',          'price' => 17.00, 'stock' => 3,  'category_id' => $botanas->id],
            // Abarrotes
            ['sku' => 'ABA-01', 'name' => 'Arroz 1kg',              'price' => 28.00, 'stock' => 25, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-02', 'name' => 'Frijol 1kg',             'price' => 32.00, 'stock' => 22, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-03', 'name' => 'Azúcar 1kg',             'price' => 30.00, 'stock' => 20, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-04', 'name' => 'Sal La Fina 1kg',        'price' => 14.00, 'stock' => 18, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-05', 'name' => 'Aceite 1L',              'price' => 42.00, 'stock' => 16, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-06', 'name' => 'Maseca 1kg',             'price' => 24.00, 'stock' => 20, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-07', 'name' => 'Atún Dolores lata',      'price' => 24.00, 'stock' => 30, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-08', 'name' => 'Frijoles La Costeña',    'price' => 22.00, 'stock' => 24, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-09', 'name' => 'Pasta Sopa La Moderna',  'price' => 10.00, 'stock' => 40, 'category_id' => $abarrotes->id],
            ['sku' => 'ABA-10', 'name' => 'Café La Costeña 200g',   'price' => 48.00, 'stock' => 12, 'category_id' => $abarrotes->id],
            // Limpieza y hogar
            ['sku' => 'LIM-01', 'name' => 'Cloro 1L',               'price' => 22.00, 'stock' => 18, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-02', 'name' => 'Pinol 1L',               'price' => 30.00, 'stock' => 15, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-03', 'name' => 'Fabuloso 1L',            'price' => 28.00, 'stock' => 17, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-04', 'name' => 'Detergente Ariel 1kg',   'price' => 45.00, 'stock' => 14, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-05', 'name' => 'Detergente Roma 1kg',    'price' => 35.00, 'stock' => 16, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-06', 'name' => 'Jabón Zote',             'price' => 18.00, 'stock' => 25, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-07', 'name' => 'Suavitel 1L',            'price' => 38.00, 'stock' => 12, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-08', 'name' => 'Escoba Perico',          'price' => 65.00, 'stock' => 8,  'category_id' => $limpieza->id],
            ['sku' => 'LIM-09', 'name' => 'Papel Higiénico 4p',     'price' => 35.00, 'stock' => 22, 'category_id' => $limpieza->id],
            ['sku' => 'LIM-10', 'name' => 'Servilletas Pétalo',     'price' => 20.00, 'stock' => 4,  'category_id' => $limpieza->id],
            // Lácteos
            ['sku' => 'LAC-01', 'name' => 'Leche Lala 1L',          'price' => 27.00, 'stock' => 30, 'category_id' => $lacteos->id],
        ];

        foreach ($products as &$product) {
            $product['image'] = $this->photoFor($product['sku']);
        }
        unset($product);

        foreach ($products as $product) {
            Product::updateOrCreate(['sku' => $product['sku']], $product);
        }

        $this->seedDemoSales();
    }

    /** Genera ~60 ventas de muestra repartidas en los últimos 60 días. */
    private function seedDemoSales(): void
    {
        if (Sale::count() > 0) {
            return;
        }

        $products = Product::all();
        $userIds  = User::pluck('id')->all();
        $methods  = ['Efectivo', 'Efectivo', 'Tarjeta', 'Tarjeta', 'Transferencia'];
        $names    = [null, null, 'Ana López', 'Renata Cruz', 'Sofía Méndez', null];

        for ($folio = 1; $folio <= 60; $folio++) {
            $date   = now()->subDays(random_int(0, 59))->setTime(random_int(10, 20), random_int(0, 59));
            $method = $methods[array_rand($methods)];
            $picked = $products->random(random_int(1, 3));

            $subtotal = 0;
            $lines    = [];
            foreach ($picked as $product) {
                $quantity = random_int(1, 3);
                $lineSubtotal = $product->price * $quantity;
                $subtotal += $lineSubtotal;
                $lines[] = [
                    'product'  => $product,
                    'quantity' => $quantity,
                    'price'    => $product->price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $tax   = round($subtotal * 0.16, 2);
            $total = $subtotal + $tax;

            $sale = new Sale([
                'receipt_number' => 'MD-' . str_pad((string) $folio, 6, '0', STR_PAD_LEFT),
                'user_id'        => $userIds[array_rand($userIds)],
                'customer_name'  => $names[array_rand($names)],
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'total'          => $total,
                'payment_method' => $method,
            ]);
            $sale->created_at = $date;
            $sale->updated_at = $date;
            $sale->save();

            foreach ($lines as $line) {
                $item = new SaleItem([
                    'sale_id'      => $sale->id,
                    'product_id'   => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'quantity'     => $line['quantity'],
                    'unit_price'   => $line['price'],
                    'subtotal'     => $line['subtotal'],
                ]);
                $item->created_at = $date;
                $item->updated_at = $date;
                $item->save();
            }
        }
    }

    /** Usa la foto real del producto si existe en public/img/products. */
    private function photoFor(string $sku): string
    {
        foreach (['webp', 'jpg', 'jpeg', 'png', 'avif'] as $ext) {
            if (file_exists(public_path("img/products/{$sku}.{$ext}"))) {
                return "img/products/{$sku}.{$ext}";
            }
        }

        return 'img/placeholder.svg';
    }
}
