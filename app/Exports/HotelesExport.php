<?php

namespace App\Exports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HotelesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Hotel::select(
            'id',
            'nombre',
            'precio',
            'ubicacion',
            'created_at'
        )->get();
    }

    public function headings(): array
{
    return [
        'ID',
        'Nombre',
        'Precio',
        'direccion',
        'Fecha Registro'
    ];
}
}

