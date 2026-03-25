<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $table = 'blog_posts';

    protected $fillable = [
        'titulo', 'contenido', 'imagen', 'tipo',
        'autor', 'empresa_id', 'usuario_id',
        'publicado', 'fecha_publicacion', 'slug',
    ];

    protected $casts = [
        'publicado'          => 'boolean',
        'fecha_publicacion'  => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->titulo) . '-' . uniqid();
            }
            if (empty($post->fecha_publicacion)) {
                $post->fecha_publicacion = now();
            }
        });
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getAutorNombreAttribute(): string
    {
        return $this->autor
            ?? $this->empresa?->nombre
            ?? $this->usuario?->name
            ?? 'FlowZone';
    }
}
