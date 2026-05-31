<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'usuario_id', 'nombre', 'telefono', 'direccion', 'aprobado',
        'tipo_empresa', 'servicios', 'descripcion', 'logo', 'nit',
        'sitio_web', 'instagram', 'facebook',
    ];

    protected $casts = [
        'aprobado'  => 'boolean',
        'servicios' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(NotificacionAdmin::class, 'empresa_id');
    }

    public function hoteles()
    {
        return $this->hasMany(\App\Models\Hotel::class, 'empresa_id');
    }

    public function gastronomias()
    {
        return $this->hasMany(\App\Models\Gastronomia::class, 'empresa_id');
    }

    public function imagenes()
    {
        return $this->hasMany(EmpresaImagen::class)->orderBy('orden');
    }

    public function imagenesActivas()
    {
        return $this->hasMany(EmpresaImagen::class)->where('activa', true)->orderBy('orden');
    }

    public function heroImagenes()
    {
        return $this->hasMany(HeroImage::class)->where('seccion', 'hero')->where('activa', true)->orderBy('orden');
    }

    public function getLogoUrlAttribute(): string
    {
        if (!$this->logo) {
            return '';
        }
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo);
    }
}
