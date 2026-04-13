<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmpresasExport;
use App\Imports\EmpresasImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\NotificacionAdmin;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
   public function index(Request $request)
{
    $perPage        = $request->get('per_page', 10);
    $empresas       = Empresa::with('usuario')->orderBy('aprobado')->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    $notificaciones = NotificacionAdmin::with('empresa')->where('leido', false)->latest()->get();
    $notifCount     = $notificaciones->count();

    return view('admin.empresas', compact('empresas', 'notificaciones', 'notifCount', 'perPage'));
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
     public function exportExcel()
{
    return Excel::download(new EmpresasExport, 'empresas.xlsx');
}

public function importExcel(Request $request)
{
    $request->validate([
        'archivo' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new EmpresasImport, $request->file('archivo'));

    return redirect()->back()->with('success', 'Empresas importadas correctamente');
}

public function exportPdf()
{
    $empresas = User::where('rol', 'empresa')->get();

    $pdf = Pdf::loadView('admin.pdf.empresa', compact('empresas'));

    return $pdf->download('empresas.pdf');
}

}
