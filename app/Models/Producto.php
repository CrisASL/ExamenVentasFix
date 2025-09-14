<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    protected $table = "productos";
    
    protected $fillable = [
        'sku',
        'nombre',
        'descripcion_corta',
        'descripcion_larga',
        'imagen_url',
        'precio_neto',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'stock_bajo',
        'stock_alto'
    ];
}
