<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Lugar;
use App\Models\Evento;
use App\Models\Empresa;
use App\Models\Reserva;
use App\Models\User;
use App\Models\NotificacionAdmin;
use App\Models\Calificacion;
use App\Models\Favorito;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales para las stat-cards
        $totalUsuarios  = User::count();
        $totalEmpresas  = Empresa::count();
        $empresasPend   = Empresa::where('aprobado', false)->count();
        $totalLugares   = Lugar::count();
        $totalHoteles   = Hotel::count();
        $totalEventos   = Evento::count();
        $totalReservas  = Reserva::count();
        $reservasPend   = Reserva::where('estado', 'pendiente')->count();

        // Notificaciones pendientes de empresas
        $notificaciones     = NotificacionAdmin::with('empresa')->where('leido', false)->latest()->get();
        $notifCount         = $notificaciones->count();

        // Estadísticas de interacción
        $totalFavoritos      = Favorito::count();
        $totalCalificaciones = Calificacion::count();
        $totalComentarios    = Calificacion::whereNotNull('comentario')->where('comentario', '!=', '')->count();
        $promedioGeneral     = round(Calificacion::avg('calificacion') ?? 0, 1);

        // Top 5 hoteles más calificados
        $topHoteles = Calificacion::where('tipo', 'hotel')
            ->select('item_id', DB::raw('round(avg(calificacion),1) as promedio'), DB::raw('count(*) as total'))
            ->groupBy('item_id')
            ->orderByDesc('promedio')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->nombre = \App\Models\Hotel::find($row->item_id)?->nombre ?? 'Hotel #'.$row->item_id;
                return $row;
            });

        // Top 5 lugares más calificados
        $topLugares = Calificacion::where('tipo', 'lugar')
            ->select('item_id', DB::raw('round(avg(calificacion),1) as promedio'), DB::raw('count(*) as total'))
            ->groupBy('item_id')
            ->orderByDesc('promedio')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->nombre = \App\Models\Lugar::find($row->item_id)?->nombre ?? 'Lugar #'.$row->item_id;
                return $row;
            });

        // Últimas 5 reservas para la tabla
        $ultimasReservas = Reserva::with(['hotel', 'usuario'])
                                   ->orderBy('id', 'desc')
                                   ->limit(5)
                                   ->get();

        // Datos para Chart.js — registros por módulo
        $chartLabels = ['Hoteles', 'Lugares', 'Eventos', 'Empresas', 'Reservas', 'Usuarios'];
        $chartData   = [$totalHoteles, $totalLugares, $totalEventos, $totalEmpresas, $totalReservas, $totalUsuarios];

        // Reservas por estado para gráfica de dona
        $reservasPorEstado = Reserva::select('estado', DB::raw('count(*) as total'))
                                     ->groupBy('estado')
                                     ->pluck('total', 'estado');

        $estadoLabels = $reservasPorEstado->keys()->map(function($l) {
        return ucfirst($l);
         });

$estadoData = $reservasPorEstado->values();

        return view('admin.dashboard', compact(
            'totalUsuarios', 'totalEmpresas', 'empresasPend',
            'totalLugares', 'totalHoteles', 'totalEventos',
            'totalReservas', 'reservasPend',
            'ultimasReservas',
            'chartLabels', 'chartData',
            'reservasPorEstado',
            'estadoLabels', 'estadoData',
            'notificaciones', 'notifCount',
            'totalFavoritos', 'totalCalificaciones', 'totalComentarios', 'promedioGeneral',
            'topHoteles', 'topLugares'
        ));
    }
}
