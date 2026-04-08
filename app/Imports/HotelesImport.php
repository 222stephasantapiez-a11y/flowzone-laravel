<?php

namespace App\Imports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotelesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Hotel([
            'nombre' => $row['nombre'],
            'direccion' => $row['direccion'],
            'precio' => $row['precio'],
        ]);
    }
}
