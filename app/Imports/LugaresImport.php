<?php

namespace App\Imports;

use App\Models\Lugar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class LugaresImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?Lugar
    {
        $nombre = trim($row['nombre'] ?? '');

        if (empty($nombre)) {
            return null;
        }

        $this->imported++;

        return new Lugar([
            'nombre'      => $nombre,
            'descripcion' => $row['descripcion'] ?? null,
            'ubicacion'   => $row['ubicacion'] ?? null,
            'categoria'   => $row['categoria'] ?? null,
            'precio_entrada' => is_numeric($row['precio_entrada'] ?? null) ? $row['precio_entrada'] : 0,
            'horario'     => $row['horario'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:150',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nombre.required' => 'El campo "nombre" es obligatorio.',
        ];
    }
}
