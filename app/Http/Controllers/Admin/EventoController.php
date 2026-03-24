<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::orderBy('fecha', 'desc')->get();
        return view('admin.eventos', compact('eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'required|string',
            'fecha'       => 'required|date',
            'imagen'      => 'required|url',
        ]);

        Evento::create($request->only([
            'nombre', 'descripcion', 'fecha', 'hora',
            'ubicacion', 'categoria', 'imagen',
            'precio', 'organizador', 'contacto',
        ]));

        return redirect()->route('admin.eventos.index')
                         ->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento)
    {
        $eventos = Evento::orderBy('fecha', 'desc')->get();
        return view('admin.eventos', compact('eventos', 'evento'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'nombre'      => 'required|string|max:150',
            'descripcion' => 'required|string',
            'fecha'       => 'required|date',
            'imagen'      => 'required|url',
        ]);

        $evento->update($request->only([
            'nombre', 'descripcion', 'fecha', 'hora',
            'ubicacion', 'categoria', 'imagen',
            'precio', 'organizador', 'contacto',
        ]));

        return redirect()->route('admin.eventos.index')
                         ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('admin.eventos.index')
                         ->with('success', 'Evento eliminado correctamente.');
    }
}
