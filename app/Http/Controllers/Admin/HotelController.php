<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    // Reglas de validación compartidas
    private function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:150',
            'precio'      => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'imagen'      => 'required|url',
            'ubicacion'   => 'nullable|string|max:200',
            'capacidad'   => 'nullable|integer|min:1',
            'servicios'   => 'nullable|string',
            'telefono'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:150',
            'latitud'     => 'nullable|numeric|between:-90,90',
            'longitud'    => 'nullable|numeric|between:-180,180',
        ];
    }

    private function messages(): array
    {
        return [
            'latitud.between'  => 'La latitud debe estar entre -90 y 90 (ej: 4.711000).',
            'longitud.between' => 'La longitud debe estar entre -180 y 180 (ej: -74.072100).',
            'imagen.url'       => 'La imagen debe ser una URL válida (https://...).',
            'precio.min'       => 'El precio no puede ser negativo.',
        ];
    }

    // Listar todos los hoteles
    public function index()
    {
        $hoteles = Hotel::orderBy('id', 'desc')->get();
        return view('admin.hoteles', compact('hoteles'));
    }

    // Guardar nuevo hotel
    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        Hotel::create([
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'precio'        => $request->precio,
            'ubicacion'     => $request->ubicacion ?: null,
            'latitud'       => $request->filled('latitud') ? $request->latitud : null,
            'longitud'      => $request->filled('longitud') ? $request->longitud : null,
            'imagen'        => $request->imagen,
            'servicios'     => $request->servicios ?: null,
            'capacidad'     => $request->capacidad ?: null,
            'disponibilidad'=> $request->has('disponibilidad'),
            'telefono'      => $request->telefono ?: null,
            'email'         => $request->email ?: null,
        ]);

        return redirect()->route('admin.hoteles.index')
                         ->with('success', 'Hotel creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Hotel $hotel)
    {
        $hoteles = Hotel::orderBy('id', 'desc')->get();
        return view('admin.hoteles', compact('hoteles', 'hotel'));
    }

    // Actualizar hotel existente
    public function update(Request $request, Hotel $hotel)
    {
        $request->validate($this->rules(), $this->messages());

        $hotel->update([
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'precio'        => $request->precio,
            'ubicacion'     => $request->ubicacion ?: null,
            'latitud'       => $request->filled('latitud') ? $request->latitud : null,
            'longitud'      => $request->filled('longitud') ? $request->longitud : null,
            'imagen'        => $request->imagen,
            'servicios'     => $request->servicios ?: null,
            'capacidad'     => $request->capacidad ?: null,
            'disponibilidad'=> $request->has('disponibilidad'),
            'telefono'      => $request->telefono ?: null,
            'email'         => $request->email ?: null,
        ]);

        return redirect()->route('admin.hoteles.index')
                         ->with('success', 'Hotel actualizado correctamente.');
    }

    // Eliminar hotel
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hoteles.index')
                         ->with('success', 'Hotel eliminado correctamente.');
    }
}
