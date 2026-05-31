<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmpresaImagen extends Model
{
    protected $table = 'empresa_imagenes';

    protected $fillable = [
        'empresa_id', 'ruta', 'titulo', 'categoria', 'orden', 'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function getUrlPublicaAttribute(): string
    {
        if (str_starts_with($this->ruta, 'http')) {
            return $this->ruta;
        }
        return Storage::disk('public')->url($this->ruta);
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', true)->orderBy('orden');
    }
}
