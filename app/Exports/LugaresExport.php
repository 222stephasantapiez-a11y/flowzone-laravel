<?php

namespace App\Exports;

use App\Models\Lugar;
use Maatwebsite\Excel\Concerns\FromCollection;

class LugaresExport implements FromCollection
{
    public function collection()
    {
        return Lugar::select(
            'id',
            'nombre',
            'descripcion',
            'ubicacion',
            'created_at'
        )->get();
    }
}