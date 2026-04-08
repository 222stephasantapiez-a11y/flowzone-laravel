<?php

namespace App\Imports;

use App\Models\BlogPost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BlogsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new BlogPost([
            'titulo' => $row['titulo'] ?? '',
            'contenido' => $row['contenido'] ?? '',
            'slug' => $row['slug'] ?? null,
            'publicado' => $row['publicado'] ?? 0,
        ]);
    }
}