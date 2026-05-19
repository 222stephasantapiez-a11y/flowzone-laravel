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
        'disponible_hoy', 'hora_inicio', 'hora_fin',
        'stock_diario', 'stock_actual', 'dias_semana',
    ];

    protected $casts = [
        'precio_promedio' => 'decimal:2',
        'disponible_hoy'  => 'boolean',
        'dias_semana'     => 'array',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function estaDisponibleAhora(): bool
    {
        if (!$this->disponible_hoy) return false;
        $ahora = now()->format('H:i:s');
        if ($this->hora_inicio && $ahora < $this->hora_inicio) return false;
        if ($this->hora_fin && $ahora > $this->hora_fin) return false;
        if ($this->stock_actual !== null && $this->stock_actual <= 0) return false;
        return true;
    }
}
