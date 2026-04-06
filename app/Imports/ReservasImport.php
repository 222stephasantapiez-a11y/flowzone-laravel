<?php

namespace App\Imports;

use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ReservasImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // empieza desde la fila 2, salta encabezados
    }

    public function model(array $row)
    {
        return new Reserva([
            'usuario_id'    => $row[0],
            'hotel_id'      => $row[1],
            'fecha_entrada' => $row[2],
            'fecha_salida'  => $row[3],
            'num_personas'  => $row[4],
            'precio_total'  => $row[5],
            'estado'        => $row[6],
        ]);
    }
}