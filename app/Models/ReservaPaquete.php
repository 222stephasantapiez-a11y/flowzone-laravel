<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaPaquete extends Model
{
    protected $table = 'reservas_paquete';

    protected $fillable = [
        'paquete_id','usuario_id','fecha_reserva','num_adultos','num_ninos',
        'precio_total','estado','notas','telefono_contacto',
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'precio_total'  => 'decimal:2',
    ];

    public function paquete()
    {
        return $this->belongsTo(PaqueteTuristico::class, 'paquete_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
