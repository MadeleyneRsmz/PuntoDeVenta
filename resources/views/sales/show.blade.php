@extends('layouts.app')

@section('title', 'Ticket ' . $sale->receipt_number)

@section('content')
    <div class="page-head">
        <div>
            <h1>Ticket de venta</h1>
            <div class="sub">Folio {{ $sale->receipt_number }}</div>
        </div>
        <a href="{{ route('sales.index') }}" class="btn btn-ghost"><x-icon n="back" /> Volver al historial</a>
    </div>

    <div class="card receipt">
        <div class="head">
            <div style="font-weight:800;font-size:18px;">Tienda Made</div>
            <div class="muted" style="font-size:12px;margin-top:4px;">{{ $sale->created_at->format('d/m/Y H:i') }}</div>
            <div style="margin-top:10px;font-size:22px;font-weight:800;">{{ $sale->receipt_number }}</div>
        </div>
        <div class="rows">
            <div class="r"><span class="muted">Cliente</span><span>{{ $sale->customer_name ?: 'Público general' }}</span></div>
            <div class="r"><span class="muted">Atendió</span><span>{{ $sale->cashier->name ?? '—' }}</span></div>
            <div class="r"><span class="muted">Forma de pago</span><span>{{ $sale->payment_method }}</span></div>
            <hr style="border:none;border-top:1px dashed var(--border);margin:14px 0;">
            @foreach ($sale->items as $item)
                <div class="r">
                    <span>{{ $item->quantity }} × {{ $item->product_name }}</span>
                    <span>${{ number_format($item->subtotal, 2) }}</span>
                </div>
            @endforeach
        </div>
        <div class="foot">
            <div class="r"><span class="muted">Subtotal</span><span>${{ number_format($sale->subtotal, 2) }}</span></div>
            <div class="r"><span class="muted">IVA (16%)</span><span>${{ number_format($sale->tax, 2) }}</span></div>
            <div class="r" style="font-size:19px;font-weight:800;color:var(--pink-deep);"><span>Total</span><span>${{ number_format($sale->total, 2) }}</span></div>
        </div>
    </div>
@endsection
