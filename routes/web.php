<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    // Paso 1: correo y contraseña.
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    // Paso 2: código de verificación enviado por Gmail.
    Route::get('/verificar', [AuthController::class, 'showTwoFactor'])->name('twofactor.show');
    Route::post('/verificar', [AuthController::class, 'verifyTwoFactor'])->name('twofactor.verify');
    Route::post('/verificar/reenviar', [AuthController::class, 'resendTwoFactor'])->name('twofactor.resend');

    // Crear cuenta nueva.
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.attempt');

    // Recuperar contraseña con código por Gmail.
    Route::get('/recuperar', [AuthController::class, 'showForgot'])->name('password.forgot');
    Route::post('/recuperar', [AuthController::class, 'sendResetCode'])->name('password.email');
    Route::get('/recuperar/codigo', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/recuperar/codigo', [AuthController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/sales/new', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
});
