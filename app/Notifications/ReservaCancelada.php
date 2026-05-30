<?php

namespace App\Notifications;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReservaCancelada extends Notification
{
    use Queueable;

    public function __construct(public Reserva $reserva) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'tipo'           => 'reserva_cancelada',
            'mensaje'        => '❌ Tu reserva en ' . $this->reserva->hotel->nombre . ' fue cancelada.',
            'reserva_id'     => $this->reserva->id,
            'hotel'          => $this->reserva->hotel->nombre,
            'fecha_entrada'  => $this->reserva->fecha_entrada->format('d/m/Y'),
            'fecha_salida'   => $this->reserva->fecha_salida->format('d/m/Y'),
            'precio_total'   => $this->reserva->precio_total,
            'referencia'     => $this->reserva->referencia_pago,
        ];
    }
}