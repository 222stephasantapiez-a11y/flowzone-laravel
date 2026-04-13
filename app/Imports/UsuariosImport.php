<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsuariosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $existe = User::where('name', $row['nombre'])
            ->orWhere('email', $row['correo'])
            ->exists();

        if ($existe) {
            return null; // omite usuarios repetidos
        }

        return new User([
            'name' => $row['nombre'],
            'email' => $row['correo'],
            'password' => Hash::make('12345678'),
        ]);
    }
}