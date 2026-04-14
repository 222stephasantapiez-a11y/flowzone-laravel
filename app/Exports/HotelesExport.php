<?php

namespace App\Exports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\FromCollection;

class HotelesExport implements FromCollection
{
    public function collection()
    {
        return Hotel::select(
            'id',
            'nombre',
            'descripcion',
            'precio',
            'ubicacion',
            'capacidad',
            'servicios',
            'telefono',
            'disponibilidad',
            'created_at'
        )->get();
    }
}

