<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

/**
 * Trait reutilizable para todos los imports.
 * Implementar en la clase: SkipsOnError, SkipsOnFailure
 */
trait BaseImport
{
    protected array $errors   = [];
    protected int   $imported = 0;

    public function onError(Throwable $e): void
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $row    = $failure->row();
            $field  = implode(', ', $failure->attribute() ? [$failure->attribute()] : []);
            $msgs   = implode(', ', $failure->errors());
            $this->errors[] = "Fila {$row} [{$field}]: {$msgs}";
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
