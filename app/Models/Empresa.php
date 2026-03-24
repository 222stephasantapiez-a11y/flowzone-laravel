<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'usuario_id', 'nombre', 'telefono', 'direccion', 'aprobado',
    ];

    protected $casts = [
        'aprobado' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
