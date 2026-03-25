<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    /**
     * Toggle favorito vía AJAX o form POST.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'tipo'    => 'required|in:lugar,hotel',
            'item_id' => 'required|integer|min:1',
        ]);

        $agregado = Favorito::toggle(
            auth()->id(),
            $request->tipo,
            $request->item_id
        );

        if ($request->expectsJson()) {
            return response()->json([
                'agregado' => $agregado,
                'mensaje'  => $agregado ? 'Agregado a favoritos' : 'Eliminado de favoritos',
            ]);
        }

        return back()->with('success', $agregado ? 'Agregado a favoritos.' : 'Eliminado de favoritos.');
    }
}
