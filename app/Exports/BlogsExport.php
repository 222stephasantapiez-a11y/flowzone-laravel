<?php

namespace App\Exports;

use App\Models\BlogPost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BlogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BlogPost::select(
            'id',
            'titulo',
            'contenido',
            'slug',
            'publicado',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Título',
            'Contenido',
            'Slug',
            'Publicado',
            'Fecha'
        ];
    }
}