<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        // Cargamos el usuario relacionado para mostrar nombre y correo
        $empresas = Empresa::with('usuario')->orderBy('aprobado')->orderBy('id', 'desc')->get();
        return view('admin.empresas', compact('empresas'));
    }

    // Aprobar empresa
    public function aprobar(Empresa $empresa)
    {
        $empresa->update(['aprobado' => true]);
        return redirect()->route('admin.empresas.index')
                         ->with('success', "Empresa \"{$empresa->nombre}\" aprobada.");
    }

    // Rechazar / eliminar empresa
    public function rechazar(Empresa $empresa)
    {
        $nombre = $empresa->nombre;
        $empresa->delete();
        return redirect()->route('admin.empresas.index')
                         ->with('success', "Empresa \"{$nombre}\" rechazada y eliminada.");
    }

    // Editar datos de la empresa (nombre, teléfono, dirección)
    public function edit(Empresa $empresa)
    {
        $empresas = Empresa::with('usuario')->orderBy('aprobado')->orderBy('id', 'desc')->get();
        return view('admin.empresas', compact('empresas', 'empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre'    => 'required|string|max:200',
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:400',
        ]);

        $empresa->update($request->only(['nombre', 'telefono', 'direccion']));

        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('admin.empresas.index')
                         ->with('success', 'Empresa eliminada correctamente.');
    }
}
