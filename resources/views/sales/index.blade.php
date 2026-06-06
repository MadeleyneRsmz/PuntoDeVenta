@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
    <div class="page-head">
        <div>
            <h1>Historial de ventas</h1>
            <div class="sub">{{ $summary['count'] }} ventas · ${{ number_format($summary['income'], 2) }} en ingresos</div>
        </div>
        <div class="flex gap">
            <a href="{{ route('sales.index', ['range' => 'today']) }}" class="btn {{ $range === 'today' ? 'btn-pink' : 'btn-outline' }}">Hoy</a>
            <a href="{{ route('sales.index', ['range' => 'week']) }}" class="btn {{ $range === 'week' ? 'btn-pink' : 'btn-outline' }}">Semana</a>
            <a href="{{ route('sales.index', ['range' => 'month']) }}" class="btn {{ $range === 'month' ? 'btn-pink' : 'btn-outline' }}">Mes</a>
            <a href="{{ route('sales.index', ['range' => 'all']) }}" class="btn {{ $range === 'all' ? 'btn-pink' : 'btn-outline' }}">Todo</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($sales->isEmpty())
                <div class="empty-state"><x-icon n="receipt" />No hay ventas en este rango.</div>
            @else
                <div class="table-wrap">
                    <table class="grid-table">
                        <thead><tr><th>Folio</th><th>Fecha</th><th>Cliente</th><th>Vendió</th><th>Pago</th><th class="right">Total</th></tr></thead>
                        <tbody>
                        @foreach ($sales as $sale)
                            <tr onclick="location='{{ route('sales.show', $sale) }}'" style="cursor:pointer;">
                                <td><b>{{ $sale->receipt_number }}</b></td>
                                <td class="muted">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $sale->customer_name ?: 'Público general' }}</td>
                                <td class="muted">{{ $sale->cashier->name ?? '—' }}</td>
                                <td><span class="tag tag-pink">{{ $sale->payment_method }}</span></td>
                                <td class="right"><b>${{ number_format($sale->total, 2) }}</b></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt">{{ $sales->links() }}</div>
            @endif
        </div>
    </div>
@endsection
