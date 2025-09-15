<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductoController;
use App\Http\Controllers\api\UsuarioController;
use App\Http\Controllers\api\ClienteController;
use App\Http\Controllers\LoginController;

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('usuarios', UsuarioController::class);
    Route::apiResource('clientes', ClienteController::class);
});

// Route::apiResource('productos', ProductoController::class);
// Route::apiResource('usuarios', UsuarioController::class);
// Route::apiResource('clientes', ClienteController::class);
