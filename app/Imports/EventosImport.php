<?php

namespace App\Imports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Throwable;

class EventosImport implements ToModel, WithHeadingRow, WithStartRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    private int $imported = 0;
    private array $errors = [];

    // Los encabezados están en la fila 2
    public function headingRow(): int
    {
        return 2;
    }

    // Los datos empiezan en la fila 3
    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row): ?Evento
    {
        $nombre = trim($row['nombre'] ?? '');

        if (empty($nombre)) {
            return null;
        }

        if (Evento::where('nombre', $nombre)->exists()) {
            return null;
        }

        $fecha = $this->parseFecha($row['fecha'] ?? null);
        $hora  = $this->parseHora($row['hora'] ?? null);

        $this->imported++;

        return new Evento([
            'nombre'      => $nombre,
            'descripcion' => $row['descripcion'] ?? null,
            'fecha'       => $fecha,
            'hora'        => $hora,
            'ubicacion'   => $row['ubicacion'] ?? null,
            'categoria'   => $row['categoria'] ?? null,
            'precio'      => $this->parsePrecio($row['precio'] ?? null),
            'organizador' => $row['organizador'] ?? null,
            'contacto'    => $row['contacto'] ?? null,
        ]);
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function parseFecha(mixed $value): ?string
    {
        if (empty($value)) return null;

        // Serial numérico de Excel
        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $value = trim($value);

        // Mapa de meses en español
        $meses = [
            'enero'      => '01', 'febrero'    => '02', 'marzo'      => '03',
            'abril'      => '04', 'mayo'        => '05', 'junio'      => '06',
            'julio'      => '07', 'agosto'      => '08', 'septiembre' => '09',
            'octubre'    => '10', 'noviembre'   => '11', 'diciembre'  => '12',
        ];

        $texto = strtolower($value);

        foreach ($meses as $nombreMes => $numMes) {
            // Patrón: "N de mes de YYYY" o "N de mes" o "Sábado N de mes de YYYY"
            if (preg_match('/(\d{1,2})\s+(?:de\s+)?' . $nombreMes . '(?:\s+de\s+(\d{4}))?/', $texto, $m)) {
                $dia  = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                $anio = $m[2] ?? '2026';
                return "{$anio}-{$numMes}-{$dia}";
            }

            // Patrón: "mes YYYY" → primer día del mes
            if (preg_match('/' . $nombreMes . '\s+(\d{4})/', $texto, $m)) {
                return "{$m[1]}-{$numMes}-01";
            }

            // Patrón: solo mes mencionado sin año → primer día, año 2026
            if (str_contains($texto, $nombreMes)) {
                return "2026-{$numMes}-01";
            }
        }

        // Fallback: Carbon para fechas ISO o en inglés
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseHora(mixed $value): ?string
    {
        if (empty($value)) return null;

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('H:i:s');
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parsePrecio(mixed $value): float
    {
        if (empty($value)) return 0;

        if (is_numeric($value)) return (float) $value;

        $lower = strtolower(trim($value));
        if (in_array($lower, ['gratuito', 'gratis', 'free', '-', ''])) return 0;

        // Intenta extraer número si hay texto mezclado
        preg_match('/[\d]+/', $value, $matches);
        return isset($matches[0]) ? (float) $matches[0] : 0;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:150',
            'fecha'  => 'required',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nombre.required' => 'El campo "nombre" es obligatorio.',
            'fecha.required'  => 'El campo "fecha" es obligatorio.',
        ];
    }

    public function onError(Throwable $e): void
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->errors[] = 'Fila ' . $failure->row() . ': ' . implode(', ', $failure->errors());
        }
    }
}