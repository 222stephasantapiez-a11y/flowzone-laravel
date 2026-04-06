<?php

namespace App\Imports;

use App\Models\Gastronomia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GastronomiaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Gastronomia([
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'ubicacion' => $row['ubicacion'],
            'precio' => $row['precio'],
        ]);
    }
}