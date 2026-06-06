<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="topnav">
        <div class="brand">
            <span class="dot"><x-icon n="heart" style="width:16px;height:16px;" /></span>
            Tienda Made
        </div>
        <div class="links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <x-icon n="home" style="width:15px;height:15px;" /> Inicio
            </a>
            <a href="{{ route('sales.create') }}" class="{{ request()->routeIs('sales.create') ? 'active' : '' }}">
                <x-icon n="cart" style="width:15px;height:15px;" /> Vender
            </a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                <x-icon n="box" style="width:15px;height:15px;" /> Productos
            </a>
            <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'active' : '' }}">
                <x-icon n="receipt" style="width:15px;height:15px;" /> Ventas
            </a>
        </div>
        <div class="user">
            <span class="name">{{ auth()->user()->name ?? 'Invitada' }}</span>
            <span class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'B', 0, 1)) }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn" title="Cerrar sesión">
                    <x-icon n="logout" style="width:15px;height:15px;" />
                </button>
            </form>
        </div>
    </nav>

    <div class="page">
        @if (session('success'))
            <div class="alert alert-ok"><x-icon n="check" />{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-bad"><x-icon n="alert" />{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-bad"><x-icon n="alert" />{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
