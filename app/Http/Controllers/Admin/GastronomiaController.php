<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\HandlesImport;
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
    use HandlesImport;
    // ==========================
    // LISTAR + FILTROS + PAGINACIÓN
    // ==========================
    public function index(Request $request)
    {
        $query = Gastronomia::with('empresa');

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('restaurante')) {
            $query->where('restaurante', 'like', '%' . $request->restaurante . '%');
        }

        if ($request->filled('precio')) {
            $query->where('precio_promedio', '<=', $request->precio);
        }

        if ($request->filled('empresa')) {
            $query->where('empresa_id', $request->empresa);
        }

        $perPage = (int) $request->get('per_page', 10);

        // Ordenamiento
        $sort      = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'nombre', 'tipo', 'restaurante', 'precio_promedio'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        $direction = $direction === 'desc' ? 'desc' : 'asc';

        $gastronomias = $query->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $empresas = Empresa::where('aprobado', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.gastronomia', compact('gastronomias', 'empresas', 'perPage', 'sort', 'direction'));
    }

    // ==========================
    // MANEJO DE IMAGEN
    // ==========================
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

    // ==========================
    // CREAR
    // ==========================
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        Gastronomia::create([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'tipo'            => $request->tipo,
            'precio_promedio' => $request->precio_promedio ?: null,
            'restaurante'     => $request->restaurante,
            'direccion'       => $request->direccion,
            'ubicacion'       => $request->ubicacion,
            'telefono'        => $request->telefono,
            'ingredientes'    => $request->ingredientes,
            'empresa_id'      => $request->empresa_id ?: null,
            'imagen'          => $this->handleImage($request),
            'latitud'         => $request->filled('latitud') ? $request->latitud : null,
            'longitud'        => $request->filled('longitud') ? $request->longitud : null,
        ]);

        return redirect()->route('admin.gastronomia.index')
            ->with('success', 'Creado correctamente');
    }

    // ==========================
    // EDITAR
    // ==========================
    public function edit(Gastronomia $gastronomium)
    {
        $perPage = 10;

        $gastronomias = Gastronomia::with('empresa')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $empresas = Empresa::where('aprobado', true)
            ->orderBy('nombre')
            ->get();

        return view('admin.gastronomia', compact('gastronomias', 'empresas', 'gastronomium', 'perPage'));
    }

    // ==========================
    // ACTUALIZAR
    // ==========================
    public function update(Request $request, Gastronomia $gastronomium)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
        ]);

        $gastronomium->update([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'tipo'            => $request->tipo,
            'precio_promedio' => $request->precio_promedio ?: null,
            'restaurante'     => $request->restaurante,
            'direccion'       => $request->direccion,
            'ubicacion'       => $request->ubicacion,
            'telefono'        => $request->telefono,
            'ingredientes'    => $request->ingredientes,
            'empresa_id'      => $request->empresa_id ?: null,
            'imagen'          => $this->handleImage($request, $gastronomium->imagen),
            'latitud'         => $request->filled('latitud') ? $request->latitud : null,
            'longitud'        => $request->filled('longitud') ? $request->longitud : null,
        ]);

        return redirect()->route('admin.gastronomia.index')
            ->with('success', 'Actualizado correctamente');
    }

    // ==========================
    // ELIMINAR
    // ==========================
    public function destroy(Gastronomia $gastronomium)
    {
        if ($gastronomium->imagen && !str_starts_with($gastronomium->imagen, 'http')) {
            Storage::disk('public')->delete($gastronomium->imagen);
        }

        $gastronomium->delete();

        return redirect()->route('admin.gastronomia.index')
            ->with('success', 'Eliminado correctamente');
    }

    // ==========================
    // EXPORTAR EXCEL
    // ==========================
    public function exportExcel()
    {
        return Excel::download(new GastronomiaExport, 'gastronomia.xlsx');
    }

    // ==========================
    // IMPORTAR EXCEL
    // ==========================
    public function importExcel(Request $request)
    {
        return $this->runImport($request, new GastronomiaImport, 'admin.gastronomia.index');
    }

    // ==========================
    // EXPORTAR PDF
    // ==========================
    public function exportPdf()
    {
        $gastronomias = Gastronomia::all();

        $pdf = Pdf::loadView('admin.pdf.gastronomia', compact('gastronomias'));

        return $pdf->download('gastronomia.pdf');
    }
}