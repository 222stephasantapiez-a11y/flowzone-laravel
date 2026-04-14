<?php

namespace App\Imports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class HotelesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Hotel([
            'nombre'      => $row['nombre'],
            'descripcion' => $row['descripcion'] ?? null,
            'precio'      => $row['precio'],
            'ubicacion'   => $row['ubicacion'] ?? null,
            'capacidad'   => $row['capacidad'] ?? null,
            'servicios'   => $row['servicios'] ?? null,
            'telefono'    => $row['telefono'] ?? null,
        ]);
    }
}
