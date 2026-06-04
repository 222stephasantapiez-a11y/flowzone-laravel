<?php

namespace App\Imports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Throwable;

class EventosImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    private int $imported = 0;
    private int $updated  = 0;
    private array $errors = [];

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row): ?Evento
    {
        $row    = $this->normalizeRow($row);
        $nombre = trim($row['nombre'] ?? '');

        if (empty($nombre)) {
            return null;
        }

        $datos = [
            'descripcion' => $row['descripcion'] ?? null,
            'fecha'       => $this->parseFecha($row['fecha'] ?? null),
            'hora'        => $this->parseHora($row['hora'] ?? null),
            'ubicacion'   => $row['ubicacion'] ?? null,
            'categoria'   => $row['categoria'] ?? null,
            'precio'      => $this->parsePrecio($row['precio'] ?? null),
            'organizador' => $row['organizador'] ?? null,
            'contacto'    => $row['contacto'] ?? null,
        ];

        $evento = Evento::where('nombre', $nombre)->first();

        if ($evento) {
            // Ya existe → actualiza
            $evento->update($datos);
            $this->updated++;
            return null; // null porque ya lo guardamos manualmente
        }

        // No existe → crea
        $this->imported++;
        return new Evento(array_merge(['nombre' => $nombre], $datos));
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getUpdatedCount(): int
    {
        return $this->updated;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[$this->normalizeKey((string) $key)] = $value;
        }

        $aliases = [
            'nombre'      => ['name', 'evento', 'titulo', 'title'],
            'fecha'       => ['date', 'fecha_evento', 'dia', 'fecha_inicio'],
            'hora'        => ['time', 'hora_inicio', 'horario'],
            'descripcion' => ['description', 'detalle', 'desc'],
            'ubicacion'   => ['location', 'lugar', 'direccion', 'address'],
            'categoria'   => ['category', 'tipo', 'type'],
            'precio'      => ['price', 'costo', 'valor', 'cost'],
            'organizador' => ['organizer', 'organizado_por'],
            'contacto'    => ['contact', 'telefono', 'email'],
        ];

        foreach ($aliases as $canonical => $variants) {
            if (!isset($normalized[$canonical])) {
                foreach ($variants as $variant) {
                    if (isset($normalized[$variant])) {
                        $normalized[$canonical] = $normalized[$variant];
                        break;
                    }
                }
            }
        }

        return $normalized;
    }

    private function normalizeKey(string $key): string
    {
        $key = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $key);
        return strtolower(trim($key));
    }

    private function parseFecha(mixed $value): ?string
    {
        if (empty($value)) return null;

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $value = trim((string) $value);

        // DD/MM/YYYY, DD-MM-YYYY, DD.MM.YYYY
        if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{2,4})$/', $value, $m)) {
            $anio = strlen($m[3]) === 2 ? '20' . $m[3] : $m[3];
            return sprintf('%s-%02d-%02d', $anio, (int)$m[2], (int)$m[1]);
        }

        // ISO YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Meses en español
        $meses = [
            'enero' => '01', 'ene' => '01',
            'febrero' => '02', 'feb' => '02',
            'marzo' => '03', 'mar' => '03',
            'abril' => '04', 'abr' => '04',
            'mayo' => '05',
            'junio' => '06', 'jun' => '06',
            'julio' => '07', 'jul' => '07',
            'agosto' => '08', 'ago' => '08',
            'septiembre' => '09', 'sep' => '09', 'sept' => '09',
            'octubre' => '10', 'oct' => '10',
            'noviembre' => '11', 'nov' => '11',
            'diciembre' => '12', 'dic' => '12',
        ];

        $texto = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value));

        foreach ($meses as $nombreMes => $numMes) {
            if (preg_match('/(\d{1,2})\s+(?:de\s+)?' . $nombreMes . '(?:\s+de\s+(\d{4}))?/', $texto, $m)) {
                return sprintf('%s-%s-%s', $m[2] ?? date('Y'), $numMes, str_pad($m[1], 2, '0', STR_PAD_LEFT));
            }
            if (preg_match('/' . $nombreMes . '\s+(\d{4})/', $texto, $m)) {
                return "{$m[1]}-{$numMes}-01";
            }
        }

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
                return ExcelDate::excelToDateTimeObject((float) $value)->format('H:i:s');
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
        if (empty($value)) return 0.0;

        if (is_numeric($value)) return (float) $value;

        $lower = strtolower(trim((string) $value));
        if (in_array($lower, ['gratuito', 'gratis', 'free', '-', ''])) return 0.0;

        $clean = preg_replace('/[^0-9.,]/', '', (string) $value);
        $clean = str_replace(',', '.', $clean);

        return is_numeric($clean) ? (float) $clean : 0.0;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'fecha'  => ['required'],
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