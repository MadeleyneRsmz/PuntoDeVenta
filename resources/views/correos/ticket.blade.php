<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket de compra</title>
</head>
<body style="margin:0;background:#fff6f9;font-family:Segoe UI, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table width="460" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #ffd9e8;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#ff5c93;padding:22px 28px;text-align:center;">
                            <span style="color:#fff;font-weight:700;letter-spacing:.05em;text-transform:uppercase;font-size:15px;">{{ config('app.name') }}</span>
                            <div style="color:#ffe3ee;font-size:12px;margin-top:4px;">Comprobante de venta {{ $sale->receipt_number }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 28px;">
                            <p style="margin:0 0 4px;font-size:13.5px;color:#8a7480;">Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                            <p style="margin:0 0 4px;font-size:13.5px;color:#8a7480;">Método de pago: {{ $sale->payment_method }}</p>
                            <p style="margin:0 0 4px;font-size:13.5px;color:#8a7480;">Atendió: {{ $sale->cashier->name ?? '—' }}</p>
                            @if ($sale->customer_name)
                                <p style="margin:0 0 4px;font-size:13.5px;color:#8a7480;">Cliente: {{ $sale->customer_name }}</p>
                            @endif
                            <div style="height:12px;"></div>

                            <table width="100%" cellpadding="6" cellspacing="0" style="border-top:1px dashed #ffd9e8;border-bottom:1px dashed #ffd9e8;margin-bottom:14px;">
                                @foreach ($sale->items as $item)
                                    <tr>
                                        <td style="font-size:13.5px;color:#3a2233;">{{ $item->quantity }} × {{ $item->product_name }}</td>
                                        <td align="right" style="font-size:13.5px;color:#3a2233;">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            <table width="100%" cellpadding="3" cellspacing="0">
                                <tr><td style="font-size:13px;color:#8a7480;">Subtotal</td><td align="right" style="font-size:13px;color:#8a7480;">${{ number_format($sale->subtotal, 2) }}</td></tr>
                                <tr><td style="font-size:13px;color:#8a7480;">IVA (16%)</td><td align="right" style="font-size:13px;color:#8a7480;">${{ number_format($sale->tax, 2) }}</td></tr>
                                <tr><td style="font-size:18px;font-weight:800;color:#3a2233;padding-top:6px;">Total</td><td align="right" style="font-size:18px;font-weight:800;color:#ff5c93;padding-top:6px;">${{ number_format($sale->total, 2) }}</td></tr>
                            </table>

                            <p style="margin:22px 0 0;font-size:12px;color:#c3a9b5;">Gracias por tu compra.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
