<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Cliente;

class DashboardController extends Controller
{
    public function index()
    {
        $usuariosCount = Usuario::count();
        $productosCount = Producto::count();
        $clientesCount = Cliente::count();

        return view('dashboard', compact('usuariosCount', 'productosCount', 'clientesCount'));
    }
}
