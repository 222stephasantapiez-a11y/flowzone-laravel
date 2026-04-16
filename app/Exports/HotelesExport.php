<?php

namespace App\Exports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

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

    public function headings(): array
{
    return [
        'ID',
        'Nombre',
        'Precio',
        'Fecha Registro'
    ];
}
}

