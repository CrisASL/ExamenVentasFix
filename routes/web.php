<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

// Mostrar formulario login (GET)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Procesar login (POST)
Route::post('/login', [LoginController::class, 'login'])->name('usuario.login');