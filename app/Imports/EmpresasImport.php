<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmpresasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['nombre'] ?? '',
            'email' => $row['correo'] ?? '',
            'telefono' => $row['telefono'] ?? '',
            'password' => Hash::make('12345678'),
            'rol' => 'empresa',
        ]);
    }
}