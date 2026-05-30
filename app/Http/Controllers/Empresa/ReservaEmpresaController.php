<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Reserva;
use App\Models\Calificacion;
use App\Notifications\ReservaConfirmada;
use App\Notifications\ReservaCancelada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaEmpresaController extends Controller
{
    private function empresa(): Empresa
    {
        return Empresa::where('usuario_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $empresa  = $this->empresa();
        $hotelIds = $empresa->hoteles()->pluck('id');

        $query = Reserva::with(['hotel', 'usuario'])
            ->whereIn('hotel_id', $hotelIds);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_entrada', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_salida', '<=', $request->fecha_fin);
        }

        $reservas = (clone $query)->latest()->paginate(15)->withQueryString();

        $totalReservas = (clone $query)->count();
        $ingresos      = Reserva::whereIn('hotel_id', $hotelIds)
                            ->where('estado', 'confirmada')->sum('precio_total');
        $pendientes    = Reserva::whereIn('hotel_id', $hotelIds)
                            ->where('estado', 'pendiente')->count();

        $calificaciones = Calificacion::where('tipo', 'hotel')
            ->whereIn('item_id', $hotelIds)
            ->with('usuario')
            ->latest()
            ->take(20)
            ->get();

        return view('empresa.reservas', compact(
            'empresa', 'reservas', 'calificaciones',
            'totalReservas', 'ingresos', 'pendientes'
        ));
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $empresa  = $this->empresa();
        $hotelIds = $empresa->hoteles()->pluck('id');

        abort_unless($hotelIds->contains($reserva->hotel_id), 403);

        $request->validate(['estado' => 'required|in:pendiente,confirmada,cancelada']);

        $estadoAnterior = $reserva->estado;
        $reserva->update(['estado' => $request->estado]);

        // Enviar notificación al usuario
        if ($reserva->usuario) {
            if ($request->estado === 'confirmada' && $estadoAnterior !== 'confirmada') {
                $reserva->usuario->notify(new ReservaConfirmada($reserva));
            } elseif ($request->estado === 'cancelada' && $estadoAnterior !== 'cancelada') {
                $reserva->usuario->notify(new ReservaCancelada($reserva));
            }
        }

        return back()->with('success', 'Estado de reserva actualizado.');
    }
}