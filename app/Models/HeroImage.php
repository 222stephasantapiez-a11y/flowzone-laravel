<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroImage extends Model
{
    protected $fillable = ['titulo', 'url', 'seccion', 'activa', 'orden', 'tipo'];

    protected $casts = ['activa' => 'boolean'];

    /** URL pública de la imagen */
    public function getPublicUrlAttribute(): string
    {
        if ($this->tipo === 'upload') {
            return Storage::disk('public')->url($this->url);
        }
        return $this->url;
    }

    /** Scope: solo activas ordenadas */
    public function scopeActivas($query)
    {
        return $query->where('activa', true)->orderBy('orden');
    }

    /** Scope por sección */
    public function scopeSeccion($query, string $seccion)
    {
        return $query->where('seccion', $seccion);
    }
}
