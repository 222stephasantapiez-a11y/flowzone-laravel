<?php

namespace App\Imports;

use App\Models\Gastronomia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class GastronomiaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?Gastronomia
    {
        $nombre = trim($row['nombre'] ?? '');

        if (empty($nombre)) {
            return null;
        }

        $this->imported++;

        return new Gastronomia([
            'nombre'          => $nombre,
            'descripcion'     => $row['descripcion'] ?? null,
            'tipo'            => $row['tipo'] ?? null,
            'precio_promedio' => is_numeric($row['precio_promedio'] ?? null) ? $row['precio_promedio'] : null,
            'restaurante'     => $row['restaurante'] ?? null,
            'direccion'       => $row['direccion'] ?? null,
            'ubicacion'       => $row['ubicacion'] ?? null,
            'telefono'        => $row['telefono'] ?? null,
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
