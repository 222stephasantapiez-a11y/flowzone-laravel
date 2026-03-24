<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = "hoteles";

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'ubicacion',
        'latitud', 'longitud', 'imagen', 'servicios',
        'capacidad', 'disponibilidad', 'telefono', 'email',
    ];

    protected $casts = [
        'disponibilidad' => 'boolean',
        'precio' => 'decimal:2',
    ];
}
