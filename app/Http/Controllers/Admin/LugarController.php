<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LugaresExport;
use App\Imports\LugaresImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\HandlesImport;
use App\Models\Lugar;
use Illuminate\Support\Facades\Storage;

class LugarController extends Controller
{
    use HandlesImport;

    // ✅ INDEX (FILTROS + PAGINACIÓN)
    public function index(Request $request)
    {
        $query = Lugar::query();

        // 🔎 FILTROS
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('ubicacion')) {
            $query->where('ubicacion', 'like', '%' . $request->ubicacion . '%');
        }

        if ($request->filled('precio_entrada')) {
            $query->where('precio_entrada', '<=', $request->precio_entrada);
        }

        // ORDENAMIENTO
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        // Validar columnas permitidas
        $allowedSorts = ['id', 'nombre', 'categoria', 'ubicacion', 'precio_entrada'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        // Validar dirección
        $direction = $direction === 'desc' ? 'desc' : 'asc';

        // 📄 PAGINACIÓN
        $perPage = $request->get('per_page', 10);

        $lugares = $query->orderBy($sort, $direction)
                         ->paginate($perPage)
                         ->withQueryString();

        return view('admin.lugares', compact('lugares', 'perPage', 'sort', 'direction'));
    }

    // 📸 IMAGEN
    private function handleImageUpload(Request $request, ?string $currentImage = null): string
    {
        if ($request->hasFile('imagen_file')) {
            if ($currentImage && !str_starts_with($currentImage, 'http')) {
                Storage::disk('public')->delete($currentImage);
            }
            return $request->file('imagen_file')->store('uploads/lugares', 'public');
        }

        if ($request->filled('imagen_url')) {
            if ($currentImage && !str_starts_with($currentImage, 'http')) {
                Storage::disk('public')->delete($currentImage);
            }
            return $request->imagen_url;
        }

        return $currentImage ?? '';
    }

    // ✅ VALIDACIONES
    private function rules(bool $isUpdate = false): array
    {
        $imgRequired = $isUpdate ? 'nullable' : 'required_without:imagen_file';

        return [
            'nombre'         => 'required|string|max:150',
            'descripcion'    => 'required|string',
            'categoria'      => 'required|string|max:100',
            'imagen_url'     => "$imgRequired|nullable|url",
            'imagen_file'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'ubicacion'      => 'nullable|string|max:200',
            'precio_entrada' => 'nullable|numeric|min:0',
        ];
    }

    // ✅ CREAR
    public function store(Request $request)
    {
        $request->validate($this->rules(), [
            'imagen_url.required_without' => 'Debes ingresar una URL o subir una imagen.',
        ]);

        $imagen = $this->handleImageUpload($request);

        Lugar::create([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'ubicacion'      => $request->ubicacion,
            'latitud'        => $request->latitud ?: null,
            'longitud'       => $request->longitud ?: null,
            'categoria'      => $request->categoria,
            'imagen'         => $imagen,
            'precio_entrada' => $request->precio_entrada ?: 0,
            'horario'        => $request->horario,
        ]);

        return redirect()->route('admin.lugares.index')
                         ->with('success', 'Lugar creado correctamente.');
    }

    // ✏️ EDITAR
    public function edit(Lugar $lugar)
    {
        $perPage = 10;
        $lugares = Lugar::orderBy('id', 'desc')->paginate($perPage);

        return view('admin.lugares', compact('lugares', 'lugar', 'perPage'));
    }

    // 🔄 ACTUALIZAR
    public function update(Request $request, Lugar $lugar)
    {
        $request->validate($this->rules(true));

        $imagen = $this->handleImageUpload($request, $lugar->imagen);

        $lugar->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'ubicacion'      => $request->ubicacion,
            'latitud'        => $request->latitud ?: null,
            'longitud'       => $request->longitud ?: null,
            'categoria'      => $request->categoria,
            'imagen'         => $imagen,
            'precio_entrada' => $request->precio_entrada ?: 0,
            'horario'        => $request->horario,
        ]);

        return redirect()->route('admin.lugares.index')
                         ->with('success', 'Lugar actualizado correctamente.');
    }

    // 🗑️ ELIMINAR
    public function destroy(Lugar $lugar)
    {
        if ($lugar->imagen && !str_starts_with($lugar->imagen, 'http')) {
            Storage::disk('public')->delete($lugar->imagen);
        }

        $lugar->delete();

        return redirect()->route('admin.lugares.index')
                         ->with('success', 'Lugar eliminado correctamente.');
    }

    // 📤 EXCEL
    public function exportExcel()
    {
        return Excel::download(new LugaresExport, 'lugares.xlsx');
    }

    // 📄 PDF
    public function exportPdf()
    {
        $lugares = Lugar::all();

        $pdf = Pdf::loadView('admin.pdf.lugares', compact('lugares'));

        return $pdf->download('lugares.pdf');
    }

    // 📥 IMPORTAR EXCEL
    public function importExcel(Request $request)
    {
        return $this->runImport($request, new LugaresImport, 'admin.lugares.index');
    }
}