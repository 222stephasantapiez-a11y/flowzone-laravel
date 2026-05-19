<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'habitaciones';

    protected $fillable = [
        'hotel_id','nombre','tipo','num_camas','tipo_cama',
        'capacidad_personas','precio_noche','disponible','descripcion','amenidades',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'amenidades' => 'array',
        'precio_noche' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function reservas()
    {
        return $this->hasMany(ReservaHabitacion::class);
    }

    public function estaDisponible(string $entrada, string $salida): bool
    {
        return !$this->reservas()
            ->whereIn('estado', ['pendiente','confirmada'])
            ->where('fecha_entrada', '<', $salida)
            ->where('fecha_salida', '>', $entrada)
            ->exists();
    }
}
