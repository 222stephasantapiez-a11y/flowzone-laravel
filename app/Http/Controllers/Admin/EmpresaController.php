<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\NotificacionAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\EmpresasExport;
use App\Imports\EmpresasImport;

class EmpresaController extends Controller
{
    // ==========================
    // LISTAR + GENERADOR + PAGINACIÓN
    // ==========================
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $empresas = Empresa::with('usuario')
            ->orderBy('aprobado')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $notificaciones = NotificacionAdmin::with('empresa')
            ->where('leido', false)
            ->latest()
            ->get();

        $notifCount = $notificaciones->count();

        // ==========================
        // GENERADOR DE PLAN
        // ==========================
        $plan = null;

        if ($request->has('generar')) {
            $evento      = DB::table('eventos')->inRandomOrder()->first();
            $gastronomia = DB::table('gastronomia')->inRandomOrder()->first();
            $hotel       = DB::table('hoteles')->inRandomOrder()->first();
            $lugar       = DB::table('lugares')->inRandomOrder()->first();

            if ($evento && $gastronomia && $hotel && $lugar) {
                $subtotal = ($evento->precio ?? 0)
                    + ($gastronomia->precio_promedio ?? 0)
                    + ($hotel->precio ?? 0)
                    + ($lugar->precio_entrada ?? 0);

                $descuento   = $subtotal * 0.20;
                $precioFinal = $subtotal - $descuento;

                $plan = [
                    'evento'       => $evento,
                    'gastronomia'  => $gastronomia,
                    'hotel'        => $hotel,
                    'lugar'        => $lugar,
                    'subtotal'     => $subtotal,
                    'descuento'    => $descuento,
                    'precioFinal'  => $precioFinal,
                ];
            }
        }

        return view('admin.empresas', compact(
            'empresas',
            'notificaciones',
            'notifCount',
            'plan',
            'perPage'
        ));
    }

    // ==========================
    // APROBAR EMPRESA
    // ==========================
    public function aprobar(Empresa $empresa)
    {
        $empresa->update(['aprobado' => true]);

        if ($empresa->usuario) {
            $empresa->usuario->update(['estado' => 'activo']);
        }

        return redirect()->route('admin.empresas.index')
            ->with('success', "Empresa \"{$empresa->nombre}\" aprobada.");
    }

    // ==========================
    // RECHAZAR EMPRESA
    // ==========================
    public function rechazar(Empresa $empresa)
    {
        $nombre = $empresa->nombre;

        if ($empresa->usuario) {
            $empresa->usuario->update(['estado' => 'bloqueado']);
        }

        $empresa->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', "Empresa \"{$nombre}\" rechazada y eliminada.");
    }

    // ==========================
    // EDITAR
    // ==========================
    public function edit(Empresa $empresa)
    {
        $empresas = Empresa::with('usuario')
            ->orderBy('aprobado')
            ->orderBy('id', 'desc')
            ->get();

        $notificaciones = NotificacionAdmin::with('empresa')
            ->where('leido', false)
            ->latest()
            ->get();

        $notifCount = $notificaciones->count();

        return view('admin.empresas', compact(
            'empresas',
            'empresa',
            'notificaciones',
            'notifCount'
        ));
    }

    // ==========================
    // ACTUALIZAR
    // ==========================
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre'    => 'required|string|max:200',
            'telefono'  => 'nullable|string|max:30',
            'direccion' => 'nullable|string|max:400',
        ]);

        $empresa->update($request->only([
            'nombre',
            'telefono',
            'direccion'
        ]));

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    // ==========================
    // ELIMINAR
    // ==========================
    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }

    // ==========================
    // NOTIFICACIONES
    // ==========================
    public function marcarLeida(NotificacionAdmin $notificacion)
    {
        $notificacion->update(['leido' => true]);

        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        NotificacionAdmin::where('leido', false)
            ->update(['leido' => true]);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    // ==========================
    // EXPORTAR EXCEL
    // ==========================
    public function exportExcel()
    {
        return Excel::download(new EmpresasExport, 'empresas.xlsx');
    }

    // ==========================
    // IMPORTAR EXCEL
    // ==========================
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new EmpresasImport, $request->file('archivo'));

        return back()->with('success', 'Empresas importadas correctamente');
    }

    // ==========================
    // EXPORTAR PDF
    // ==========================
    public function exportPdf()
    {
        $empresas = User::where('rol', 'empresa')->get();

        $pdf = Pdf::loadView('admin.pdf.empresa', compact('empresas'));

        return $pdf->download('empresas.pdf');
    }
}