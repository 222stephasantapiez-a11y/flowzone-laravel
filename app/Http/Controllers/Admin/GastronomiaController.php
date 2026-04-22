<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gastronomia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GastronomiaExport;
use App\Imports\GastronomiaImport;

class GastronomiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Gastronomia::query();

        if ($request->nombre) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->restaurante) {
            $query->where('restaurante', 'like', '%' . $request->restaurante . '%');
        }

        if ($request->precio) {
            $query->where('precio_promedio', '<=', $request->precio);
        }

        if ($request->empresa) {
            $query->where('empresa_id', $request->empresa);
        }

        $gastronomias = $query->latest()->paginate(10)->withQueryString();

        $empresas = Empresa::where('aprobado', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.gastronomia', compact('gastronomias', 'empresas'));
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        Gastronomia::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'precio_promedio' => $request->precio_promedio,
            'restaurante' => $request->restaurante,
            'direccion' => $request->direccion,
            'ubicacion' => $request->ubicacion,
            'telefono' => $request->telefono,
            'ingredientes' => $request->ingredientes,
            'empresa_id' => $request->empresa_id,
            'imagen' => $this->handleImage($request),
        ]);

        return redirect()->route('admin.gastronomia.index')
            ->with('success', 'Creado correctamente');
    }

    public function edit(Gastronomia $gastronomium)
    {
        $gastronomias = Gastronomia::with('empresa')->latest()->paginate(10);
        $empresas = Empresa::where('aprobado', true)->orderBy('nombre')->get();

        return view('admin.gastronomia', compact('gastronomias', 'empresas', 'gastronomium'));
    }

    public function update(Request $request, Gastronomia $gastronomium)
    {
        $gastronomium->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'precio_promedio' => $request->precio_promedio,
            'restaurante' => $request->restaurante,
            'direccion' => $request->direccion,
            'ubicacion' => $request->ubicacion,
            'telefono' => $request->telefono,
            'ingredientes' => $request->ingredientes,
            'empresa_id' => $request->empresa_id,
            'imagen' => $this->handleImage($request, $gastronomium->imagen),
        ]);

        return redirect()->route('admin.gastronomia.index')
            ->with('success', 'Actualizado correctamente');
    }

    public function destroy(Gastronomia $gastronomium)
    {
        if ($gastronomium->imagen && !str_starts_with($gastronomium->imagen, 'http')) {
            Storage::disk('public')->delete($gastronomium->imagen);
        }

        $gastronomium->delete();

        return redirect()->route('admin.gastronomia.index')
            ->with('success', 'Eliminado correctamente');
    }

    public function exportExcel()
    {
        return Excel::download(new GastronomiaExport, 'gastronomia.xlsx');
    }

    public function exportPdf()
    {
        $gastronomias = Gastronomia::all();

        $pdf = Pdf::loadView('admin.pdf.gastronomia', compact('gastronomias'));

        return $pdf->download('gastronomia.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new GastronomiaImport, $request->file('archivo'));

        return back()->with('success', 'Importado correctamente');
    }
}