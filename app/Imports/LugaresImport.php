<?php

namespace App\Imports;

use App\Models\Lugar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LugaresImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $existe = Lugar::where('nombre', $row['nombre'])
            ->where('ubicacion', $row['ubicacion'])
            ->exists();

        if ($existe) {
            return null; // omite lugares repetidos
        }

        return new Lugar([
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'ubicacion' => $row['ubicacion'],
        ]);
    }
}