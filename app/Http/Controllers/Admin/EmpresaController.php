<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\HandlesImport;
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
    use HandlesImport;

    // ==========================
    // LISTAR + PAGINACIÓN
    // ==========================
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $sort      = $request->get('sort', 'aprobado');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = ['id', 'nombre', 'aprobado', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'aprobado';
        }
        $direction = $direction === 'desc' ? 'desc' : 'asc';

        $empresas = Empresa::with('usuario')
            ->when($request->filled('busqueda'), function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->busqueda . '%');
            })
            ->when($request->filled('estado'), function ($q) use ($request) {
                $q->where('aprobado', $request->estado === 'aprobado' ? true : false);
            })
            ->when($request->filled('responsable'), function ($q) use ($request) {
                $q->whereHas('usuario', function ($u) use ($request) {
                    $u->where('name', 'like', '%' . $request->responsable . '%')
                      ->orWhere('email', 'like', '%' . $request->responsable . '%');
                });
            })
            ->orderBy($sort, $direction)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Solo mostrar solicitudes de empresas, no las respuestas del admin
        $notificaciones = NotificacionAdmin::with('empresa')
            ->where('leido', false)
            ->where('mensaje', 'not like', 'RESPUESTA DEL ADMIN:%')
            ->latest()
            ->get();

        $notifCount = $notificaciones->count();

        return view('admin.empresas', compact(
            'empresas',
            'notificaciones',
            'notifCount',
            'perPage',
            'sort',
            'direction'
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

        if ($empresa->tipo_empresa === 'hotel' && $empresa->hoteles()->count() === 0) {
            \App\Models\Hotel::create([
                'empresa_id'     => $empresa->id,
                'nombre'         => $empresa->nombre,
                'descripcion'    => $empresa->descripcion,
                'telefono'       => $empresa->telefono,
                'precio'         => 0,
                'disponibilidad' => false,
            ]);
        }

        NotificacionAdmin::create([
            'empresa_id' => $empresa->id,
            'mensaje'    => 'APROBACIÓN: Tu empresa ha sido aprobada. Ya apareces en el sitio público.',
            'leido'      => false,
        ]);

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
        $perPage = 10;

        $empresas = Empresa::with('usuario')
            ->orderBy('aprobado')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        // Solo mostrar solicitudes de empresas, no las respuestas del admin
        $notificaciones = NotificacionAdmin::with('empresa')
            ->where('leido', false)
            ->where('mensaje', 'not like', 'RESPUESTA DEL ADMIN:%')
            ->latest()
            ->get();

        $notifCount = $notificaciones->count();

        return view('admin.empresas', compact(
            'empresas',
            'empresa',
            'notificaciones',
            'notifCount',
            'perPage'
        ));
    }

    // ==========================
    // ACTUALIZAR
    // ==========================
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre'       => 'required|string|max:200',
            'telefono'     => 'nullable|string|max:30',
            'direccion'    => 'nullable|string|max:400',
            'tipo_empresa' => 'nullable|in:hotel,restaurante,agencia_turismo,transporte,artesanias,otro',
            'nit'          => 'nullable|string|max:20',
            'descripcion'  => 'nullable|string|max:1000',
            'servicios'    => 'nullable|array',
            'servicios.*'  => 'string|max:100',
            'sitio_web'    => 'nullable|url',
            'instagram'    => 'nullable|string|max:200',
            'facebook'     => 'nullable|string|max:200',
        ]);

        $empresa->update($request->only([
            'nombre', 'telefono', 'direccion', 'tipo_empresa',
            'nit', 'descripcion', 'servicios', 'sitio_web', 'instagram', 'facebook',
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
    public function responder(Request $request, NotificacionAdmin $notificacion)
    {
        $request->validate([
            'respuesta' => 'required|string|min:5|max:1000',
        ]);

        // Crear respuesta para que la empresa la vea en su historial
        NotificacionAdmin::create([
            'empresa_id' => $notificacion->empresa_id,
            'mensaje'    => 'RESPUESTA DEL ADMIN: ' . $request->respuesta,
            'leido'      => false,
        ]);

        // Marcar la solicitud original como leída — desaparece del panel admin
        $notificacion->update(['leido' => true]);

        return back()->with('success', 'Respuesta enviada a la empresa.');
    }

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
        return $this->runImport($request, new EmpresasImport, 'admin.empresas.index');
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