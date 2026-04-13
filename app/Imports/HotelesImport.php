<?php

namespace App\Imports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotelesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $existe = Hotel::where('nombre', $row['nombre'])
            ->where('ubicacion', $row['direccion'])
            ->exists();

        if ($existe) {
            return null; // omite hoteles repetidos
        }

        return new Hotel([
            'nombre' => $row['nombre'],
            'ubicacion' => $row['direccion'],
            'precio' => $row['precio'],
        ]);
    }
}