@extends('layouts.app')

@section('title', 'Productos')

@section('content')
    <div class="page-head">
        <div>
            <h1>Productos</h1>
            <div class="sub">Consulta, agrega y edita el catálogo</div>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-pink btn-lg"><x-icon n="plus" /> Agregar producto</a>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="flex gap" style="margin-bottom:18px;">
        <input type="text" name="q" value="{{ $search }}" placeholder="Buscar por nombre o clave…"
               style="flex:1;padding:11px 15px;border:2px solid var(--border);border-radius:var(--radius-pill);font-family:inherit;font-size:14px;">
        <button class="btn btn-outline" type="submit"><x-icon n="search" /> Buscar</button>
    </form>

    <div class="card">
        <div class="card-body">
            @if ($products->isEmpty())
                <div class="empty-state"><x-icon n="box" />No hay productos que coincidan.</div>
            @else
                <div class="table-wrap">
                    <table class="grid-table">
                        <thead>
                        <tr>
                            <th>Foto</th><th>Clave</th><th>Nombre</th><th>Categoría</th>
                            <th class="right">Precio</th><th class="right">Existencia</th><th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td><img class="thumb" src="{{ $product->photo_url }}" alt=""></td>
                                <td class="muted">{{ $product->sku }}</td>
                                <td><b>{{ $product->name }}</b></td>
                                <td class="muted">{{ $product->category->name ?? '—' }}</td>
                                <td class="right">${{ number_format($product->price, 2) }}</td>
                                <td class="right">
                                    @if ($product->stock <= 5)
                                        <span class="tag tag-amber">{{ $product->stock }}</span>
                                    @else
                                        <span class="tag tag-green">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="right">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-ghost" style="padding:7px 10px;"><x-icon n="edit" style="width:14px;height:14px;" /></a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="padding:7px 10px;color:var(--red);"><x-icon n="trash" style="width:14px;height:14px;" /></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
@endsection
