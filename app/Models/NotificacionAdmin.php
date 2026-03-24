<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionAdmin extends Model
{
    protected $table = 'notificaciones_admin';

    protected $fillable = ['empresa_id', 'mensaje', 'leido'];

    protected $casts = ['leido' => 'boolean'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
