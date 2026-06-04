<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-badge"><x-icon n="user" style="width:26px;height:26px;" /></div>
        <h1>Crear cuenta</h1>
        <p class="lead">Llena tus datos para registrarte</p>

        @if ($errors->any())
            <div class="alert alert-bad"><x-icon n="alert" />{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf
            <div class="field">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Tu nombre" autofocus required>
            </div>
            <div class="field">
                <label for="email">Correo</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" required>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="field" style="margin-bottom:20px;">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-pink btn-block btn-lg">Registrarme</button>
        </form>

        <p class="auth-alt">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
</div>
</body>
</html>
