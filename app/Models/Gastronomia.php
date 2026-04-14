<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gastronomia extends Model
{
    protected $table = 'gastronomia';

    protected $fillable = [
        'nombre', 'descripcion', 'tipo', 'precio_promedio',
        'restaurante', 'direccion', 'latitud', 'longitud',
        'telefono', 'imagen', 'ingredientes', 'empresa_id', 'ubicacion',
    ];

    protected $casts = [
        'precio_promedio' => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
