<?php

namespace App\Imports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EventosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $existe = Evento::where('nombre', $row['nombre'])
            ->where('fecha', $row['fecha'])
            ->where('ubicacion', $row['ubicacion'])
            ->exists();

        if ($existe) {
            return null; // omite eventos repetidos
        }

        return new Evento([
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'fecha' => $row['fecha'],
            'ubicacion' => $row['ubicacion'],
        ]);
    }
}