<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsuariosExport implements FromCollection
{
    public function collection()
    {
        return User::select('id', 'name', 'email', 'created_at')->get();
    }
}