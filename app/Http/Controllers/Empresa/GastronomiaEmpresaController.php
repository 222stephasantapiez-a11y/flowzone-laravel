<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Gastronomia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastronomiaEmpresaController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->where('aprobado', true)->firstOrFail();
    }

    private function handleImage(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('imagen_file')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            return $request->file('imagen_file')->store('uploads/gastronomia', 'public');
        }
        if ($request->filled('imagen_url')) {
            if ($current && !str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            return $request->imagen_url;
        }
        return $current;
    }

    public function index(Request $request)
    {
        $empresa  = $this->empresa();
        $busqueda = $request->get('busqueda', '');

        $query = Gastronomia::where('empresa_id', $empresa->id)->latest();
        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('tipo', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        $items = $query->get();
        return view('empresa.gastronomia', compact('empresa', 'items', 'busqueda'));
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'imagen_url'  => 'nullable|url',
            'imagen_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        Gastronomia::create([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'tipo'           => $request->tipo,
            'precio_promedio'=> $request->precio_promedio ?: null,
            'restaurante'    => $empresa->nombre,
            'direccion'      => $request->direccion,
            'ubicacion'      => $request->ubicacion,
            'telefono'       => $request->telefono,
            'ingredientes'   => $request->ingredientes,
            'empresa_id'     => $empresa->id,
            'imagen'         => $this->handleImage($request),
            'latitud'        => $request->filled('latitud') ? $request->latitud : null,
            'longitud'       => $request->filled('longitud') ? $request->longitud : null,
        ]);

        return redirect()->route('empresa.gastronomia.index')
                         ->with('success', 'Plato/servicio creado correctamente.');
    }

    public function edit(Gastronomia $gastronomium)
    {
        $empresa = $this->empresa();
        abort_if($gastronomium->empresa_id !== $empresa->id, 403);
        $items = Gastronomia::where('empresa_id', $empresa->id)->latest()->get();
        return view('empresa.gastronomia', compact('empresa', 'items', 'gastronomium'));
    }

    public function update(Request $request, Gastronomia $gastronomium)
    {
        $empresa = $this->empresa();
        abort_if($gastronomium->empresa_id !== $empresa->id, 403);

        $request->validate([
            'nombre'      => 'required|string|max:150',
            'imagen_url'  => 'nullable|url',
            'imagen_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $gastronomium->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'tipo'           => $request->tipo,
            'precio_promedio'=> $request->precio_promedio ?: null,
            'direccion'      => $request->direccion,
            'ubicacion'      => $request->ubicacion,
            'telefono'       => $request->telefono,
            'ingredientes'   => $request->ingredientes,
            'imagen'         => $this->handleImage($request, $gastronomium->imagen),
            'latitud'        => $request->filled('latitud') ? $request->latitud : null,
            'longitud'       => $request->filled('longitud') ? $request->longitud : null,
        ]);

        return redirect()->route('empresa.gastronomia.index')
                         ->with('success', 'Actualizado correctamente.');
    }

    public function destroy(Gastronomia $gastronomium)
    {
        $empresa = $this->empresa();
        abort_if($gastronomium->empresa_id !== $empresa->id, 403);
        if ($gastronomium->imagen && !str_starts_with($gastronomium->imagen, 'http')) {
            Storage::disk('public')->delete($gastronomium->imagen);
        }
        $gastronomium->delete();
        return redirect()->route('empresa.gastronomia.index')->with('success', 'Eliminado.');
    }
}
