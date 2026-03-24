<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    // Listar todos los hoteles
    public function index()
    {
        $hoteles = Hotel::orderBy('id', 'desc')->get();
        return view('admin.hoteles', compact('hoteles'));
    }

    // Guardar nuevo hotel
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:150',
            'precio'   => 'required|numeric',
            'imagen'   => 'required|url',
            'descripcion' => 'required|string',
        ]);

        Hotel::create([
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'precio'        => $request->precio,
            'ubicacion'     => $request->ubicacion,
            'latitud'       => $request->latitud,
            'longitud'      => $request->longitud,
            'imagen'        => $request->imagen,
            'servicios'     => $request->servicios,
            'capacidad'     => $request->capacidad,
            'disponibilidad'=> $request->has('disponibilidad'),
            'telefono'      => $request->telefono,
            'email'         => $request->email,
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
        $request->validate([
            'nombre'   => 'required|string|max:150',
            'precio'   => 'required|numeric',
            'imagen'   => 'required|url',
            'descripcion' => 'required|string',
        ]);

        $hotel->update([
            'nombre'        => $request->nombre,
            'descripcion'   => $request->descripcion,
            'precio'        => $request->precio,
            'ubicacion'     => $request->ubicacion,
            'latitud'       => $request->latitud,
            'longitud'      => $request->longitud,
            'imagen'        => $request->imagen,
            'servicios'     => $request->servicios,
            'capacidad'     => $request->capacidad,
            'disponibilidad'=> $request->has('disponibilidad'),
            'telefono'      => $request->telefono,
            'email'         => $request->email,
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
