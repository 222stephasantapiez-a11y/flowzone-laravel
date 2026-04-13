<?php

namespace App\Exports;

use App\Models\Lugar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

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

    public function headings(): array
{
    return [
        'ID',
        'Nombre',
        'Descripción',
        'Ubicación',
        'Fecha Registro'
    ];
}
}