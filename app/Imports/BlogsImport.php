<?php

namespace App\Imports;

use App\Models\BlogPost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BlogsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, SkipsEmptyRows
{
    use BaseImport;

    public function model(array $row): ?BlogPost
    {
        $titulo = trim($row['titulo'] ?? '');

        if (empty($titulo)) {
            return null;
        }

        $this->imported++;

        return new BlogPost([
            'titulo'     => $titulo,
            'contenido'  => $row['contenido'] ?? '',
            'slug'       => !empty($row['slug']) ? $row['slug'] : null,
            'publicado'  => isset($row['publicado']) ? (bool) $row['publicado'] : false,
        ]);
    }

    public function rules(): array
    {
        return [
            'titulo'    => 'required|string|max:200',
            'contenido' => 'required|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'titulo.required'    => 'El campo "titulo" es obligatorio.',
            'contenido.required' => 'El campo "contenido" es obligatorio.',
        ];
    }
}
