<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Gastronomia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GastronomiaEmpresaController extends Controller
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

    public function index(\Illuminate\Http\Request $request)
    {
        $empresa = $this->empresa();

        $query = Gastronomia::where('empresa_id', $empresa->id);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('precio_min')) {
            $query->where('precio_promedio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio_promedio', '<=', $request->precio_max);
        }

        $items   = $query->latest()->get();
        $filtros = $request->only(['nombre', 'tipo', 'precio_min', 'precio_max']);
        $hayFiltros = collect($filtros)->filter()->isNotEmpty();

        return view('empresa.gastronomia', compact('empresa', 'items', 'filtros', 'hayFiltros'));
    }

    public function store(Request $request)
    {
        $empresa = $this->empresa();
        $request->validate([
            'nombre'         => 'required|string|max:150',
            'imagen_url'     => 'nullable|url',
            'imagen_file'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'hora_inicio'    => 'nullable|date_format:H:i',
            'hora_fin'       => 'nullable|date_format:H:i',
            'stock_diario'   => 'nullable|integer|min:0',
            'stock_actual'   => 'nullable|integer|min:0',
            'dias_semana'    => 'nullable|array',
            'dias_semana.*'  => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
        ]);

        Gastronomia::create([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'tipo'            => $request->tipo,
            'precio_promedio' => $request->precio_promedio ?: null,
            'restaurante'     => $empresa->nombre,
            'direccion'       => $request->direccion,
            'ubicacion'       => $request->ubicacion,
            'telefono'        => $request->telefono,
            'ingredientes'    => $request->ingredientes,
            'empresa_id'      => $empresa->id,
            'imagen'          => $this->handleImage($request),
            'disponible_hoy'  => $request->boolean('disponible_hoy', true),
            'hora_inicio'     => $request->hora_inicio,
            'hora_fin'        => $request->hora_fin,
            'stock_diario'    => $request->stock_diario ?: null,
            'stock_actual'    => $request->stock_actual ?: null,
            'dias_semana'     => $request->dias_semana ?? [],
        ]);

        return redirect()->route('empresa.gastronomia.index')
                         ->with('success', 'Plato/servicio creado correctamente.');
    }

    public function edit(Gastronomia $gastronomium)
    {
        $empresa = $this->empresa();
        abort_if($gastronomium->empresa_id !== $empresa->id, 403);
        $items = Gastronomia::where('empresa_id', $empresa->id)->latest()->get();
        $filtros = []; $hayFiltros = false; $planes = \App\Models\PlanTuristico::where("empresa_id", $empresa->id)->with(["evento","gastronomia","hotel","lugar"])->latest()->get(); return view("empresa.gastronomia", compact("empresa", "items", "gastronomium", "filtros", "hayFiltros", "planes"));
    }

    public function update(Request $request, Gastronomia $gastronomium)
    {
        $empresa = $this->empresa();
        abort_if($gastronomium->empresa_id !== $empresa->id, 403);

        $request->validate([
            'nombre'         => 'required|string|max:150',
            'imagen_url'     => 'nullable|url',
            'imagen_file'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'hora_inicio'    => 'nullable|date_format:H:i',
            'hora_fin'       => 'nullable|date_format:H:i',
            'stock_diario'   => 'nullable|integer|min:0',
            'stock_actual'   => 'nullable|integer|min:0',
            'dias_semana'    => 'nullable|array',
            'dias_semana.*'  => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
        ]);

        $gastronomium->update([
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'tipo'            => $request->tipo,
            'precio_promedio' => $request->precio_promedio ?: null,
            'direccion'       => $request->direccion,
            'ubicacion'       => $request->ubicacion,
            'telefono'        => $request->telefono,
            'ingredientes'    => $request->ingredientes,
            'imagen'          => $this->handleImage($request, $gastronomium->imagen),
            'disponible_hoy'  => $request->boolean('disponible_hoy', true),
            'hora_inicio'     => $request->hora_inicio,
            'hora_fin'        => $request->hora_fin,
            'stock_diario'    => $request->stock_diario ?: null,
            'stock_actual'    => $request->stock_actual ?: null,
            'dias_semana'     => $request->dias_semana ?? [],
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
    // ── Export Excel ──
    public function exportExcel(Request $request)
    {
        $empresa = $this->empresa();
        $items = $this->filteredQuery($empresa, $request)->get();
        return Excel::download(new \App\Exports\GastronomiaEmpresaExport($items), 'platos_registrados.xlsx');
    }

    // ── Export PDF ──
    public function exportPdf(Request $request)
    {
        $empresa = $this->empresa();
        $items = $this->filteredQuery($empresa, $request)->get();
        $pdf = Pdf::loadView('empresa.gastronomia_pdf', compact('items', 'empresa'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('platos_registrados.pdf');
    }

    // ── Import Excel ──
    public function importExcel(Request $request)
    {
        $request->validate(['archivo' => 'required|file|mimes:xlsx,xls,csv,txt']);
        $empresa = $this->empresa();

        try {
            $import = new \App\Imports\GastronomiaEmpresaImport($empresa->id);
            Excel::import($import, $request->file('archivo'));
            $count = \App\Models\Gastronomia::where('empresa_id', $empresa->id)->count();
            return back()->with('success', 'Importación completada. Total registros en BD: ' . $count);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())->map(fn($f) => 'Fila '.$f->row().': '.implode(', ', $f->errors()))->implode(' | ');
            return back()->with('error', 'Error de validación: ' . $failures);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    // ── Helper query con filtros ──
    private function filteredQuery(Empresa $empresa, Request $request)
    {
        $q = Gastronomia::where('empresa_id', $empresa->id);
        if ($request->filled('nombre'))     $q->where('nombre', 'like', '%'.$request->nombre.'%');
        if ($request->filled('tipo'))       $q->where('tipo', $request->tipo);
        if ($request->filled('precio_min')) $q->where('precio_promedio', '>=', $request->precio_min);
        if ($request->filled('precio_max')) $q->where('precio_promedio', '<=', $request->precio_max);
        return $q->latest();
    }

    // ── Toggle disponibilidad individual ──
    public function toggleDisponibilidad(Gastronomia $gastronomium)
    {
        $empresa = $this->empresa();
        abort_if($gastronomium->empresa_id !== $empresa->id, 403);
        $gastronomium->update(['disponible_hoy' => !$gastronomium->disponible_hoy]);
        return back()->with('success', 'Disponibilidad actualizada.');
    }

    // ── Resetear stock diario ──
    public function resetStockDiario()
    {
        $empresa = $this->empresa();
        Gastronomia::where('empresa_id', $empresa->id)
            ->whereNotNull('stock_diario')
            ->update(['stock_actual' => \Illuminate\Support\Facades\DB::raw('stock_diario')]);
        return back()->with('success', 'Stock del día reiniciado correctamente.');
    }
}