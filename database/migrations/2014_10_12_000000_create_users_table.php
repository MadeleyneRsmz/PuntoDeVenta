<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            // Correo real al que llegan los códigos y avisos (útil para cuentas demo como made@1).
            $table->string('notify_email')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'employee'])->default('employee');
            // Se llena cuando la cuenta ya verificó su correo (el código solo se pide al registrarse).
            $table->timestamp('verified_at')->nullable();
            // Verificación del correo al registrarse: código de 6 dígitos enviado por Gmail.
            $table->string('two_factor_code', 10)->nullable();
            $table->timestamp('two_factor_expires_at')->nullable();
            // Recuperación de contraseña: código de 6 dígitos enviado por Gmail.
            $table->string('reset_code', 10)->nullable();
            $table->timestamp('reset_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
