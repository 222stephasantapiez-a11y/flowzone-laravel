<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\PaqueteTuristico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaqueteController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->firstOrFail();
    }

    private function handleImage(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('imagen_file')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            return $request->file('imagen_file')->store('paquetes', 'public');
        }
        if ($request->filled('imagen_url')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            return $request->imagen_url;
        }
        return $current;
    }

    private function rules(): array
    {
        return [
            'nombre'             => 'required|string|max:200',
            'descripcion'        => 'required|string',
            'duracion_dias'      => 'required|integer|min:1',
            'duracion_horas'     => 'nullable|integer|min:0',
            'precio_adulto'      => 'required|numeric|min:0',
            'precio_nino'        => 'nullable|numeric|min:0',
            'cupo_maximo'        => 'required|integer|min:1',
            'cupo_minimo'        => 'required|integer|min:1',
            'cupo_disponible'    => 'required|integer|min:0',
            'punto_salida'       => 'nullable|string|max:300',
            'hora_salida'        => 'nullable|date_format:H:i',
            'dificultad'         => 'nullable|in:facil,moderado,dificil',
            'itinerario'         => 'nullable|string',
            'incluye'            => 'nullable|array',
            'que_llevar'         => 'nullable|array',
            'imagen_file'        => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'imagen_url'         => 'nullable|url',
        ];
    }

    public function index()
    {
        $empresa  = $this->empresa();
        $paquetes = PaqueteTuristico::where('empresa_id', $empresa->id)->latest()->get();
        return view('empresa.paquetes', compact('empresa', 'paquetes'));
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();
        $request->validate($this->rules());

        // Decodificar JSON de ruta, no_incluye y fechas_disponibles enviados como hidden
        $ruta              = $this->decodeJson($request->ruta_json);
        $noIncluye         = $this->decodeJson($request->no_incluye_json);
        $fechasDisponibles = $this->decodeJson($request->fechas_disponibles_json);

        PaqueteTuristico::create([
            'empresa_id'         => $empresa->id,
            'nombre'             => $request->nombre,
            'descripcion'        => $request->descripcion,
            'itinerario'         => $request->itinerario,
            'ruta'               => $ruta,
            'incluye'            => $request->incluye ?? [],
            'no_incluye'         => $noIncluye,
            'duracion_dias'      => $request->duracion_dias,
            'duracion_horas'     => $request->duracion_horas,
            'cupo_maximo'        => $request->cupo_maximo,
            'cupo_minimo'        => $request->cupo_minimo,
            'cupo_disponible'    => $request->cupo_disponible,
            'precio_adulto'      => $request->precio_adulto,
            'precio_nino'        => $request->precio_nino,
            'punto_salida'       => $request->punto_salida,
            'hora_salida'        => $request->hora_salida,
            'fechas_disponibles' => $fechasDisponibles,
            'activo'             => $request->boolean('activo', true),
            'imagen'             => $this->handleImage($request),
            'dificultad'         => $request->dificultad,
            'que_llevar'         => $request->que_llevar ?? [],
        ]);

        return redirect()->route('empresa.paquetes.index')->with('success', 'Paquete creado correctamente.');
    }

    public function update(Request $request, PaqueteTuristico $paquete)
    {
        $empresa = $this->empresa();
        abort_if($paquete->empresa_id !== $empresa->id, 403);
        $request->validate($this->rules());

        $ruta              = $this->decodeJson($request->ruta_json);
        $noIncluye         = $this->decodeJson($request->no_incluye_json);
        $fechasDisponibles = $this->decodeJson($request->fechas_disponibles_json);

        $paquete->update([
            'nombre'             => $request->nombre,
            'descripcion'        => $request->descripcion,
            'itinerario'         => $request->itinerario,
            'ruta'               => $ruta,
            'incluye'            => $request->incluye ?? [],
            'no_incluye'         => $noIncluye,
            'duracion_dias'      => $request->duracion_dias,
            'duracion_horas'     => $request->duracion_horas,
            'cupo_maximo'        => $request->cupo_maximo,
            'cupo_minimo'        => $request->cupo_minimo,
            'cupo_disponible'    => $request->cupo_disponible,
            'precio_adulto'      => $request->precio_adulto,
            'precio_nino'        => $request->precio_nino,
            'punto_salida'       => $request->punto_salida,
            'hora_salida'        => $request->hora_salida,
            'fechas_disponibles' => $fechasDisponibles,
            'activo'             => $request->boolean('activo', true),
            'imagen'             => $this->handleImage($request, $paquete->imagen),
            'dificultad'         => $request->dificultad,
            'que_llevar'         => $request->que_llevar ?? [],
        ]);

        return redirect()->route('empresa.paquetes.index')->with('success', 'Paquete actualizado.');
    }

    public function destroy(PaqueteTuristico $paquete)
    {
        $empresa = $this->empresa();
        abort_if($paquete->empresa_id !== $empresa->id, 403);
        if ($paquete->imagen && !str_starts_with($paquete->imagen, 'http')) {
            Storage::disk('public')->delete($paquete->imagen);
        }
        $paquete->delete();
        return redirect()->route('empresa.paquetes.index')->with('success', 'Paquete eliminado.');
    }

    public function toggleActivo(PaqueteTuristico $paquete)
    {
        $empresa = $this->empresa();
        abort_if($paquete->empresa_id !== $empresa->id, 403);
        $paquete->update(['activo' => !$paquete->activo]);
        return back()->with('success', 'Estado del paquete actualizado.');
    }

    public function ajustarCupo(Request $request, PaqueteTuristico $paquete)
    {
        $empresa = $this->empresa();
        abort_if($paquete->empresa_id !== $empresa->id, 403);
        $request->validate(['cupo_disponible' => 'required|integer|min:0']);
        $paquete->update(['cupo_disponible' => $request->cupo_disponible]);
        return back()->with('success', 'Cupo actualizado.');
    }

    private function decodeJson(?string $json): array
    {
        if (!$json) return [];
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
