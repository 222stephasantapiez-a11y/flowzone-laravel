<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Lugar;
use App\Models\Evento;
use App\Models\Gastronomia;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    // ── Inicio ──────────────────────────────────────────────
    public function home()
    {
        return view('pages.home', [
            'hoteles_destacados' => Hotel::where('disponibilidad', true)->latest()->take(3)->get(),
            'lugares_destacados' => Lugar::latest()->take(3)->get(),
            'eventos_proximos'   => Evento::where('fecha', '>=', now())->orderBy('fecha')->take(3)->get(),
        ]);
    }

    // ── Hoteles ──────────────────────────────────────────────
    public function hoteles(Request $request)
    {
        $query = Hotel::where('disponibilidad', true);

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('nombre', 'ilike', "%$q%")->orWhere('descripcion', 'ilike', "%$q%"));
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        return view('pages.hoteles', [
            'hoteles'   => $query->latest()->get(),
            'busqueda'  => $request->busqueda ?? '',
            'precio_max'=> $request->precio_max ?? '',
        ]);
    }

    public function detalleHotel(Hotel $hotel)
    {
        return view('pages.detalle_hotel', [
            'hotel'      => $hotel,
            'es_favorito'=> false, // TODO: favoritos
        ]);
    }

    // ── Lugares ──────────────────────────────────────────────
    public function lugares(Request $request)
    {
        $query = Lugar::query();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('nombre', 'ilike', "%$q%")->orWhere('descripcion', 'ilike', "%$q%"));
        }

        return view('pages.lugares', [
            'lugares'         => $query->latest()->get(),
            'categorias'      => Lugar::distinct()->pluck('categoria')->filter()->sort()->values(),
            'categoria_filtro'=> $request->categoria ?? '',
            'busqueda'        => $request->busqueda ?? '',
        ]);
    }

    public function detalleLugar(Lugar $lugar)
    {
        return view('pages.detalle_lugar', [
            'lugar'      => $lugar,
            'es_favorito'=> false,
        ]);
    }

    // ── Eventos ──────────────────────────────────────────────
    public function eventos(Request $request)
    {
        $query = Evento::where('fecha', '>=', now());

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('nombre', 'ilike', "%$q%")->orWhere('descripcion', 'ilike', "%$q%"));
        }

        return view('pages.eventos', [
            'eventos'         => $query->orderBy('fecha')->get(),
            'categorias'      => Evento::distinct()->pluck('categoria')->filter()->sort()->values(),
            'categoria_filtro'=> $request->categoria ?? '',
            'busqueda'        => $request->busqueda ?? '',
        ]);
    }

    // ── Gastronomía ──────────────────────────────────────────
    public function gastronomia(Request $request)
    {
        $query = Gastronomia::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('nombre', 'ilike', "%$q%")->orWhere('descripcion', 'ilike', "%$q%"));
        }

        return view('pages.gastronomia', [
            'platos'     => $query->latest()->get(),
            'tipos'      => Gastronomia::distinct()->pluck('tipo')->filter()->sort()->values(),
            'tipo_filtro'=> $request->tipo ?? '',
            'busqueda'   => $request->busqueda ?? '',
        ]);
    }

    // ── Contacto ─────────────────────────────────────────────
    public function contacto()
    {
        return view('pages.contacto');
    }

    // ── Reservas (requiere auth) ──────────────────────────────
    public function reservaForm(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        if (!$hotel->disponibilidad) {
            return redirect()->route('hoteles')->with('error', 'Hotel no disponible.');
        }

        return view('pages.reserva', ['hotel' => $hotel, 'error' => null, 'success' => null]);
    }

    public function reservaStore(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        $request->validate([
            'fecha_entrada' => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'  => ['required', 'date', 'after:fecha_entrada'],
            'num_personas'  => ['required', 'integer', 'min:1', 'max:' . $hotel->capacidad],
        ], [
            'fecha_entrada.after_or_equal' => 'La fecha de entrada no puede ser anterior a hoy.',
            'fecha_salida.after'           => 'La fecha de salida debe ser posterior a la entrada.',
            'num_personas.max'             => 'Excede la capacidad del hotel (' . $hotel->capacidad . ' personas).',
        ]);

        $dias = now()->parse($request->fecha_entrada)->diffInDays($request->fecha_salida);
        $total = $dias * $hotel->precio;

        Reserva::create([
            'usuario_id'   => Auth::id(),
            'hotel_id'     => $hotel->id,
            'fecha_entrada'=> $request->fecha_entrada,
            'fecha_salida' => $request->fecha_salida,
            'num_personas' => $request->num_personas,
            'precio_total' => $total,
            'estado'       => 'pendiente',
        ]);

        return redirect()->route('mis-reservas')->with('success', '¡Reserva realizada! Total: $' . number_format($total, 0, ',', '.') . ' COP');
    }

    public function misReservas(Request $request)
    {
        $reservas = Reserva::with('hotel')
            ->where('usuario_id', Auth::id())
            ->latest()
            ->get();

        if ($request->filled('cancelar')) {
            $reserva = Reserva::where('id', $request->cancelar)
                ->where('usuario_id', Auth::id())
                ->where('estado', 'pendiente')
                ->first();

            if ($reserva) {
                $reserva->update(['estado' => 'cancelada']);
                return redirect()->route('mis-reservas')->with('success', 'Reserva cancelada.');
            }
            return redirect()->route('mis-reservas')->with('error', 'No se pudo cancelar la reserva.');
        }

        return view('pages.mis_reservas', [
            'reservas'     => $reservas,
            'pendientes'   => $reservas->where('estado', 'pendiente'),
            'confirmadas'  => $reservas->where('estado', 'confirmada'),
            'canceladas'   => $reservas->where('estado', 'cancelada'),
            'total_gastado'=> $reservas->whereNotIn('estado', ['cancelada'])->sum('precio_total'),
        ]);
    }

    // ── Favoritos (requiere auth) ─────────────────────────────
    public function favoritos()
    {
        return view('pages.favoritos');
    }
}
