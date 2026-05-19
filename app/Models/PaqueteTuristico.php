<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaqueteTuristico extends Model
{
    protected $table = 'paquetes_turisticos';

    protected $fillable = [
        'empresa_id','nombre','descripcion','itinerario','ruta','incluye','no_incluye',
        'duracion_dias','duracion_horas','cupo_maximo','cupo_minimo','cupo_disponible',
        'precio_adulto','precio_nino','punto_salida','hora_salida','fechas_disponibles',
        'activo','imagen','dificultad','que_llevar',
    ];

    protected $casts = [
        'ruta'               => 'array',
        'incluye'            => 'array',
        'no_incluye'         => 'array',
        'fechas_disponibles' => 'array',
        'que_llevar'         => 'array',
        'activo'             => 'boolean',
        'precio_adulto'      => 'decimal:2',
        'precio_nino'        => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function reservas()
    {
        return $this->hasMany(ReservaPaquete::class, 'paquete_id');
    }

    public function tieneCupo(): bool
    {
        return $this->cupo_disponible > 0 && $this->activo;
    }
}
