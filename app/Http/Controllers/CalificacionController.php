<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    /**
     * Guardar o actualizar calificación + comentario.
     * Tipos válidos: lugar, hotel, gastronomia, empresa
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo'         => 'required|in:lugar,hotel,gastronomia,empresa',
            'item_id'      => 'required|integer|min:1',
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario'   => 'nullable|string|max:1000',
        ]);

        Calificacion::updateOrCreate(
            [
                'usuario_id' => auth()->id(),
                'tipo'       => $request->tipo,
                'item_id'    => $request->item_id,
            ],
            [
                'calificacion' => $request->calificacion,
                'comentario'   => $request->comentario,
            ]
        );

        return back()->with('success', '¡Gracias por tu calificación!');
    }
}
