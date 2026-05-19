<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\NotificacionAdmin;
use App\Models\Calificacion;
use App\Models\Favorito;
use App\Models\Habitacion;
use App\Models\PaqueteTuristico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmpresaDashboardController extends Controller
{
    public function index()
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->first();

        $statsCalificaciones = collect();
        $statsFavoritos      = collect();
        $promedioEmpresa     = 0;
        $totalFavoritosEmp   = 0;
        $totalReseñasEmp     = 0;

        if ($empresa) {
            $hotelIds      = $empresa->hoteles()->pluck('id');
            $gastronomiaIds = \App\Models\Gastronomia::where('empresa_id', $empresa->id)->pluck('id');

            // Calificaciones de hoteles
            $statsHoteles = collect();
            if ($hotelIds->isNotEmpty()) {
                $statsHoteles = Calificacion::where('tipo', 'hotel')
                    ->whereIn('item_id', $hotelIds)
                    ->selectRaw('item_id, round(avg(calificacion),1) as promedio, count(*) as total')
                    ->groupBy('item_id')
                    ->get()
                    ->map(function ($row) {
                        $row->nombre = \App\Models\Hotel::find($row->item_id)?->nombre ?? 'Hotel #'.$row->item_id;
                        $row->tipo_label = 'Hotel';
                        return $row;
                    });
            }

            // Calificaciones de gastronomía
            $statsGastronomia = collect();
            if ($gastronomiaIds->isNotEmpty()) {
                $statsGastronomia = Calificacion::where('tipo', 'gastronomia')
                    ->whereIn('item_id', $gastronomiaIds)
                    ->selectRaw('item_id, round(avg(calificacion),1) as promedio, count(*) as total')
                    ->groupBy('item_id')
                    ->get()
                    ->map(function ($row) {
                        $row->nombre = \App\Models\Gastronomia::find($row->item_id)?->nombre ?? 'Plato #'.$row->item_id;
                        $row->tipo_label = 'Gastronomía';
                        return $row;
                    });
            }

            $statsCalificaciones = $statsHoteles->merge($statsGastronomia);

            $promedioEmpresa = round(
                Calificacion::where(function ($q) use ($hotelIds, $gastronomiaIds) {
                    $q->where(function ($q2) use ($hotelIds) {
                        $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                    })->orWhere(function ($q2) use ($gastronomiaIds) {
                        $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                    });
                })->avg('calificacion') ?? 0,
                1
            );

            $totalReseñasEmp = Calificacion::where(function ($q) use ($hotelIds, $gastronomiaIds) {
                $q->where(function ($q2) use ($hotelIds) {
                    $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                })->orWhere(function ($q2) use ($gastronomiaIds) {
                    $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                });
            })->whereNotNull('comentario')->count();

            $totalFavoritosEmp = Favorito::where(function ($q) use ($hotelIds, $gastronomiaIds) {
                $q->where(function ($q2) use ($hotelIds) {
                    $q2->where('tipo', 'hotel')->whereIn('item_id', $hotelIds);
                })->orWhere(function ($q2) use ($gastronomiaIds) {
                    $q2->where('tipo', 'gastronomia')->whereIn('item_id', $gastronomiaIds);
                });
            })->count();
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
            'totalHabitaciones'       => $empresa ? Habitacion::whereHas('hotel', fn($q) => $q->where('empresa_id', $empresa->id))->count() : 0,
            'habitacionesDisponibles' => $empresa ? Habitacion::whereHas('hotel', fn($q) => $q->where('empresa_id', $empresa->id))->where('disponible', true)->count() : 0,
            'totalPaquetes'       => $empresa ? PaqueteTuristico::where('empresa_id', $empresa->id)->count() : 0,
            'paquetesActivos'     => $empresa ? PaqueteTuristico::where('empresa_id', $empresa->id)->where('activo', true)->count() : 0,
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

    public function editarPerfil()
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();
        return view('empresa.perfil', compact('empresa'));
    }

    public function actualizarPerfil(Request $request)
    {
        $empresa = Empresa::where('usuario_id', Auth::id())->firstOrFail();

        $request->validate([
            'nombre'            => ['required', 'string', 'max:200'],
            'telefono'          => ['nullable', 'string', 'max:30'],
            'direccion'         => ['nullable', 'string', 'max:400'],
            'tipo_empresa'      => ['nullable', 'in:hotel,restaurante,agencia_turismo,transporte,artesanias,otro'],
            'servicios'         => ['nullable', 'array'],
            'servicios.*'       => ['string', 'max:100'],
            'descripcion'       => ['nullable', 'string', 'max:1000'],
            'sitio_web'         => ['nullable', 'url'],
            'instagram'         => ['nullable', 'string', 'max:200'],
            'facebook'          => ['nullable', 'string', 'max:200'],
            'empresa_logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'empresa_logo_url'  => ['nullable', 'url'],
        ]);

        // Manejar logo
        $logo = $empresa->logo;
        if ($request->hasFile('empresa_logo_file')) {
            $logo = Storage::disk('public')->putFile('logos/empresas', $request->file('empresa_logo_file'));
        } elseif ($request->filled('empresa_logo_url')) {
            $logo = $request->empresa_logo_url;
        }

        $empresa->update([
            'nombre'       => $request->nombre,
            'telefono'     => $request->telefono,
            'direccion'    => $request->direccion,
            'tipo_empresa' => $request->tipo_empresa,
            'servicios'    => $request->servicios ?? [],
            'descripcion'  => $request->descripcion,
            'logo'         => $logo,
            'sitio_web'    => $request->sitio_web,
            'instagram'    => $request->instagram,
            'facebook'     => $request->facebook,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
