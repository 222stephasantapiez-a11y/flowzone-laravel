<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Lugar;
use App\Models\Evento;
use App\Models\Empresa;
use App\Models\Gastronomia;
use App\Models\Reserva;
use App\Models\BlogPost;
use App\Models\Calificacion;
use App\Models\Favorito;
use App\Models\HeroImage;
use App\Models\Habitacion;
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
            'blog_recientes'     => BlogPost::where('publicado', true)->latest('fecha_publicacion')->take(3)->get(),
            'heroImgs'           => HeroImage::where('activa', true)->where('seccion', 'hero')->where(function ($q) {
                $q->whereNull('empresa_id')->orWhereHas('empresa', fn($e) => $e->where('aprobado', true));
            })->orderBy('orden')->get(),
            'totalHoteles'       => Hotel::count(),
            'totalLugares'       => Lugar::count(),
            'totalEventos'       => Evento::count(),
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
            'hoteles'    => $query->latest()->get(),
            'busqueda'   => $request->busqueda ?? '',
            'precio_max' => $request->precio_max ?? '',
        ]);
    }

    public function detalleHotel(Hotel $hotel)
    {
        $stats = Calificacion::stats('hotel', $hotel->id);
        $miCalificacion = auth()->check()
            ? Calificacion::where(['usuario_id' => auth()->id(), 'tipo' => 'hotel', 'item_id' => $hotel->id])->first()
            : null;
        $reseñas = Calificacion::with('usuario')
            ->where('tipo', 'hotel')->where('item_id', $hotel->id)
            ->whereNotNull('comentario')->latest()->take(10)->get();

        $es_favorito = auth()->check()
            ? Favorito::esFavorito(auth()->id(), 'hotel', $hotel->id)
            : false;

        return view('pages.detalle_hotel', compact('hotel', 'stats', 'miCalificacion', 'reseñas', 'es_favorito'));
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
            'lugares'          => $query->latest()->get(),
            'categorias'       => Lugar::distinct()->pluck('categoria')->filter()->sort()->values(),
            'categoria_filtro' => $request->categoria ?? '',
            'busqueda'         => $request->busqueda ?? '',
        ]);
    }

    public function detalleLugar(Lugar $lugar)
    {
        $stats = Calificacion::stats('lugar', $lugar->id);
        $miCalificacion = auth()->check()
            ? Calificacion::where(['usuario_id' => auth()->id(), 'tipo' => 'lugar', 'item_id' => $lugar->id])->first()
            : null;
        $reseñas = Calificacion::with('usuario')
            ->where('tipo', 'lugar')->where('item_id', $lugar->id)
            ->whereNotNull('comentario')->latest()->take(10)->get();

        $es_favorito = auth()->check()
            ? Favorito::esFavorito(auth()->id(), 'lugar', $lugar->id)
            : false;

        return view('pages.detalle_lugar', compact('lugar', 'stats', 'miCalificacion', 'reseñas', 'es_favorito'));
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
            'eventos'          => $query->orderBy('fecha')->get(),
            'categorias'       => Evento::distinct()->pluck('categoria')->filter()->sort()->values(),
            'categoria_filtro' => $request->categoria ?? '',
            'busqueda'         => $request->busqueda ?? '',
        ]);
    }

    public function detalleEvento(Evento $evento)
    {
        return view('pages.detalle_evento', compact('evento'));
    }

    // ── Gastronomía ──────────────────────────────────────────
    public function gastronomia(Request $request)
    {
        $query = Gastronomia::with('empresa');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('nombre', 'like', "%$q%")->orWhere('descripcion', 'like', "%$q%"));
        }

        $restaurantesQuery = Empresa::where('tipo_empresa', 'restaurante')->where('aprobado', true);

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $restaurantesQuery->where(
                fn($q2) => $q2->where('nombre', 'like', "%$q%")->orWhere('descripcion', 'like', "%$q%")
            );
        }

        return view('pages.gastronomia', [
            'platos'       => $query->latest()->get(),
            'tipos'        => Gastronomia::distinct()->pluck('tipo')->filter()->sort()->values(),
            'tipo_filtro'  => $request->tipo ?? '',
            'busqueda'     => $request->busqueda ?? '',
            'restaurantes' => $restaurantesQuery->get(),
        ]);
    }

    // ── Planes turísticos públicos ────────────────────────────
    public function planesTuristicos(Request $request)
    {
        $query = \App\Models\PlanTuristico::with(['empresa','hotel','gastronomia','lugar','habitacion','evento'])
            ->where('publicado', true);

        if ($request->filled('tipo')) {
            $query->where('tipo_plan', $request->tipo);
        }
        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('titulo', 'like', "%$q%")->orWhere('descripcion', 'like', "%$q%"));
        }

        return view('pages.planes_turisticos', [
            'planes'      => $query->latest()->get(),
            'tipo_filtro' => $request->tipo ?? '',
            'busqueda'    => $request->busqueda ?? '',
        ]);
    }

    // ── Blog ─────────────────────────────────────────────────
    public function blog(Request $request)
    {
        $query = BlogPost::with(['empresa', 'usuario'])->where('publicado', true);

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('busqueda')) {
            $q = $request->busqueda;
            $query->where(fn($q2) => $q2->where('titulo', 'like', "%$q%")->orWhere('contenido', 'like', "%$q%"));
        }

        return view('pages.blog', [
            'posts'       => $query->latest('fecha_publicacion')->get(),
            'tipo_filtro' => $request->tipo ?? '',
            'busqueda'    => $request->busqueda ?? '',
        ]);
    }

    public function blogPost(BlogPost $post)
    {
        abort_if(!$post->publicado, 404);
        $relacionados = BlogPost::where('tipo', $post->tipo)
            ->where('publicado', true)
            ->where('id', '!=', $post->id)
            ->latest('fecha_publicacion')
            ->take(3)
            ->get();

        $stats = Calificacion::stats('blog', $post->id);
        $miCalificacion = auth()->check()
            ? Calificacion::where(['usuario_id' => auth()->id(), 'tipo' => 'blog', 'item_id' => $post->id])->first()
            : null;
        $reseñas = Calificacion::with('usuario')
            ->where('tipo', 'blog')->where('item_id', $post->id)
            ->whereNotNull('comentario')->latest()->take(10)->get();

        return view('pages.blog_post', compact('post', 'relacionados', 'stats', 'miCalificacion', 'reseñas'));
    }

    // ── Contacto ─────────────────────────────────────────────
    public function contacto()
    {
        return view('pages.contacto');
    }

    // ── Maps ─────────────────────────────────────────────────
    public function maps()
    {
        $lugares = Lugar::whereNotNull('latitud')->whereNotNull('longitud')->get()
            ->map(fn($l) => [
                'tipo'        => 'lugar',
                'id'          => $l->id,
                'nombre'      => $l->nombre,
                'descripcion' => \Str::limit($l->descripcion, 100),
                'ubicacion'   => $l->ubicacion,
                'categoria'   => $l->categoria,
                'lat'         => (float) $l->latitud,
                'lng'         => (float) $l->longitud,
                'url'         => route('lugares.detalle', $l),
                'precio'      => $l->precio_entrada > 0
                    ? '$' . number_format($l->precio_entrada, 0, ',', '.') . ' COP'
                    : 'Entrada gratuita',
            ]);

        $hoteles = Hotel::whereNotNull('latitud')->whereNotNull('longitud')
            ->where('disponibilidad', true)->get()
            ->map(fn($h) => [
                'tipo'        => 'hotel',
                'id'          => $h->id,
                'nombre'      => $h->nombre,
                'descripcion' => \Str::limit($h->descripcion, 100),
                'ubicacion'   => $h->ubicacion,
                'categoria'   => 'Hotel',
                'lat'         => (float) $h->latitud,
                'lng'         => (float) $h->longitud,
                'url'         => route('hoteles.detalle', $h),
                'precio'      => '$' . number_format($h->precio, 0, ',', '.') . ' COP/noche',
            ]);

        $puntos = collect($lugares)->merge(collect($hoteles))->values();
        return view('pages.maps', compact('puntos'));
    }

    public function mapsBuscar(Request $request)
    {
        $q = trim($request->get('q', ''));

        $lugares = Lugar::query();
        $hoteles = Hotel::where('disponibilidad', true);

        if ($q !== '') {
            $lugares->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('ubicacion', 'like', "%{$q}%")
                      ->orWhere('categoria', 'like', "%{$q}%")
                      ->orWhere('descripcion', 'like', "%{$q}%");
            });
            $hoteles->where(function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('ubicacion', 'like', "%{$q}%")
                      ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        $resultLugares = $lugares->get()->map(fn($l) => [
            'tipo'        => 'lugar',
            'id'          => $l->id,
            'nombre'      => $l->nombre,
            'descripcion' => \Str::limit($l->descripcion, 100),
            'ubicacion'   => $l->ubicacion,
            'categoria'   => $l->categoria,
            'lat'         => $l->latitud ? (float) $l->latitud : null,
            'lng'         => $l->longitud ? (float) $l->longitud : null,
            'url'         => route('lugares.detalle', $l),
            'precio'      => $l->precio_entrada > 0
                ? '$' . number_format($l->precio_entrada, 0, ',', '.') . ' COP'
                : 'Entrada gratuita',
        ]);

        $resultHoteles = $hoteles->get()->map(fn($h) => [
            'tipo'        => 'hotel',
            'id'          => $h->id,
            'nombre'      => $h->nombre,
            'descripcion' => \Str::limit($h->descripcion, 100),
            'ubicacion'   => $h->ubicacion,
            'categoria'   => 'Hotel',
            'lat'         => $h->latitud ? (float) $h->latitud : null,
            'lng'         => $h->longitud ? (float) $h->longitud : null,
            'url'         => route('hoteles.detalle', $h),
            'precio'      => '$' . number_format($h->precio, 0, ',', '.') . ' COP/noche',
        ]);

        return response()->json(
            $resultLugares->merge($resultHoteles)->values()
        );
    }

    // ── Reservas (requiere auth) ──────────────────────────────
    public function reservaForm(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        if (!$hotel->disponibilidad) {
            return redirect()->route('hoteles')->with('error', 'Hotel no disponible.');
        }

        $fechaEntrada = $request->fecha_entrada ?? now()->format('Y-m-d');
        $fechaSalida  = $request->fecha_salida  ?? now()->addDay()->format('Y-m-d');

        $habitacionesOcupadas = Reserva::where('hotel_id', $hotel->id)
            ->whereNotIn('estado', ['cancelada'])
            ->whereNotNull('habitacion_id')
            ->where('fecha_entrada', '<', $fechaSalida)
            ->where('fecha_salida',  '>', $fechaEntrada)
            ->pluck('habitacion_id');

        $habitaciones = Habitacion::where('hotel_id', $hotel->id)
            ->where('disponible', true)
            ->orderBy('precio_noche')
            ->get()
            ->map(function ($hab) use ($habitacionesOcupadas) {
                $hab->ocupada = $habitacionesOcupadas->contains($hab->id);
                return $hab;
            });

        return view('pages.reserva', compact('hotel', 'habitaciones'));
    }

    public function habitacionesDisponibles(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        $ocupadas = Reserva::where('hotel_id', $hotel->id)
            ->whereNotIn('estado', ['cancelada'])
            ->whereNotNull('habitacion_id')
            ->where('fecha_entrada', '<', $request->fecha_salida)
            ->where('fecha_salida',  '>', $request->fecha_entrada)
            ->pluck('habitacion_id');

        $habitaciones = Habitacion::where('hotel_id', $hotel->id)
            ->where('disponible', true)
            ->when($request->filled('num_personas'), fn($q) =>
                $q->where('capacidad_personas', '>=', (int) $request->num_personas)
            )
            ->orderBy('precio_noche')
            ->get()
            ->map(fn($h) => [
                'id'        => $h->id,
                'nombre'    => $h->nombre,
                'tipo'      => $h->tipo,
                'tipo_cama' => $h->tipo_cama,
                'capacidad' => (int) $h->capacidad_personas,
                'precio'    => (float) $h->precio_noche,
                'amenidades'=> $h->amenidades ?? [],
                'ocupada'   => $ocupadas->contains($h->id),
            ]);

        return response()->json($habitaciones);
    }

    public function reservaStore(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);

        if (!$hotel->disponibilidad) {
            return redirect()->route('hoteles')->with('error', 'Hotel no disponible.');
        }

        $request->validate([
            'fecha_entrada'  => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'   => ['required', 'date', 'after:fecha_entrada'],
            'num_personas'   => ['required', 'integer', 'min:1', 'max:' . ($hotel->capacidad ?? 9999)],
            'habitacion_id'  => ['nullable', 'exists:habitaciones,id'],
        ], [
            'fecha_entrada.after_or_equal' => 'La fecha de entrada no puede ser anterior a hoy.',
            'fecha_salida.after'           => 'La fecha de salida debe ser posterior a la entrada.',
            'num_personas.max'             => 'Excede la capacidad del hotel (' . ($hotel->capacidad ?? 'sin límite') . ' personas).',
        ]);

        $dias = now()->parse($request->fecha_entrada)->diffInDays($request->fecha_salida);

        $habitacion = null;
        if ($request->filled('habitacion_id')) {
            $habitacion = Habitacion::where('id', $request->habitacion_id)
                ->where('hotel_id', $hotel->id)
                ->where('disponible', true)
                ->firstOrFail();

            $conflicto = Reserva::where('habitacion_id', $habitacion->id)
                ->whereNotIn('estado', ['cancelada'])
                ->where('fecha_entrada', '<', $request->fecha_salida)
                ->where('fecha_salida',  '>', $request->fecha_entrada)
                ->exists();

            if ($conflicto) {
                return back()->with('error', 'La habitación seleccionada no está disponible para esas fechas.')->withInput();
            }

            $total = $dias * $habitacion->precio_noche;
        } else {
            $total = $dias * $hotel->precio;
        }

        if ($total <= 0) {
            return back()->with('error', 'Este hotel no tiene precio configurado. Contacta al administrador.');
        }

        $referencia = strtoupper('FZ-' . now()->format('ymd') . '-' . rand(10000, 99999));

        $reserva = Reserva::create([
            'usuario_id'      => Auth::id(),
            'hotel_id'        => $hotel->id,
            'habitacion_id'   => $habitacion?->id,
            'fecha_entrada'   => $request->fecha_entrada,
            'fecha_salida'    => $request->fecha_salida,
            'num_personas'    => $request->num_personas,
            'precio_total'    => $total,
            'estado'          => 'pendiente',
            'metodo_pago'     => null,
            'referencia_pago' => $referencia,
            'estado_pago'     => 'pendiente',
        ]);

        $amountCents = (int) round($total) * 100;
        $moneda      = 'COP';
        $cadena      = $referencia . $amountCents . $moneda . config('wompi.integrity_key');
        $signature   = hash('sha256', $cadena);

        $widgetUrl = 'https://checkout.wompi.co/p/?'
            . 'public-key='               . urlencode(config('wompi.public_key'))
            . '&currency='                . $moneda
            . '&amount-in-cents='         . $amountCents
            . '&reference='               . urlencode($referencia)
            . '&redirect-url='            . urlencode('https://sandbox.wompi.co/v1/void')
            . '&signature:integrity='     . $signature
            . '&customer-data:email='     . urlencode(Auth::user()->email)
            . '&customer-data:full-name=' . urlencode(Auth::user()->name);

        return redirect()->away($widgetUrl);
    }

    public function misReservas()
    {
        $reservas = Reserva::with('hotel')
            ->where('usuario_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.mis_reservas', [
            'reservas'      => $reservas,
            'pendientes'    => $reservas->where('estado', 'pendiente'),
            'confirmadas'   => $reservas->where('estado', 'confirmada'),
            'canceladas'    => $reservas->where('estado', 'cancelada'),
            'total_gastado' => $reservas->whereNotIn('estado', ['cancelada'])->sum('precio_total'),
        ]);
    }

    public function cancelarReserva(Reserva $reserva)
    {
        // Solo el dueño puede cancelar
        if ($reserva->usuario_id !== Auth::id()) {
            abort(403);
        }

        // Solo se pueden cancelar reservas pendientes
        if ($reserva->estado !== 'pendiente') {
            return back()->with('error', 'Solo puedes cancelar reservas pendientes.');
        }

        $reserva->update(['estado' => 'cancelada']);

        return back()->with('success', 'Reserva cancelada correctamente.');
    }

    // ── Favoritos (requiere auth) ─────────────────────────────
    public function favoritos()
    {
        $userId = Auth::id();
        $favs   = Favorito::where('usuario_id', $userId)->get();

        $hoteles = Hotel::whereIn('id', $favs->where('tipo', 'hotel')->pluck('item_id'))->get();
        $lugares = Lugar::whereIn('id', $favs->where('tipo', 'lugar')->pluck('item_id'))->get();

        return view('pages.favoritos', compact('hoteles', 'lugares'));
    }

    // ── Detalle empresa pública ───────────────────────────────
    public function detalleEmpresa(Empresa $empresa)
    {
        abort_if(!$empresa->aprobado, 404);

        $empresa->load([
            'imagenesActivas',
            'heroImagenes',
            'hoteles.habitaciones',
            'gastronomias' => fn($q) => $q->where('disponible_hoy', true),
        ]);

        return view('pages.detalle_empresa', compact('empresa'));
    }
}