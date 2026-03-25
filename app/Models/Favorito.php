<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';

    protected $fillable = ['usuario_id', 'tipo', 'item_id'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Toggle favorito. Devuelve true si se agregó, false si se eliminó.
     */
    public static function toggle(int $usuarioId, string $tipo, int $itemId): bool
    {
        $existing = static::where([
            'usuario_id' => $usuarioId,
            'tipo'       => $tipo,
            'item_id'    => $itemId,
        ])->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        static::create([
            'usuario_id' => $usuarioId,
            'tipo'       => $tipo,
            'item_id'    => $itemId,
        ]);

        return true;
    }

    /**
     * Verifica si un item es favorito del usuario.
     */
    public static function esFavorito(int $usuarioId, string $tipo, int $itemId): bool
    {
        return static::where([
            'usuario_id' => $usuarioId,
            'tipo'       => $tipo,
            'item_id'    => $itemId,
        ])->exists();
    }
}
