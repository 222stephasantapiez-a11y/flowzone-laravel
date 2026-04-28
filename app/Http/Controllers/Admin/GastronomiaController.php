<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GastronomiaExport;
use App\Imports\GastronomiaImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Gastronomia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GastronomiaController extends Controller
{
    private function handleImage(Request $request, ?string $current = null): string
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
        return $current ?? '';
    }

    public function index(Request $request)
    {
        $perPage  = $request->get('per_page', 10);
        $busqueda = $request->get('busqueda', '');

        $query = Gastronomia::with('empresa')->oldest();
        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('restaurante', 'like', "%{$busqueda}%")
                  ->orWhere('ubicacion', 'like', "%{$busqueda}%");
            });
        }

        $items    = $query->paginate($perPage)->withQueryString();
        $empresas = Empresa::where('aprobado', true)->orderBy('nombre')->get();
        return view('admin.gastronomia', compact('items', 'empresas', 'perPage', 'busqueda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'imagen_url'  => 'nullable|url',
            'imagen_file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        Gastronomia::create([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'tipo'           => $request->tipo,
            'precio_promedio'=> $request->precio_promedio ?: null,
            'restaurante'    => $request->restaurante,
            'direccion'      => $request->direccion,
            'ubicacion'      => $request->ubicacion,
            'telefono'       => $request->telefono,
            'ingredientes'   => $request->ingredientes,
            'empresa_id'     => $request->empresa_id ?: null,
            'imagen'         => $this->handleImage($request),
            'latitud'        => $request->filled('latitud') ? $request->latitud : null,
            'longitud'       => $request->filled('longitud') ? $request->longitud : null,
        ]);

        return redirect()->route('admin.gastronomia.index')
                         ->with('success', 'Elemento gastronómico creado.');
    }

    public function edit(Gastronomia $gastronomium)
    {
        $perPage  = 10;
        $items    = Gastronomia::with('empresa')->oldest()->paginate($perPage)->withQueryString();
        $empresas = Empresa::where('aprobado', true)->orderBy('nombre')->get();
        return view('admin.gastronomia', compact('items', 'empresas', 'gastronomium', 'perPage'));
    }

    public function update(Request $request, Gastronomia $gastronomium)
    {
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
            'restaurante'    => $request->restaurante,
            'direccion'      => $request->direccion,
            'ubicacion'      => $request->ubicacion,
            'telefono'       => $request->telefono,
            'ingredientes'   => $request->ingredientes,
            'empresa_id'     => $request->empresa_id ?: null,
            'imagen'         => $this->handleImage($request, $gastronomium->imagen),
            'latitud'        => $request->filled('latitud') ? $request->latitud : null,
            'longitud'       => $request->filled('longitud') ? $request->longitud : null,
        ]);

        return redirect()->route('admin.gastronomia.index')
                         ->with('success', 'Elemento actualizado.');
    }

    public function destroy(Gastronomia $gastronomium)
    {
        if ($gastronomium->imagen && !str_starts_with($gastronomium->imagen, 'http')) {
            Storage::disk('public')->delete($gastronomium->imagen);
        }
        $gastronomium->delete();
        return redirect()->route('admin.gastronomia.index')
                         ->with('success', 'Elemento eliminado.');
    }

    public function exportExcel()
    {
        return Excel::download(new GastronomiaExport, 'gastronomia.xlsx');
    }

    public function exportPdf()
    {
        $gastronomia = \App\Models\Gastronomia::all();
        $pdf = Pdf::loadView('admin.pdf.gastronomia', compact('gastronomia'));
        return $pdf->download('gastronomia.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new GastronomiaImport, $request->file('archivo'));

        return back()->with('success', 'Restaurantes importados correctamente');
    }
}