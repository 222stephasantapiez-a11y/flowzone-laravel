<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\PlanTuristico;
use App\Models\Empresa;
use App\Models\Evento;
use App\Models\Gastronomia;
use App\Models\Hotel;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanTuristicoController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->firstOrFail();
    }

    // Genera un plan aleatorio y lo devuelve como JSON (sin guardar aún)
    public function generar(Request $request)
    {
        $empresa = $this->empresa();

        $evento      = Evento::inRandomOrder()->first();
        $gastronomia = Gastronomia::where('empresa_id', $empresa->id)->inRandomOrder()->first()
                    ?? Gastronomia::inRandomOrder()->first();
        $hotel       = Hotel::inRandomOrder()->first();
        $lugar       = Lugar::inRandomOrder()->first();

        if (!$evento || !$gastronomia || !$hotel || !$lugar) {
            return response()->json(['error' => 'No hay suficientes datos para generar un plan.'], 422);
        }

        $subtotal    = ($evento->precio ?? 0) + ($gastronomia->precio_promedio ?? 0) + ($hotel->precio ?? 0) + ($lugar->precio_entrada ?? 0);
        $descuento   = $subtotal * 0.20;
        $precioFinal = $subtotal - $descuento;

        return response()->json([
            'evento'       => ['id' => $evento->id,      'nombre' => $evento->nombre],
            'gastronomia'  => ['id' => $gastronomia->id, 'nombre' => $gastronomia->nombre],
            'hotel'        => ['id' => $hotel->id,       'nombre' => $hotel->nombre],
            'lugar'        => ['id' => $lugar->id,       'nombre' => $lugar->nombre],
            'subtotal'     => $subtotal,
            'descuento'    => $descuento,
            'precioFinal'  => $precioFinal,
        ]);
    }

    // Guarda el plan en la BD
    public function guardar(Request $request)
    {
        $request->validate([
            'evento_id'      => 'required|exists:eventos,id',
            'gastronomia_id' => 'required|exists:gastronomia,id',
            'hotel_id'       => 'required|exists:hoteles,id',
            'lugar_id'       => 'required|exists:lugares,id',
            'subtotal'       => 'required|numeric',
            'descuento'      => 'required|numeric',
            'precio_final'   => 'required|numeric',
        ]);

        $empresa = $this->empresa();

        PlanTuristico::create([
            'empresa_id'     => $empresa->id,
            'titulo'         => 'Plan ' . now()->format('d/m/Y H:i'),
            'evento_id'      => $request->evento_id,
            'gastronomia_id' => $request->gastronomia_id,
            'hotel_id'       => $request->hotel_id,
            'lugar_id'       => $request->lugar_id,
            'subtotal'       => $request->subtotal,
            'descuento'      => $request->descuento,
            'precio_final'   => $request->precio_final,
        ]);

        return back()->with('success', '¡Plan guardado! Ya aparece en la sección de planes sugeridos.');
    }

    // Elimina un plan guardado
    public function destroy(PlanTuristico $plan)
    {
        $empresa = $this->empresa();
        abort_if($plan->empresa_id !== $empresa->id, 403);
        $plan->delete();
        return back()->with('success', 'Plan eliminado.');
    }
}