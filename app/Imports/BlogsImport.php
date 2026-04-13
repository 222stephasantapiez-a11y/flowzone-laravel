<?php

namespace App\Imports;

use App\Models\BlogPost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BlogsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $titulo = $row['titulo'] ?? '';
        $slug = $row['slug'] ?? null;

        $existe = BlogPost::where('titulo', $titulo)
            ->orWhere('slug', $slug)
            ->exists();

        if ($existe) {
            return null; // omite blogs repetidos
        }

        return new BlogPost([
            'titulo' => $titulo,
            'contenido' => $row['contenido'] ?? '',
            'slug' => $slug,
            'publicado' => $row['publicado'] ?? 0,
        ]);
    }
}