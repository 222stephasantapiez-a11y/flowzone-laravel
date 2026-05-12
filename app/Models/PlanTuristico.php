<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanTuristico extends Model
{
    protected $table = 'planes_turisticos';

    protected $fillable = [
        'empresa_id', 'titulo',
        'evento_id', 'gastronomia_id', 'hotel_id', 'lugar_id',
        'subtotal', 'descuento', 'precio_final',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function gastronomia()
    {
        return $this->belongsTo(Gastronomia::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }
}