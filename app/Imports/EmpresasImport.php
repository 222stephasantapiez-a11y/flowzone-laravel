<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class EmpresasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?User
    {
        $nombre = trim($row['nombre'] ?? '');
        $correo = trim($row['correo'] ?? '');

        if (empty($nombre) || empty($correo)) {
            return null;
        }

        // Evitar duplicados por email
        if (User::where('email', $correo)->exists()) {
            $this->errors[] = "El correo \"{$correo}\" ya está registrado, se omitió.";
            return null;
        }

        $user = User::create([
            'name'     => $nombre,
            'email'    => $correo,
            'telefono' => $row['telefono'] ?? null,
            'password' => Hash::make('12345678'),
            'rol'      => 'empresa',
            'estado'   => 'pendiente',
        ]);

        // Crear registro en tabla empresas
        Empresa::create([
            'usuario_id' => $user->id,
            'nombre'     => $nombre,
            'telefono'   => $row['telefono'] ?? null,
            'direccion'  => $row['direccion'] ?? null,
            'aprobado'   => false,
        ]);

        $this->imported++;

        // Retornamos null porque ya guardamos manualmente
        return null;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:200',
            'correo' => 'required|email|max:200',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nombre.required' => 'El campo "nombre" es obligatorio.',
            'correo.required' => 'El campo "correo" es obligatorio.',
            'correo.email'    => 'El campo "correo" debe ser un email válido.',
        ];
    }
}
