<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = "hoteles";

    protected $fillable = [
        'empresa_id', 'nombre', 'descripcion', 'precio', 'ubicacion',
        'latitud', 'longitud', 'imagen', 'servicios',
        'capacidad', 'disponibilidad', 'telefono', 'email',
    ];

    protected $casts = [
        'disponibilidad' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class, 'empresa_id');
    }
}
