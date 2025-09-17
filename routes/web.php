<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioWebController;
use App\Http\Controllers\ProductoWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientesWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/usuarios/web', [UsuarioWebController::class, 'usuario_web'])->name('usuarios.webIndex');
Route::get('/productos/web', [ProductoWebController::class, 'producto_web'])->name('productos.webIndex');
Route::get('/clientes/web', [ClientesWebController::class, 'clientes_web'])->name('clientes.webIndex');

// Mostrar formulario login (GET)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Procesar login (POST)
Route::post('/login', [LoginController::class, 'login'])->name('usuario.login');