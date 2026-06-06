@extends('layouts.app')

@section('title', 'Agregar producto')

@section('content')
    <div class="page-head">
        <div>
            <h1>Agregar producto</h1>
            <div class="sub">Se agrega a la tabla en cuanto lo guardes</div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-ghost"><x-icon n="back" /> Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf
                @include('products._form')
                <button type="submit" class="btn btn-pink btn-lg mt"><x-icon n="check" /> Guardar producto</button>
            </form>
        </div>
    </div>
@endsection
