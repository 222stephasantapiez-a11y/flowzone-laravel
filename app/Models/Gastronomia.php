<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gastronomia extends Model
{
    protected $table = 'gastronomia';

    protected $fillable = [
        'nombre', 'descripcion', 'tipo', 'precio_promedio',
        'restaurante', 'direccion', 'telefono', 'imagen', 'ingredientes',
    ];

    protected $casts = [
        'precio_promedio' => 'decimal:2',
    ];
}
