<?php

namespace App\Imports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class HotelesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?Hotel
    {
        $nombre = trim($row['nombre'] ?? '');

        if (empty($nombre)) {
            return null;
        }

        $this->imported++;

        return new Hotel([
            'nombre'      => $nombre,
            'descripcion' => $row['descripcion'] ?? null,
            'precio'      => is_numeric($row['precio'] ?? null) ? $row['precio'] : 0,
            'ubicacion'   => $row['ubicacion'] ?? null,
            'capacidad'   => is_numeric($row['capacidad'] ?? null) ? (int) $row['capacidad'] : null,
            'servicios'   => $row['servicios'] ?? null,
            'telefono'    => $row['telefono'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:150',
            'precio' => 'required|numeric|min:0',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nombre.required' => 'El campo "nombre" es obligatorio.',
            'precio.required' => 'El campo "precio" es obligatorio.',
            'precio.numeric'  => 'El campo "precio" debe ser un número.',
        ];
    }
}
