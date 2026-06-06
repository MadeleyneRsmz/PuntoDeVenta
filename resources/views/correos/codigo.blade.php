<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Código de verificación</title>
</head>
<body style="margin:0;background:#fff6f9;font-family:Segoe UI, Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 16px;">
                <table width="440" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #ffd9e8;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#ff5c93;padding:22px 28px;">
                            <span style="color:#fff;font-weight:700;letter-spacing:.05em;text-transform:uppercase;font-size:15px;">{{ config('app.name') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <p style="margin:0 0 14px;font-size:15px;color:#3a2233;">Hola <b>{{ $user->name }}</b>,</p>
                            @if ($motivo === 'recuperacion')
                                <p style="margin:0 0 22px;font-size:14px;color:#8a7480;">
                                    Usa este código para restablecer tu contraseña. Es válido por 15 minutos.
                                </p>
                            @else
                                <p style="margin:0 0 22px;font-size:14px;color:#8a7480;">
                                    Usa este código para verificar tu correo y activar tu cuenta. Es válido por 15 minutos.
                                </p>
                            @endif
                            <div style="text-align:center;margin:0 0 24px;">
                                <span style="display:inline-block;background:#fff0f5;border:1px solid #ffd9e8;border-radius:10px;padding:16px 30px;font-size:32px;font-weight:800;letter-spacing:.18em;font-family:Consolas,monospace;color:#ff5c93;">
                                    {{ $codigo }}
                                </span>
                            </div>
                            <p style="margin:0;font-size:12.5px;color:#c3a9b5;">
                                Si no solicitaste este código, puedes ignorar este correo.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
