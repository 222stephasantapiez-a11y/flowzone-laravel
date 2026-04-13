<?php

namespace App\Exports;

use App\Models\Gastronomia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GastronomiaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Gastronomia::select(
            'id',
            'nombre',
            'descripcion',
            'ubicacion',
            'precio_promedio',
            'created_at'
        )->get();
    }

    public function headings(): array
{
    return [
        'ID',
        'Nombre',
        'Descripción',
        'Ubicación',
        'Precio',
        'Fecha Registro'
    ];
}
}