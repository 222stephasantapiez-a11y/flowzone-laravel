<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    protected $fillable = [
        'usuario_id', 'tipo', 'item_id', 'calificacion', 'comentario',
    ];

    protected $casts = [
        'calificacion' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Obtiene el promedio y total de calificaciones para un item.
     * Tipos válidos: lugar, hotel, gastronomia, empresa
     */
    public static function stats(string $tipo, int $itemId): array
    {
        $query = static::where('tipo', $tipo)->where('item_id', $itemId);
        return [
            'promedio' => round($query->avg('calificacion') ?? 0, 1),
            'total'    => $query->count(),
        ];
    }
}
