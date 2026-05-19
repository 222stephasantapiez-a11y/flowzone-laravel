<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\PlanTuristico;
use App\Models\Empresa;
use App\Models\Evento;
use App\Models\Gastronomia;
use App\Models\Hotel;
use App\Models\Habitacion;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlanTuristicoController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->firstOrFail();
    }

    // ── Vista principal del generador ──────────────────────────
    public function index()
    {
        $empresa = $this->empresa();
        $planes  = PlanTuristico::where('empresa_id', $empresa->id)
            ->with(['evento','gastronomia','hotel','lugar','habitacion'])
            ->latest()->get();

        // Datos según tipo de empresa
        $hoteles     = $empresa->hoteles()->get();
        $habitaciones = Habitacion::whereIn('hotel_id', $hoteles->pluck('id'))->where('disponible', true)->get();
        $platos      = Gastronomia::where('empresa_id', $empresa->id)->where('disponible_hoy', true)->get();
        $lugares     = Lugar::latest()->take(20)->get();
        $eventos     = Evento::where('fecha', '>=', now())->orderBy('fecha')->take(20)->get();

        return view('empresa.planes', compact(
            'empresa', 'planes', 'hoteles', 'habitaciones', 'platos', 'lugares', 'eventos'
        ));
    }

    // ── Genera plan aleatorio según tipo de empresa (JSON) ─────
    public function generar(Request $request)
    {
        $empresa = $this->empresa();
        $tipo    = $empresa->tipo_empresa ?? 'otro';

        $lat    = $request->filled('ubicacion_lat') ? (float)$request->ubicacion_lat : null;
        $lng    = $request->filled('ubicacion_lng') ? (float)$request->ubicacion_lng : null;
        $radio  = min((float)($request->radio_km ?? 50), 200);
        $fechaI = $request->fecha_inicio;
        $fechaF = $request->fecha_fin;
        $horaI  = $request->hora_inicio;
        $horaF  = $request->hora_fin;

        // ── Gastronomía ──────────────────────────────────────
        $qGastro = Gastronomia::where('empresa_id', $empresa->id);
        if ($horaI || $horaF) {
            $qGastro->where(function ($q) use ($horaI, $horaF) {
                $q->whereNull('hora_inicio')->orWhere('hora_inicio', '<=', $horaF ?? '23:59');
            })->where(function ($q) use ($horaI) {
                $q->whereNull('hora_fin')->orWhere('hora_fin', '>=', $horaI ?? '00:00');
            });
        }
        if ($lat && $lng) {
            $qGastro = $this->queryPorProximidad($qGastro, $lat, $lng, $radio);
        }
        $gastronomia = $qGastro->inRandomOrder()->first()
                    ?? Gastronomia::inRandomOrder()->first();

        // ── Lugar ────────────────────────────────────────────
        $qLugar = Lugar::query();
        if ($lat && $lng) {
            $qLugar = $this->queryPorProximidad($qLugar, $lat, $lng, $radio);
        }
        $lugar = $qLugar->inRandomOrder()->first() ?? Lugar::inRandomOrder()->first();

        // ── Evento ───────────────────────────────────────────
        $qEvento = Evento::where('fecha', '>=', now());
        if ($fechaI) $qEvento->where('fecha', '>=', $fechaI);
        if ($fechaF) $qEvento->where('fecha', '<=', $fechaF);
        $evento = $qEvento->inRandomOrder()->first() ?? Evento::inRandomOrder()->first();

        $resultado = [
            'tipo_empresa' => $tipo,
            'lugar'        => $lugar ? ['id' => $lugar->id, 'nombre' => $lugar->nombre, 'precio' => (float)($lugar->precio_entrada ?? 0)] : null,
            'evento'       => $evento ? ['id' => $evento->id, 'nombre' => $evento->nombre, 'precio' => (float)($evento->precio ?? 0)] : null,
            'gastronomia'  => $gastronomia ? ['id' => $gastronomia->id, 'nombre' => $gastronomia->nombre, 'precio' => (float)($gastronomia->precio_promedio ?? 0)] : null,
            'hotel'        => null,
            'habitacion'   => null,
        ];

        // ── Lógica específica por tipo ────────────────────────
        if ($tipo === 'hotel') {
            $qHotel = $empresa->hoteles();
            if ($fechaI || $fechaF) {
                $qHotel->where('disponibilidad', true);
            }
            if ($lat && $lng) {
                $qHotel = $this->queryPorProximidad($qHotel, $lat, $lng, $radio);
            }
            $hoteles = $qHotel->get();
            if ($hoteles->isEmpty()) {
                return response()->json(['error' => 'No se encontraron hoteles disponibles para las fechas y ubicación indicadas.'], 422);
            }
            $hotel      = $hoteles->random();
            $habitacion = Habitacion::where('hotel_id', $hotel->id)->where('disponible', true)->inRandomOrder()->first();
            $resultado['hotel']      = ['id' => $hotel->id, 'nombre' => $hotel->nombre, 'precio' => (float)($hotel->precio ?? 0)];
            $resultado['habitacion'] = $habitacion ? ['id' => $habitacion->id, 'nombre' => $habitacion->nombre, 'precio' => (float)($habitacion->precio_noche ?? 0)] : null;

        } elseif ($tipo === 'restaurante') {
            $qPlatos = Gastronomia::where('empresa_id', $empresa->id);
            if ($horaI || $horaF) {
                $qPlatos->where(function ($q) use ($horaI, $horaF) {
                    $q->whereNull('hora_inicio')->orWhere('hora_inicio', '<=', $horaF ?? '23:59');
                })->where(function ($q) use ($horaI) {
                    $q->whereNull('hora_fin')->orWhere('hora_fin', '>=', $horaI ?? '00:00');
                });
            }
            $platos = $qPlatos->inRandomOrder()->take(3)->get();
            $resultado['platos_extra'] = $platos->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre, 'precio' => (float)($p->precio_promedio ?? 0)])->values()->toArray();

        } elseif (in_array($tipo, ['agencia_turismo', 'transporte'])) {
            $qHotel = Hotel::where('disponibilidad', true);
            if ($lat && $lng) {
                $qHotel = $this->queryPorProximidad($qHotel, $lat, $lng, $radio);
            }
            $hotel = $qHotel->inRandomOrder()->first();
            $resultado['hotel'] = $hotel ? ['id' => $hotel->id, 'nombre' => $hotel->nombre, 'precio' => (float)($hotel->precio ?? 0)] : null;
        }

        // ── Calcular precios ──────────────────────────────────
        $subtotal = 0;
        if ($resultado['habitacion']) $subtotal += $resultado['habitacion']['precio'];
        elseif ($resultado['hotel'])  $subtotal += $resultado['hotel']['precio'];
        if ($resultado['gastronomia']) $subtotal += $resultado['gastronomia']['precio'];
        if ($resultado['evento'])      $subtotal += $resultado['evento']['precio'];
        if ($resultado['lugar'])       $subtotal += $resultado['lugar']['precio'];
        if (!empty($resultado['platos_extra'])) {
            foreach ($resultado['platos_extra'] as $p) $subtotal += $p['precio'];
        }

        $descuento   = round($subtotal * 0.20, 2);
        $precioFinal = round($subtotal - $descuento, 2);

        if (!$gastronomia && !$resultado['hotel'] && !$lugar) {
            return response()->json(['error' => 'No hay suficientes datos para generar un plan.'], 422);
        }

        return response()->json([
            ...$resultado,
            'subtotal'         => (float)$subtotal,
            'descuento'        => (float)$descuento,
            'precioFinal'      => (float)$precioFinal,
            'filtros_aplicados'=> array_filter([
                'fechas'    => ($fechaI || $fechaF) ? "$fechaI → $fechaF" : null,
                'horario'   => ($horaI || $horaF) ? "$horaI → $horaF" : null,
                'ubicacion' => ($lat && $lng) ? "lat:$lat lng:$lng radio:{$radio}km" : null,
            ]),
        ]);
    }

    // ── Haversine proximity filter ─────────────────────────────
    private function queryPorProximidad($query, float $lat, float $lng, float $radioKm, string $latCol = 'latitud', string $lngCol = 'longitud')
    {
        return $query->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians({$latCol})) * cos(radians({$lngCol}) - radians(?)) + sin(radians(?)) * sin(radians({$latCol})))) AS distancia_km",
                [$lat, $lng, $lat])
            ->whereNotNull($latCol)->whereNotNull($lngCol)
            ->having('distancia_km', '<=', $radioKm)
            ->orderBy('distancia_km');
    }

    // ── Guarda el plan ─────────────────────────────────────────
    public function guardar(Request $request)
    {
        $request->validate([
            'titulo'       => 'nullable|string|max:200',
            'descripcion'  => 'nullable|string|max:1000',
            'subtotal'     => 'required|numeric',
            'descuento'    => 'required|numeric',
            'precio_final' => 'required|numeric',
            'publicado'    => 'nullable|boolean',
            'imagen_file'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'imagen_url'   => 'nullable|url',
        ]);

        $empresa = $this->empresa();

        // Imagen
        $imagen = null;
        if ($request->hasFile('imagen_file')) {
            $imagen = $request->file('imagen_file')->store('planes', 'public');
        } elseif ($request->filled('imagen_url')) {
            $imagen = $request->imagen_url;
        }

        PlanTuristico::create([
            'empresa_id'     => $empresa->id,
            'titulo'         => $request->titulo ?: 'Plan ' . now()->format('d/m/Y H:i'),
            'tipo_plan'      => $empresa->tipo_empresa,
            'descripcion'    => $request->descripcion,
            'evento_id'      => $request->evento_id ?: null,
            'gastronomia_id' => $request->gastronomia_id ?: null,
            'hotel_id'       => $request->hotel_id ?: null,
            'lugar_id'       => $request->lugar_id ?: null,
            'habitacion_id'  => $request->habitacion_id ?: null,
            'subtotal'       => $request->subtotal,
            'descuento'      => $request->descuento,
            'precio_final'   => $request->precio_final,
            'publicado'      => $request->boolean('publicado', false),
            'imagen'         => $imagen,
        ]);

        return back()->with('success', '¡Plan guardado! ' . ($request->boolean('publicado') ? 'Ya es visible en el sitio público.' : 'Guardado como borrador.'));
    }

    // ── Toggle publicado ───────────────────────────────────────
    public function togglePublicado(PlanTuristico $plan)
    {
        $empresa = $this->empresa();
        abort_if($plan->empresa_id !== $empresa->id, 403);
        $plan->update(['publicado' => !$plan->publicado]);
        return back()->with('success', $plan->publicado ? 'Plan publicado en el sitio.' : 'Plan ocultado del sitio.');
    }

    // ── Elimina un plan ────────────────────────────────────────
    public function destroy(PlanTuristico $plan)
    {
        $empresa = $this->empresa();
        abort_if($plan->empresa_id !== $empresa->id, 403);
        if ($plan->imagen && !str_starts_with($plan->imagen, 'http')) {
            Storage::disk('public')->delete($plan->imagen);
        }
        $plan->delete();
        return back()->with('success', 'Plan eliminado.');
    }

    // ── Compatibilidad: generar desde gastronomia (legacy) ─────
    // Mantiene la ruta empresa.gastronomia.planes.generar funcionando
}
