<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-badge"><x-icon n="heart" style="width:26px;height:26px;" /></div>
        <h1>{{ config('app.name') }}</h1>
        <p class="lead">Inicia sesión para continuar</p>

        @if (session('success'))
            <div class="alert alert-ok"><x-icon n="check" />{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-bad"><x-icon n="alert" />{{ $errors->first() }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-bad"><x-icon n="alert" />{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="field">
                <label for="email">Correo</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" autofocus required>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="flex between" style="margin-bottom:20px;">
                <label class="flex gap" style="font-size:12px;color:var(--ink-soft);gap:6px;">
                    <input type="checkbox" name="remember" style="width:auto;"> Recordarme
                </label>
                <a href="{{ route('password.forgot') }}" style="font-size:12px;font-weight:700;">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="btn btn-pink btn-block btn-lg">Entrar</button>
        </form>

        <p class="auth-alt">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
    </div>
</div>
</body>
</html>
