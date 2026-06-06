@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="page-head">
        <div>
            <h1>Hola, {{ auth()->user()->name }} 🌸</h1>
            <div class="sub">Esto es lo que pasa hoy en la tienda</div>
        </div>
        <a href="{{ route('sales.create') }}" class="btn btn-pink btn-lg"><x-icon n="cart" /> Nueva venta</a>
    </div>

    <div class="stat-cards">
        <div class="stat-card">
            <div class="label"><x-icon n="box" style="width:14px;height:14px;" />Productos</div>
            <div class="value">{{ $stats['products'] }}</div>
        </div>
        <div class="stat-card warn">
            <div class="label"><x-icon n="alert" style="width:14px;height:14px;" />Poca existencia</div>
            <div class="value">{{ $stats['lowStock'] }}</div>
        </div>
        <div class="stat-card">
            <div class="label"><x-icon n="cart" style="width:14px;height:14px;" />Ventas de hoy</div>
            <div class="value">{{ $stats['salesToday'] }}</div>
        </div>
        <div class="stat-card pink">
            <div class="label"><x-icon n="coin" style="width:14px;height:14px;" />Ingresos de hoy</div>
            <div class="value">${{ number_format($stats['incomeToday'], 2) }}</div>
        </div>
    </div>

    <div class="grid grid-2 mt">
        <div class="card">
            <div class="card-body">
                <h3 class="section-title"><x-icon n="receipt" />Ventas recientes</h3>
                @if ($recentSales->isEmpty())
                    <div class="empty-state"><x-icon n="receipt" />Todavía no hay ventas registradas.</div>
                @else
                    <div class="table-wrap">
                        <table class="grid-table">
                            <thead><tr><th>Folio</th><th>Vendió</th><th>Fecha</th><th class="right">Total</th></tr></thead>
                            <tbody>
                            @foreach ($recentSales as $sale)
                                <tr onclick="location='{{ route('sales.show', $sale) }}'" style="cursor:pointer;">
                                    <td><b>{{ $sale->receipt_number }}</b></td>
                                    <td class="muted">{{ $sale->cashier->name ?? '—' }}</td>
                                    <td class="muted">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="right"><b>${{ number_format($sale->total, 2) }}</b></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="section-title"><x-icon n="alert" />Poca existencia</h3>
                @if ($lowStockProducts->isEmpty())
                    <div class="empty-state"><x-icon n="check" />Todo el inventario está en buen nivel.</div>
                @else
                    @foreach ($lowStockProducts as $product)
                        <div class="flex between" style="padding:10px 0;border-bottom:1px solid var(--surface-2);">
                            <div>
                                <b style="font-size:13px;">{{ $product->name }}</b><br>
                                <span class="muted" style="font-size:11.5px;">{{ $product->sku }}</span>
                            </div>
                            <span class="tag tag-amber">{{ $product->stock }} pzas</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
