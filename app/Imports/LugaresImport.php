<?php

namespace App\Imports;

use App\Models\Lugar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LugaresImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lugar([
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'ubicacion' => $row['ubicacion'],
        ]);
    }
}