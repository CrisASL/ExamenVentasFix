<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientesWebController extends Controller
{
   public function clientes_web()
    {
        return view('clientes.clientesweb');
    }
}