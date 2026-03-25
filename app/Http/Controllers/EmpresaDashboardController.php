<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\NotificacionAdmin;
use App\Models\Calificacion;
use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaDashboardController extends Controller
{
    public function index()
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->first();

        // Estadísticas de calificaciones y favoritos de los hoteles de esta empresa
        $statsCalificaciones = collect();
        $statsFavoritos      = collect();
        $promedioEmpresa     = 0;
        $totalFavoritosEmp   = 0;
        $totalReseñasEmp     = 0;

        if ($empresa) {
            // Hoteles asociados a esta empresa (via empresa_id si existe, o por nombre)
            $hotelIds = \App\Models\Hotel::where('empresa_id', $empresa->id)->pluck('id');

            if ($hotelIds->isNotEmpty()) {
                $statsCalificaciones = Calificacion::where('tipo', 'hotel')
                    ->whereIn('item_id', $hotelIds)
                    ->selectRaw('item_id, round(avg(calificacion),1) as promedio, count(*) as total')
                    ->groupBy('item_id')
                    ->get()
                    ->map(function ($row) {
                        $row->nombre = \App\Models\Hotel::find($row->item_id)?->nombre ?? 'Hotel #'.$row->item_id;
                        return $row;
                    });

                $promedioEmpresa   = round(Calificacion::where('tipo', 'hotel')->whereIn('item_id', $hotelIds)->avg('calificacion') ?? 0, 1);
                $totalReseñasEmp   = Calificacion::where('tipo', 'hotel')->whereIn('item_id', $hotelIds)->whereNotNull('comentario')->count();
                $totalFavoritosEmp = Favorito::where('tipo', 'hotel')->whereIn('item_id', $hotelIds)->count();
            }
        }

        return view('empresa.dashboard', [
            'empresa'             => $empresa,
            'historial'           => $empresa
                ? NotificacionAdmin::where('empresa_id', $empresa->id)->latest()->get()
                : collect(),
            'statsCalificaciones' => $statsCalificaciones,
            'promedioEmpresa'     => $promedioEmpresa,
            'totalFavoritosEmp'   => $totalFavoritosEmp,
            'totalReseñasEmp'     => $totalReseñasEmp,
        ]);
    }

    public function enviarSolicitud(Request $request)
    {
        $request->validate([
            'tipo'        => ['required', 'in:hotel,restaurante,actualizacion,novedad'],
            'descripcion' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
        ]);

        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        $tipos = [
            'hotel'         => 'SOLICITUD NUEVO HOTEL',
            'restaurante'   => 'SOLICITUD NUEVO RESTAURANTE',
            'actualizacion' => 'SOLICITUD ACTUALIZACIÓN DE DATOS',
            'novedad'       => 'NOVEDAD / REPORTE',
        ];

        $mensaje = $tipos[$request->tipo] . "\n" . $request->descripcion;

        NotificacionAdmin::create([
            'empresa_id' => $empresa->id,
            'mensaje'    => $mensaje,
            'leido'      => false,
        ]);

        return back()->with('success', 'Solicitud enviada. El administrador la revisará pronto.');
    }
}
