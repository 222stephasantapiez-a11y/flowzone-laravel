<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsuariosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::select(
            'id',
            'name',
            'email',
            'rol',
            'estado',
            'telefono',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Correo',
            'Rol',
            'Estado',
            'Telefono',
            'Fecha Registro'
        ];
    }
}