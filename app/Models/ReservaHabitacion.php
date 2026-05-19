<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaHabitacion extends Model
{
    protected $table = 'reservas_habitacion';

    protected $fillable = [
        'habitacion_id','usuario_id','fecha_entrada','fecha_salida',
        'num_huespedes','precio_total','estado','notas',
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_salida'  => 'date',
        'precio_total'  => 'decimal:2',
    ];

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
