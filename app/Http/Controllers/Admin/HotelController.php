<?php

namespace App\Http\Controllers\Admin;

use App\Exports\HotelesExport;
use App\Imports\HotelesImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    private function handleImageUpload(Request $request, ?string $currentImage = null): string
    {
        // Opción B: archivo subido
        if ($request->hasFile('imagen_file')) {
            if ($currentImage && !str_starts_with($currentImage, 'http')) {
                Storage::disk('public')->delete($currentImage);
            }
            $path = $request->file('imagen_file')->store('uploads/hoteles', 'public');
            return $path;
        }

        // Opción A: URL
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
            'precio'       => 'required|numeric|min:0',
            'descripcion'  => 'required|string',
            'imagen_url'   => "$imgRequired|nullable|url",
            'imagen_file'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:4096',
            'ubicacion'    => 'nullable|string|max:200',
            'capacidad'    => 'nullable|integer|min:1',
            'servicios'    => 'nullable|string',
            'telefono'     => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:150',
            'latitud'      => 'nullable|numeric|between:-90,90',
            'longitud'     => 'nullable|numeric|between:-180,180',
        ];
    }

    private function messages(): array
    {
        return [
            'latitud.between'          => 'La latitud debe estar entre -90 y 90.',
            'longitud.between'         => 'La longitud debe estar entre -180 y 180.',
            'imagen_url.url'           => 'La URL de imagen debe ser válida (https://...).',
            'imagen_file.mimes'        => 'Solo se permiten imágenes JPG, PNG o WebP.',
            'imagen_file.max'          => 'La imagen no puede superar 4 MB.',
            'imagen_url.required_without' => 'Debes ingresar una URL o subir una imagen.',
        ];
    }

    public function index()
    {
        $hoteles = Hotel::orderBy('id', 'desc')->get();
        return view('admin.hoteles', compact('hoteles'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        $imagen = $this->handleImageUpload($request);

        Hotel::create([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'precio'         => $request->precio,
            'ubicacion'      => $request->ubicacion ?: null,
            'latitud'        => $request->filled('latitud') ? $request->latitud : null,
            'longitud'       => $request->filled('longitud') ? $request->longitud : null,
            'imagen'         => $imagen,
            'servicios'      => $request->servicios ?: null,
            'capacidad'      => $request->capacidad ?: null,
            'disponibilidad' => $request->has('disponibilidad'),
            'telefono'       => $request->telefono ?: null,
            'email'          => $request->email ?: null,
        ]);

        return redirect()->route('admin.hoteles.index')
                         ->with('success', 'Hotel creado correctamente.');
    }

    public function edit(Hotel $hotel)
    {
        $hoteles = Hotel::orderBy('id', 'desc')->get();
        return view('admin.hoteles', compact('hoteles', 'hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate($this->rules(true), $this->messages());

        $imagen = $this->handleImageUpload($request, $hotel->imagen);

        $hotel->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'precio'         => $request->precio,
            'ubicacion'      => $request->ubicacion ?: null,
            'latitud'        => $request->filled('latitud') ? $request->latitud : null,
            'longitud'       => $request->filled('longitud') ? $request->longitud : null,
            'imagen'         => $imagen,
            'servicios'      => $request->servicios ?: null,
            'capacidad'      => $request->capacidad ?: null,
            'disponibilidad' => $request->has('disponibilidad'),
            'telefono'       => $request->telefono ?: null,
            'email'          => $request->email ?: null,
        ]);

        return redirect()->route('admin.hoteles.index')
                         ->with('success', 'Hotel actualizado correctamente.');
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->imagen && !str_starts_with($hotel->imagen, 'http')) {
            Storage::disk('public')->delete($hotel->imagen);
        }
        $hotel->delete();
        return redirect()->route('admin.hoteles.index')
                         ->with('success', 'Hotel eliminado correctamente.');
    }
    public function exportExcel()
   {
    return Excel::download(new HotelesExport, 'hoteles.xlsx');
   }

    public function exportPdf()
{
    $hoteles = \App\Models\Hotel::all();

    $pdf = Pdf::loadView('admin.pdf.hoteles', compact('hoteles'));

    return $pdf->download('hoteles.pdf');
  }

     public function importExcel(Request $request)
    {
    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new HotelesImport, $request->file('archivo'));

    return back()->with('success', 'Hoteles importados correctamente');
    }
}
