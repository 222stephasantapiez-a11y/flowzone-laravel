<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id', 'hotel_id', 'habitacion_id', 'fecha_entrada',
        'fecha_salida', 'num_personas', 'precio_total', 'estado',
        'metodo_pago', 'referencia_pago', 'estado_pago',
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_salida'  => 'date',
        'precio_total'  => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'habitacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getMetodoPagoLabelAttribute(): string
    {
        return match ($this->metodo_pago) {
            'nequi'           => 'Nequi',
            'bancolombia_pse' => 'Bancolombia PSE',
            'tarjeta'         => 'Tarjeta de Crédito/Débito',
            default           => 'Sin método',
        };
    }
}