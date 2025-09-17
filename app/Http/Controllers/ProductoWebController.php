<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoWebController extends Controller
{
    public function producto_web()
     {
          return view('productos.productosweb');
     }
}
