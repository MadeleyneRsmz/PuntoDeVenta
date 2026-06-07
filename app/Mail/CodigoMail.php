<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Correo con un código de 6 dígitos. Se usa para dos cosas:
 *  - motivo 'login':       verificación de 2 pasos al iniciar sesión.
 *  - motivo 'recuperacion': restablecer la contraseña olvidada.
 */
class CodigoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $codigo, public string $motivo = 'login')
    {
    }

    public function build()
    {
        $asunto = $this->motivo === 'recuperacion'
            ? 'Código para recuperar tu contraseña · ' . config('app.name')
            : 'Tu código de verificación · ' . config('app.name');

        return $this->subject($asunto)->view('correos.codigo');
    }
}
