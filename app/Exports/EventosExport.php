<?php

namespace App\Exports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Evento::select(
            'id',
            'nombre',
            'descripcion',
            'fecha',
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
            'Fecha',
            'Ubicación',
            'Fecha Registro'
        ];
    }
}