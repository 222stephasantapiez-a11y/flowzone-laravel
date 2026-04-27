<?php
 
namespace App\Http\Controllers;
 
use App\Models\Hotel;
use App\Models\Lugar;
use App\Models\Evento;
use App\Models\Gastronomia;
use App\Models\Reserva;
use App\Models\BlogPost;
use App\Models\Calificacion;
use App\Models\Favorito;
use App\Models\HeroImage;
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
            'heroImages'         => HeroImage::activas()->seccion('hero')->get(),
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
         'hoteles' => $query->latest()->paginate(5)->withQueryString(),
            'busqueda'  => $request->busqueda ?? '',
            'precio_max'=> $request->precio_max ?? '',
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
         'lugares' => $query->latest()->paginate(5)->withQueryString(),
            'categorias'      => Lugar::distinct()->pluck('categoria')->filter()->sort()->values(),
            'categoria_filtro'=> $request->categoria ?? '',
            'busqueda'        => $request->busqueda ?? '',
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
            'eventos'         => $query->orderBy('fecha')->get(),
            'categorias'      => Evento::distinct()->pluck('categoria')->filter()->sort()->values(),
            'categoria_filtro'=> $request->categoria ?? '',
            'busqueda'        => $request->busqueda ?? '',
        ]);
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
 
        return view('pages.gastronomia', [
            'platos'     => $query->latest()->get(),
            'tipos'      => Gastronomia::distinct()->pluck('tipo')->filter()->sort()->values(),
            'tipo_filtro'=> $request->tipo ?? '',
            'busqueda'   => $request->busqueda ?? '',
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
            'posts'      => $query->latest('fecha_publicacion')->paginate(9),
            'tipo_filtro'=> $request->tipo ?? '',
            'busqueda'   => $request->busqueda ?? '',
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
 
        if ($lugares->isNotEmpty() && $hoteles->isNotEmpty()) {
            $puntos = $lugares->merge($hoteles)->values();
        } elseif ($lugares->isNotEmpty()) {
            $puntos = $lugares;
        } elseif ($hoteles->isNotEmpty()) {
            $puntos = $hoteles;
        } else {
            $puntos = collect();
        }
 
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
 
        return view('pages.reserva', ['hotel' => $hotel, 'error' => null, 'success' => null]);
    }
 
    public function reservaStore(Request $request)
    {
        $hotel = Hotel::findOrFail($request->hotel_id);
 
        $request->validate([
            'fecha_entrada'  => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'   => ['required', 'date', 'after:fecha_entrada'],
            'num_personas'   => ['required', 'integer', 'min:1', 'max:' . $hotel->capacidad],
            'metodo_pago'    => ['required', 'in:nequi,bancolombia_pse,tarjeta'],
            'nequi_numero'   => ['required_if:metodo_pago,nequi', 'nullable', 'digits:10'],
            'tarjeta_numero' => ['required_if:metodo_pago,tarjeta', 'nullable', 'digits_between:16,19'],
            'tarjeta_nombre' => ['required_if:metodo_pago,tarjeta', 'nullable', 'string', 'max:100'],
            'tarjeta_expiry' => ['required_if:metodo_pago,tarjeta', 'nullable', 'regex:/^\d{2}\/\d{2}$/'],
            'tarjeta_cvv'    => ['required_if:metodo_pago,tarjeta', 'nullable', 'digits:3'],
        ], [
            'fecha_entrada.after_or_equal'  => 'La fecha de entrada no puede ser anterior a hoy.',
            'fecha_salida.after'            => 'La fecha de salida debe ser posterior a la entrada.',
            'num_personas.max'              => 'Excede la capacidad del hotel (' . $hotel->capacidad . ' personas).',
            'metodo_pago.required'          => 'Selecciona un método de pago.',
            'nequi_numero.required_if'      => 'Ingresa tu número de celular Nequi.',
            'nequi_numero.digits'           => 'El número Nequi debe tener 10 dígitos.',
            'tarjeta_numero.required_if'    => 'Ingresa el número de tarjeta.',
            'tarjeta_nombre.required_if'    => 'Ingresa el nombre del titular.',
            'tarjeta_expiry.required_if'    => 'Ingresa la fecha de vencimiento (MM/AA).',
            'tarjeta_cvv.required_if'       => 'Ingresa el CVV.',
        ]);
 
        $dias  = now()->parse($request->fecha_entrada)->diffInDays($request->fecha_salida);
        $total = $dias * $hotel->precio;
 
        // ── Simulación de pago ────────────────────────────────────────
        // Nequi con número "0000000000" → falla (demo de rechazo)
        $pagoExitoso = !($request->metodo_pago === 'nequi' && $request->nequi_numero === '0000000000');
        $referencia  = strtoupper('FZ-' . now()->format('ymd') . '-' . rand(10000, 99999));
        $estadoPago  = $pagoExitoso ? 'pagado' : 'fallido';
 
        Reserva::create([
            'usuario_id'      => Auth::id(),
            'hotel_id'        => $hotel->id,
            'fecha_entrada'   => $request->fecha_entrada,
            'fecha_salida'    => $request->fecha_salida,
            'num_personas'    => $request->num_personas,
            'precio_total'    => $total,
            'estado'          => $pagoExitoso ? 'confirmada' : 'pendiente',
            'metodo_pago'     => $request->metodo_pago,
            'referencia_pago' => $referencia,
            'estado_pago'     => $estadoPago,
        ]);
 
        if (!$pagoExitoso) {
            return redirect()->route('mis-reservas')
                ->with('error', '⚠️ El pago fue rechazado. Tu reserva quedó en estado pendiente. Ref: ' . $referencia);
        }
 
        $metodos = [
            'nequi'           => 'Nequi',
            'bancolombia_pse' => 'Bancolombia PSE',
            'tarjeta'         => 'Tarjeta',
        ];
 
        return redirect()->route('mis-reservas')
            ->with('success', '✅ ¡Reserva confirmada! Pagado con ' . ($metodos[$request->metodo_pago] ?? '') . '. Total: $' . number_format($total, 0, ',', '.') . ' COP · Ref: ' . $referencia);
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
        $userId = Auth::id();
 
        $favs = Favorito::where('usuario_id', $userId)->get();
 
        $hotelIds = $favs->where('tipo', 'hotel')->pluck('item_id');
        $lugarIds = $favs->where('tipo', 'lugar')->pluck('item_id');
 
        $hoteles = Hotel::whereIn('id', $hotelIds)->get();
        $lugares = Lugar::whereIn('id', $lugarIds)->get();
 
        return view('pages.favoritos', compact('hoteles', 'lugares'));
    }
}
 