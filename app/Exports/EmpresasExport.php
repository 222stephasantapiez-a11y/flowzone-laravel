<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmpresasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('rol', 'empresa')
            ->select(
                'id',
                'name',
                'email',
                'telefono',
                'created_at'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Correo',
            'Teléfono',
            'Fecha Registro'
        ];
    }
}