<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
        'nombre', 'descripcion', 'fecha', 'hora',
        'ubicacion', 'categoria', 'imagen',
        'precio', 'organizador', 'contacto',
    ];

    protected $casts = [
        'fecha'  => 'date',
        'precio' => 'decimal:2',
    ];
}
