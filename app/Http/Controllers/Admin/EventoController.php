<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\EventosExport;
use App\Imports\EventosImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EventoController extends Controller
{
    private function handleImageUpload(Request $request, ?string $currentImage = null): string
    {
        if ($request->hasFile('imagen_file')) {
            if ($currentImage && !str_starts_with($currentImage, 'http')) {
                Storage::disk('public')->delete($currentImage);
            }
            return $request->file('imagen_file')->store('uploads/eventos', 'public');
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
            'fecha'        => 'required|date',
            'imagen_url'   => "$imgRequired|nullable|url",
            'imagen_file'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $eventos = Evento::orderBy('fecha', 'asc')->paginate($perPage)->withQueryString();
        return view('admin.eventos', compact('eventos', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), [
            'imagen_url.required_without' => 'Debes ingresar una URL o subir una imagen.',
            'imagen_file.mimes'           => 'Solo se permiten imágenes JPG, PNG o WebP.',
            'imagen_file.max'             => 'La imagen no puede superar 4 MB.',
        ]);

        $imagen = $this->handleImageUpload($request);

        Evento::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'ubicacion'   => $request->ubicacion,
            'categoria'   => $request->categoria,
            'imagen'      => $imagen,
            'precio'      => $request->precio ?: 0,
            'organizador' => $request->organizador,
            'contacto'    => $request->contacto,
        ]);

        return redirect()->route('admin.eventos.index')
                         ->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento)
    {
        $perPage = 10;
        $eventos = Evento::orderBy('fecha', 'desc')->paginate($perPage)->withQueryString();
        return view('admin.eventos', compact('eventos', 'evento', 'perPage'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate($this->rules(true), [
            'imagen_file.mimes' => 'Solo se permiten imágenes JPG, PNG o WebP.',
            'imagen_file.max'   => 'La imagen no puede superar 4 MB.',
        ]);

        $imagen = $this->handleImageUpload($request, $evento->imagen);

        $evento->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'ubicacion'   => $request->ubicacion,
            'categoria'   => $request->categoria,
            'imagen'      => $imagen,
            'precio'      => $request->precio ?: 0,
            'organizador' => $request->organizador,
            'contacto'    => $request->contacto,
        ]);

        return redirect()->route('admin.eventos.index')
                         ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        if ($evento->imagen && !str_starts_with($evento->imagen, 'http')) {
            Storage::disk('public')->delete($evento->imagen);
        }
        $evento->delete();
        return redirect()->route('admin.eventos.index')
                         ->with('success', 'Evento eliminado correctamente.');
    }

    public function exportExcel()
    {
        return Excel::download(new EventosExport, 'eventos.xlsx');
    }

    public function exportPdf()
    {
        $eventos = \App\Models\Evento::all();

        $pdf = Pdf::loadView('admin.pdf.eventos', compact('eventos'));

        return $pdf->download('eventos.pdf');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new EventosImport, $request->file('archivo'));

        return back()->with('success', 'Eventos importados correctamente');
    }
}