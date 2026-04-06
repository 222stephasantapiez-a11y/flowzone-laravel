<?php

namespace App\Exports;

use App\Models\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventosExport implements FromCollection
{
   public function collection()
   {
    return Evento::select(
        'id',
        'nombre',
        'descripcion',
        'fecha',
        'ubicacion',
        'created_at',
    )->get();
   }
}
