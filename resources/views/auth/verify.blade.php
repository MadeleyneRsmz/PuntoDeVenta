<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifica tu correo · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="logo-badge"><x-icon n="shield" style="width:26px;height:26px;" /></div>
        <h1>Verifica tu correo</h1>
        <p class="lead">Solo esta vez: te enviamos un código de 6 dígitos a<br><b>{{ $maskedEmail }}</b></p>

        @if (session('success'))
            <div class="alert alert-ok"><x-icon n="check" />{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-bad"><x-icon n="alert" />{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('twofactor.verify') }}">
            @csrf
            <div class="field">
                <label for="code">Código de verificación</label>
                <input type="text" id="code" name="code" class="code-input" inputmode="numeric" pattern="[0-9]*"
                       maxlength="6" placeholder="000000" autocomplete="one-time-code" autofocus required>
            </div>
            <button type="submit" class="btn btn-pink btn-block btn-lg">Verificar y entrar</button>
        </form>

        <form method="POST" action="{{ route('twofactor.resend') }}" style="margin-top:14px;text-align:center;">
            @csrf
            <button type="submit" class="btn btn-ghost" style="font-size:12px;">Reenviar código</button>
        </form>

        <p class="auth-alt"><a href="{{ route('login') }}">Volver al inicio de sesión</a></p>
    </div>
</div>
</body>
</html>
