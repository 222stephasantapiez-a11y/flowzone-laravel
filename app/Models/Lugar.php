<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    protected $table = "lugares";

    protected $fillable = [
        'nombre', 'descripcion', 'ubicacion',
        'latitud', 'longitud', 'categoria',
        'imagen', 'precio_entrada', 'horario',
    ];

    protected $casts = [
        'precio_entrada' => 'decimal:2',
    ];
}
