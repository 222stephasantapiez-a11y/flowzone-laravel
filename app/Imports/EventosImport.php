<?php

namespace App\Imports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class EventosImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?Evento
    {
        $nombre = trim($row['nombre'] ?? '');

        if (empty($nombre)) {
            return null;
        }

        $this->imported++;

        return new Evento([
            'nombre'      => $nombre,
            'descripcion' => $row['descripcion'] ?? null,
            'fecha'       => $row['fecha'] ?? null,
            'hora'        => $row['hora'] ?? null,
            'ubicacion'   => $row['ubicacion'] ?? null,
            'categoria'   => $row['categoria'] ?? null,
            'precio'      => is_numeric($row['precio'] ?? null) ? $row['precio'] : 0,
            'organizador' => $row['organizador'] ?? null,
            'contacto'    => $row['contacto'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:150',
            'fecha'  => 'required|date',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nombre.required' => 'El campo "nombre" es obligatorio.',
            'fecha.required'  => 'El campo "fecha" es obligatorio.',
            'fecha.date'      => 'El campo "fecha" debe ser una fecha válida.',
        ];
    }
}
