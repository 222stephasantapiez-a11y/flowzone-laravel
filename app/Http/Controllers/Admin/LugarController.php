<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use Illuminate\Http\Request;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::orderBy('id', 'desc')->get();
        return view('admin.lugares', compact('lugares'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'required|string',
            'categoria'   => 'required|string|max:100',
            'imagen'      => 'required|url',
        ]);

        Lugar::create($request->only([
            'nombre', 'descripcion', 'ubicacion',
            'latitud', 'longitud', 'categoria',
            'imagen', 'precio_entrada', 'horario',
        ]));

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
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'required|string',
            'categoria'   => 'required|string|max:100',
            'imagen'      => 'required|url',
        ]);

        $lugar->update($request->only([
            'nombre', 'descripcion', 'ubicacion',
            'latitud', 'longitud', 'categoria',
            'imagen', 'precio_entrada', 'horario',
        ]));

        return redirect()->route('admin.lugares.index')
                         ->with('success', 'Lugar actualizado correctamente.');
    }

    public function destroy(Lugar $lugar)
    {
        $lugar->delete();
        return redirect()->route('admin.lugares.index')
                         ->with('success', 'Lugar eliminado correctamente.');
    }
}
