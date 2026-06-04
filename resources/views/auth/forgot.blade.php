<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar contraseña · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-badge"><x-icon n="key" style="width:26px;height:26px;" /></div>
        <h1>Recuperar contraseña</h1>
        <p class="lead">Escribe tu correo y te enviaremos un código para restablecerla</p>

        @if ($errors->any())
            <div class="alert alert-bad"><x-icon n="alert" />{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field" style="margin-bottom:20px;">
                <label for="email">Correo</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" autofocus required>
            </div>
            <button type="submit" class="btn btn-pink btn-block btn-lg">Enviar código</button>
        </form>

        <p class="auth-alt"><a href="{{ route('login') }}">Volver al inicio de sesión</a></p>
    </div>
</div>
</body>
</html>
