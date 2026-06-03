<?php

namespace App\Http\Controllers;

use App\Mail\CodigoMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /* =====================================================
       Paso 1 del login: correo y contraseña
       ===================================================== */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Escribe tu correo.',
            'password.required' => 'Escribe tu contraseña.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Ese correo y contraseña no coinciden.']);
        }

        // Si la cuenta todavía no verificó su correo (recién registrada),
        // se le pide el código una única vez antes de entrar.
        if (! $user->isVerified()) {
            $this->sendTwoFactorCode($user);

            $request->session()->put([
                'twofactor.user_id'  => $user->id,
                'twofactor.remember' => $request->boolean('remember'),
            ]);

            return redirect()->route('twofactor.show');
        }

        // Cuenta ya verificada: entra directo.
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /* =====================================================
       Paso 2 del login: código de verificación (Gmail)
       ===================================================== */

    public function showTwoFactor(Request $request)
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        return view('auth.verify', ['maskedEmail' => $this->maskEmail($user->inboxEmail())]);
    }

    public function verifyTwoFactor(Request $request)
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        $request->validate(
            ['code' => ['required', 'digits:6']],
            ['code.required' => 'Escribe el código.', 'code.digits' => 'El código es de 6 dígitos.']
        );

        if (
            ! $user->two_factor_code
            || ! hash_equals($user->two_factor_code, $request->input('code'))
            || $user->two_factor_expires_at->isPast()
        ) {
            return back()->withErrors(['code' => 'El código no es válido o ya expiró. Pide uno nuevo.']);
        }

        // Código correcto: la cuenta queda verificada para siempre y entra.
        $remember = (bool) $request->session()->pull('twofactor.remember', false);
        $request->session()->forget('twofactor.user_id');

        $user->forceFill([
            'verified_at'           => $user->verified_at ?? now(),
            'two_factor_code'       => null,
            'two_factor_expires_at' => null,
        ])->save();

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function resendTwoFactor(Request $request)
    {
        $user = $this->pendingUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        $this->sendTwoFactorCode($user);

        return back()->with('success', 'Te enviamos un código nuevo a tu correo.');
    }

    /* =====================================================
       Registro de cuenta nueva
       ===================================================== */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ], [
            'name.required'      => 'Escribe tu nombre.',
            'email.required'     => 'Escribe tu correo.',
            'email.email'        => 'Ese correo no es válido.',
            'email.unique'       => 'Ya existe una cuenta con ese correo.',
            'password.required'  => 'Escribe una contraseña.',
            'password.min'       => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'employee',
        ]);

        // Cuenta creada: se verifica el correo con un código (solo esta vez).
        $this->sendTwoFactorCode($user);

        $request->session()->put([
            'twofactor.user_id'  => $user->id,
            'twofactor.remember' => false,
        ]);

        return redirect()
            ->route('twofactor.show')
            ->with('success', 'Tu cuenta se creó. Te enviamos un código a tu correo para verificarla.');
    }

    /* =====================================================
       Recuperar contraseña
       ===================================================== */

    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(
            ['email' => ['required', 'string']],
            ['email.required' => 'Escribe tu correo.']
        );

        $user = User::where('email', $request->input('email'))->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'No existe una cuenta con ese correo.']);
        }

        $codigo = $this->freshCode();
        $user->forceFill([
            'reset_code'       => $codigo,
            'reset_expires_at' => now()->addMinutes(15),
        ])->save();

        $this->safeSend($user->inboxEmail(), new CodigoMail($user, $codigo, 'recuperacion'));

        $request->session()->put('reset.user_id', $user->id);

        return redirect()
            ->route('password.reset')
            ->with('success', 'Te enviamos un código a tu correo para restablecer tu contraseña.');
    }

    public function showReset(Request $request)
    {
        $user = User::find($request->session()->get('reset.user_id'));

        if (! $user) {
            return redirect()->route('password.forgot');
        }

        return view('auth.reset', ['maskedEmail' => $this->maskEmail($user->inboxEmail())]);
    }

    public function reset(Request $request)
    {
        $user = User::find($request->session()->get('reset.user_id'));

        if (! $user) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'code'     => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ], [
            'code.required'      => 'Escribe el código.',
            'code.digits'        => 'El código es de 6 dígitos.',
            'password.required'  => 'Escribe la contraseña nueva.',
            'password.min'       => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if (
            ! $user->reset_code
            || ! hash_equals($user->reset_code, $request->input('code'))
            || $user->reset_expires_at->isPast()
        ) {
            return back()->withErrors(['code' => 'El código no es válido o ya expiró. Pide uno nuevo.']);
        }

        $user->forceFill([
            'password'         => Hash::make($request->input('password')),
            'reset_code'       => null,
            'reset_expires_at' => null,
        ])->save();

        $request->session()->forget('reset.user_id');

        return redirect()
            ->route('login')
            ->with('success', 'Tu contraseña se cambió. Ya puedes iniciar sesión.');
    }

    /* =====================================================
       Cerrar sesión
       ===================================================== */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /* =====================================================
       Ayudantes internos
       ===================================================== */

    private function pendingUser(Request $request): ?User
    {
        return User::find($request->session()->get('twofactor.user_id'));
    }

    private function sendTwoFactorCode(User $user): void
    {
        $codigo = $this->freshCode();

        $user->forceFill([
            'two_factor_code'       => $codigo,
            'two_factor_expires_at' => now()->addMinutes(15),
        ])->save();

        $this->safeSend($user->inboxEmail(), new CodigoMail($user, $codigo, 'login'));
    }

    private function freshCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /** Si Gmail falla (sin internet, etc.) el error queda en el log y la app no se cae. */
    private function safeSend(string $to, $mailable): void
    {
        try {
            Mail::to($to)->send($mailable);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $visible = mb_substr($local, 0, 2);

        return $visible . str_repeat('•', max(mb_strlen($local) - 2, 1)) . '@' . $domain;
    }
}
