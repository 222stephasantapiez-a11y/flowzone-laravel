<?php

namespace App\Imports;

use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ReservasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?Reserva
    {
        $usuarioId = $row['usuario_id'] ?? null;
        $hotelId   = $row['hotel_id'] ?? null;

        if (empty($usuarioId) || empty($hotelId)) {
            return null;
        }

        $this->imported++;

        return new Reserva([
            'usuario_id'    => $usuarioId,
            'hotel_id'      => $hotelId,
            'fecha_entrada' => $row['fecha_entrada'] ?? null,
            'fecha_salida'  => $row['fecha_salida'] ?? null,
            'num_personas'  => is_numeric($row['num_personas'] ?? null) ? (int) $row['num_personas'] : 1,
            'precio_total'  => is_numeric($row['precio_total'] ?? null) ? $row['precio_total'] : 0,
            'estado'        => in_array($row['estado'] ?? '', ['pendiente', 'confirmada', 'cancelada'])
                                ? $row['estado']
                                : 'pendiente',
        ]);
    }

    public function rules(): array
    {
        return [
            'usuario_id'    => 'required|integer|exists:users,id',
            'hotel_id'      => 'required|integer|exists:hoteles,id',
            'fecha_entrada' => 'required|date',
            'fecha_salida'  => 'required|date',
            'num_personas'  => 'required|integer|min:1',
            'precio_total'  => 'required|numeric|min:0',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'usuario_id.required' => 'El campo "usuario_id" es obligatorio.',
            'usuario_id.exists'   => 'El usuario_id no existe en la base de datos.',
            'hotel_id.required'   => 'El campo "hotel_id" es obligatorio.',
            'hotel_id.exists'     => 'El hotel_id no existe en la base de datos.',
            'fecha_entrada.required' => 'El campo "fecha_entrada" es obligatorio.',
            'fecha_salida.required'  => 'El campo "fecha_salida" es obligatorio.',
        ];
    }
}
