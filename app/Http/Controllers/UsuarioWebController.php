<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioWebController extends Controller
{
   public function usuario_web()
    {
        return view('usuarios.usuariosweb');
    }
}
