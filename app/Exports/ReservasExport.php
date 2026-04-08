<?php

namespace App\Exports;

use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Reserva::select(
            'usuario_id',
            'hotel_id',
            'fecha_entrada',
            'fecha_salida',
            'num_personas',
            'precio_total',
            'estado'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Usuario ID',
            'Hotel ID',
            'Fecha Entrada',
            'Fecha Salida',
            'Número Personas',
            'Precio Total',
            'Estado'
        ];
    }
}