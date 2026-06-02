<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'notify_email',
        'password',
        'role',
        'verified_at',
        'two_factor_code',
        'two_factor_expires_at',
        'reset_code',
        'reset_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'reset_code',
    ];

    protected $casts = [
        'verified_at'           => 'datetime',
        'two_factor_expires_at' => 'datetime',
        'reset_expires_at'      => 'datetime',
    ];

    /** Una cuenta verificada entra directo; el código solo se pide al registrarse. */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Correo real donde se reciben los códigos. Las cuentas demo (como made@1)
     * no tienen buzón propio, así que sus códigos llegan al Gmail configurado.
     */
    public function inboxEmail(): string
    {
        return $this->notify_email ?: $this->email;
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
