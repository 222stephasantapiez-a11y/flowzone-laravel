<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\NotificacionAdmin;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas       = Empresa::with('usuario')->orderBy('aprobado')->orderBy('id', 'desc')->get();
        $notificaciones = NotificacionAdmin::with('empresa')->where('leido', false)->latest()->get();
        $notifCount     = $notificaciones->count();

        return view('admin.empresas', compact('empresas', 'notificaciones', 'notifCount'));
    }

    public function aprobar(Empresa $empresa)
    {
        $empresa->update(['aprobado' => true]);

        // Activar el usuario asociado para que pueda iniciar sesión
        if ($empresa->usuario) {
            $empresa->usuario->update(['estado' => 'activo']);
        }

        return redirect()->route('admin.empresas.index')
                         ->with('success', "Empresa \"{$empresa->nombre}\" aprobada.");
    }

    public function rechazar(Empresa $empresa)
    {
        $nombre = $empresa->nombre;

        // Bloquear el usuario asociado
        if ($empresa->usuario) {
            $empresa->usuario->update(['estado' => 'bloqueado']);
        }

        $empresa->delete();
        return redirect()->route('admin.empresas.index')
                         ->with('success', "Empresa \"{$nombre}\" rechazada y eliminada.");
    }

    public function edit(Empresa $empresa)
    {
        $empresas       = Empresa::with('usuario')->orderBy('aprobado')->orderBy('id', 'desc')->get();
        $notificaciones = NotificacionAdmin::with('empresa')->where('leido', false)->latest()->get();
        $notifCount     = $notificaciones->count();

        return view('admin.empresas', compact('empresas', 'empresa', 'notificaciones', 'notifCount'));
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

    // Marcar notificación como leída
    public function marcarLeida(NotificacionAdmin $notificacion)
    {
        $notificacion->update(['leido' => true]);
        return back()->with('success', 'Notificación marcada como leída.');
    }

    // Marcar todas como leídas
    public function marcarTodasLeidas()
    {
        NotificacionAdmin::where('leido', false)->update(['leido' => true]);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
