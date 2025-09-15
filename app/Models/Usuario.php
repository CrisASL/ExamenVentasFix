<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Cambiar Model por Authenticatable
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = "usuarios";

    protected $fillable = [
        'rut',
        'nombre',
        'apellido',
        'email',
        'password' 
    ];

    /**
     * Obtener el identificador para el JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna claves personalizadas para el JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

