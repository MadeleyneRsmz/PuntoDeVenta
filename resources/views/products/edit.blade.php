@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
    <div class="page-head">
        <div>
            <h1>Editar producto</h1>
            <div class="sub">{{ $product->name }}</div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-ghost"><x-icon n="back" /> Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('products._form')
                <button type="submit" class="btn btn-pink btn-lg mt"><x-icon n="check" /> Guardar cambios</button>
            </form>
        </div>
    </div>
@endsection
