<?php

namespace App\Exports;

use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\FromCollection;

class HotelesExport implements FromCollection
{
    public function collection()
    {
        return Hotel::select(
            'id',
            'nombre',
            'direccion',
            'precio',
            'created_at'
        )->get();
    }
}

