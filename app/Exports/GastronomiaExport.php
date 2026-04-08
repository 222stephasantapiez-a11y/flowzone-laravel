<?php

namespace App\Exports;

use App\Models\Gastronomia;
use Maatwebsite\Excel\Concerns\FromCollection;

class GastronomiaExport implements FromCollection
{
    public function collection()
    {
        return Gastronomia::select(
            'id',
            'nombre',
            'descripcion',
            'ubicacion',
            'precio',
            'created_at'
        )->get();
    }
}