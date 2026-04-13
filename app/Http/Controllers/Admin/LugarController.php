<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LugaresExport;
use App\Imports\LugaresImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lugar;
use Illuminate\Support\Facades\Storage;

class LugarController extends Controller
{
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

    private function rules(bool $isUpdate = false): array
    {
        $imgRequired = $isUpdate ? 'nullable' : 'required_without:imagen_file';
        return [
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'required|string',
            'categoria'    => 'required|string|max:100',
            'imagen_url'   => "$imgRequired|nullable|url",
            'imagen_file'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

   public function index(Request $request)
{
    $perPage = $request->get('per_page', 10);
    $lugares = Lugar::orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    return view('admin.lugares', compact('lugares', 'perPage'));
}
    public function store(Request $request)
    {
        $request->validate($this->rules(), [
            'imagen_url.required_without' => 'Debes ingresar una URL o subir una imagen.',
            'imagen_file.mimes'           => 'Solo se permiten imágenes JPG, PNG o WebP.',
            'imagen_file.max'             => 'La imagen no puede superar 4 MB.',
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

    public function edit(Lugar $lugar)
    {
        $lugares = Lugar::orderBy('id', 'desc')->get();
        return view('admin.lugares', compact('lugares', 'lugar'));
    }

    public function update(Request $request, Lugar $lugar)
    {
        $request->validate($this->rules(true), [
            'imagen_file.mimes' => 'Solo se permiten imágenes JPG, PNG o WebP.',
            'imagen_file.max'   => 'La imagen no puede superar 4 MB.',
        ]);

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

    public function destroy(Lugar $lugar)
    {
        if ($lugar->imagen && !str_starts_with($lugar->imagen, 'http')) {
            Storage::disk('public')->delete($lugar->imagen);
        }
        $lugar->delete();
        return redirect()->route('admin.lugares.index')
                         ->with('success', 'Lugar eliminado correctamente.');
    }

    public function exportExcel()
{
    return Excel::download(new LugaresExport, 'lugares.xlsx');
}

public function exportPdf()
{
    $lugares = \App\Models\Lugar::all();

    $pdf = Pdf::loadView('admin.pdf.lugares', compact('lugares'));

    return $pdf->download('lugares.pdf');
}
public function importExcel(Request $request)
{
    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new LugaresImport, $request->file('archivo'));

    return back()->with(
        'success',
        'Importación completada. Los lugares duplicados fueron omitidos.'
    );
}
}
