@extends('layouts.app')

@section('title', 'FlowZone — Turismo en Ortega, Tolima')

@section('content')

@php
    $heroImgs          = \App\Models\HeroImage::where('activa', true)->where('seccion', 'hero')->orderBy('orden')->get();
    $categoriasLugares = \App\Models\Lugar::distinct()->pluck('categoria')->filter()->sort()->values();
    $categoriasEventos = \App\Models\Evento::distinct()->pluck('categoria')->filter()->sort()->values();
@endphp

{{-- HERO --}}
<section class="hero" id="hero-section" style="position:relative;overflow:hidden;">
    <div id="hero-carousel" style="position:absolute;inset:0;z-index:0;">
        @if($heroImgs->count() > 0)
            @foreach($heroImgs as $i => $img)
            @php $imgUrl = str_starts_with($img->url, 'http') ? $img->url : asset('storage/' . $img->url); @endphp
            <div class="hero-slide" style="position:absolute;inset:0;background-image:url('{{ $imgUrl }}');background-size:cover;background-position:center;opacity:{{ $i === 0 ? '1' : '0' }};transition:opacity 1.2s ease-in-out;"></div>
            @endforeach
        @else
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);"></div>
        @endif
    </div>
    <div class="hero-overlay" style="position:absolute;inset:0;z-index:1;background:linear-gradient(135deg,rgba(10,40,20,.65) 0%,rgba(30,80,50,.35) 100%);"></div>
    @if($heroImgs->count() > 1)
    <div id="hero-dots" style="position:absolute;bottom:1.5rem;left:50%;transform:translateX(-50%);z-index:10;display:flex;gap:.5rem;">
        @foreach($heroImgs as $i => $img)
        <button onclick="goToSlide({{ $i }})" class="hero-dot" style="width:{{ $i === 0 ? '28px' : '10px' }};height:10px;border-radius:5px;border:none;background:{{ $i === 0 ? '#fff' : 'rgba(255,255,255,.45)' }};cursor:pointer;transition:all .3s;padding:0;"></button>
        @endforeach
    </div>
    @endif
    <div class="container" style="position:relative;z-index:2;">
        <div class="hero-content fade-in">
            <div class="hero-eyebrow"><i class="fa-solid fa-leaf"></i> Ortega, Tolima — Colombia</div>
            <h1>Descubre la <span>Naturaleza</span><br>de Ortega</h1>
            <p>Explora los paisajes, sabores y tradiciones del corazón del Tolima. Una experiencia auténtica de turismo rural y cultural.</p>
            <form class="hero-search" id="hero-search-form" action="{{ route('lugares') }}" method="GET">
                <input type="text" name="busqueda" placeholder="¿Qué quieres explorar?">
                <select name="categoria" id="hero-categoria" onchange="actualizarDestino(this)">
                    <option value="" data-ruta="{{ route('lugares') }}" data-nombre="busqueda"> Todas las categorías</option>
                    @foreach($categoriasLugares as $cat)
                        <option value="{{ $cat }}" data-ruta="{{ route('lugares') }}" data-nombre="busqueda"> {{ $cat }}</option>
                    @endforeach
                    <option value="" data-ruta="{{ route('hoteles') }}" data-nombre="busqueda" data-seccion="hoteles"> Hoteles</option>
                    <option value="" data-ruta="{{ route('eventos') }}" data-nombre="busqueda" data-seccion="eventos"> Todos los eventos</option>
                    @foreach($categoriasEventos as $cat)
                        <option value="{{ $cat }}" data-ruta="{{ route('eventos') }}" data-nombre="busqueda"> {{ $cat }}</option>
                    @endforeach
                    <option value="" data-ruta="{{ route('gastronomia') }}" data-nombre="busqueda" data-seccion="gastronomia"> Gastronomía</option>
                </select>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            </form>
            <div class="hero-badges">
                <span class="hero-badge"><i class="fa-solid fa-map-pin"></i> Lugares únicos</span>
                <span class="hero-badge"><i class="fa-solid fa-hotel"></i> Hoteles top</span>
                <span class="hero-badge"><i class="fa-solid fa-utensils"></i> Gastronomía local</span>
                <span class="hero-badge"><i class="fa-solid fa-calendar"></i> Eventos culturales</span>
            </div>
        </div>
    </div>
    <div class="hero-float-cards" style="position:relative;z-index:2;">
        <div class="hero-float-card"><i class="fa-solid fa-map-location-dot"></i><div><strong>Lugares únicos</strong><small style="display:block;opacity:.7">Naturaleza y cultura</small></div></div>
        <div class="hero-float-card"><i class="fa-solid fa-star"></i><div><strong>Experiencias top</strong><small style="display:block;opacity:.7">Calificadas por viajeros</small></div></div>
    </div>
    <div class="hero-scroll" style="position:relative;z-index:2;"><i class="fa-solid fa-chevron-down"></i><span>Explorar</span></div>
</section>

{{-- STATS --}}
<div style="background:var(--green-900);">
    <div class="container">
        <div class="stats-strip" style="border-radius:0;background:transparent;box-shadow:none;">
            <div class="stats-strip-item"><h3 style="color:#4ade80;">{{ $totalLugares ?? '20+' }}</h3><p style="color:rgba(255,255,255,.6);">Lugares turísticos</p></div>
            <div class="stats-strip-item"><h3 style="color:#4ade80;">{{ $totalHoteles ?? '10+' }}</h3><p style="color:rgba(255,255,255,.6);">Hoteles y hospedajes</p></div>
            <div class="stats-strip-item"><h3 style="color:#4ade80;">{{ $totalEventos ?? '15+' }}</h3><p style="color:rgba(255,255,255,.6);">Eventos al año</p></div>
            <div class="stats-strip-item"><h3 style="color:#4ade80;">500+</h3><p style="color:rgba(255,255,255,.6);">Visitantes felices</p></div>
        </div>
    </div>
</div>

{{-- DESTINOS --}}
<section class="section" style="background:#fff;">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label" style="color:var(--green-700);font-weight:700;">
                    <i class="fa-solid fa-map-pin"></i> Destinos
                </p>
                <h2 class="section-title" style="font-size:2rem;">
                    Lugares que <span style="color:var(--green-700);">Debes Visitar</span>
                </h2>
                <p style="color:var(--gray-500);font-size:.92rem;margin-top:.3rem;">Los rincones más especiales de Ortega, Tolima</p>
            </div>
            <a href="{{ route('lugares') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @forelse($lugares_destacados ?? [] as $lugar)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($lugar->imagen)
                        @php $imgSrc = str_starts_with($lugar->imagen,'http') ? $lugar->imagen : asset('storage/'.$lugar->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $lugar->nombre }}" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-mountain-sun\'></i></div>'">
                    @else<div class="card-img-fallback"><i class="fa-solid fa-mountain-sun"></i></div>@endif
                    @if($lugar->categoria)<span class="card-badge">{{ $lugar->categoria }}</span>@endif
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $lugar->nombre }}</h3>
                    <p class="card-meta"><i class="fa-solid fa-location-dot"></i> {{ $lugar->ubicacion ?? 'Ortega, Tolima' }}</p>
                    <p class="card-desc">{{ Str::limit($lugar->descripcion, 80) }}</p>
                    <div class="card-actions">
                        <a href="{{ route('lugares.detalle', $lugar) }}" class="btn btn-outline btn-sm">Ver más</a>
                        <a href="{{ route('hoteles') }}" class="btn btn-primary btn-sm">Ver Hoteles</a>
                    </div>
                </div>
            </div>
            @empty
            <p style="color:var(--gray-400);grid-column:1/-1;text-align:center;padding:3rem;">No hay lugares disponibles aún.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- BANNER INTERMEDIO --}}
<div style="background:linear-gradient(135deg,#0f3320 0%,#1a5c35 50%,#0f3320 100%);padding:4rem 0;text-align:center;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;opacity:.05;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(74,222,128,.15);border:1px solid rgba(74,222,128,.3);border-radius:2rem;padding:.4rem 1.2rem;margin-bottom:1rem;">
            <i class="fa-solid fa-compass" style="color:#4ade80;font-size:.8rem;"></i>
            <span style="color:#4ade80;font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;">Experiencias únicas</span>
        </div>
        <h2 style="color:#fff;font-size:2.2rem;font-weight:800;margin-bottom:.75rem;line-height:1.2;">
            Ortega, Tolima <span style="color:#4ade80;">te espera</span>
        </h2>
        <p style="color:rgba(255,255,255,.65);max-width:520px;margin:0 auto 2rem;font-size:.95rem;line-height:1.7;">
            Naturaleza, cultura y gastronomía en el corazón del Tolima. Una aventura que no olvidarás.
        </p>
        <div style="display:flex;justify-content:center;gap:2rem;flex-wrap:wrap;">
            <div style="text-align:center;">
                <i class="fa-solid fa-tree" style="color:#4ade80;font-size:1.5rem;display:block;margin-bottom:.4rem;"></i>
                <span style="color:rgba(255,255,255,.7);font-size:.82rem;">Ecoturismo</span>
            </div>
            <div style="text-align:center;">
                <i class="fa-solid fa-utensils" style="color:#4ade80;font-size:1.5rem;display:block;margin-bottom:.4rem;"></i>
                <span style="color:rgba(255,255,255,.7);font-size:.82rem;">Gastronomía</span>
            </div>
            <div style="text-align:center;">
                <i class="fa-solid fa-guitar" style="color:#4ade80;font-size:1.5rem;display:block;margin-bottom:.4rem;"></i>
                <span style="color:rgba(255,255,255,.7);font-size:.82rem;">Cultura</span>
            </div>
            <div style="text-align:center;">
                <i class="fa-solid fa-water" style="color:#4ade80;font-size:1.5rem;display:block;margin-bottom:.4rem;"></i>
                <span style="color:rgba(255,255,255,.7);font-size:.82rem;">Ríos y cascadas</span>
            </div>
        </div>
    </div>
</div>

{{-- EVENTOS --}}
<section class="section" style="background:#f8fafc;">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label" style="color:#6366f1;font-weight:700;">
                    <i class="fa-solid fa-compass"></i> Experiencias
                </p>
                <h2 class="section-title" style="font-size:2rem;">
                    Vive Ortega <span style="color:#6366f1;">al Máximo</span>
                </h2>
                <p style="color:var(--gray-500);font-size:.92rem;margin-top:.3rem;">Eventos y actividades para todos los gustos</p>
            </div>
            <a href="{{ route('eventos') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @forelse($eventos_proximos ?? [] as $evento)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($evento->imagen)
                        @php $imgSrc = str_starts_with($evento->imagen,'http') ? $evento->imagen : asset('storage/'.$evento->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $evento->nombre }}" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-calendar-days\'></i></div>'">
                    @else<div class="card-img-fallback"><i class="fa-solid fa-calendar-days"></i></div>@endif
                    @if($evento->categoria)<span class="card-badge">{{ $evento->categoria }}</span>@endif
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $evento->nombre }}</h3>
                    <p class="card-meta"><i class="fa-solid fa-calendar fa-xs"></i> {{ $evento->fecha->format('d/m/Y') }}@if($evento->hora) &nbsp;·&nbsp; {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}@endif</p>
                    @if($evento->ubicacion)<p class="card-meta"><i class="fa-solid fa-location-dot fa-xs"></i> {{ $evento->ubicacion }}</p>@endif
                    @if($evento->precio > 0)
                        <p class="card-meta" style="color:var(--green-700);font-weight:700;"><i class="fa-solid fa-ticket fa-xs"></i> ${{ number_format($evento->precio, 0, ',', '.') }} COP</p>
                    @else
                        <p class="card-meta" style="color:var(--success);"><i class="fa-solid fa-circle-check fa-xs"></i> Entrada gratuita</p>
                    @endif
                    @if($evento->descripcion)<p class="card-desc">{{ Str::limit($evento->descripcion, 100) }}</p>@endif
                    <div class="card-actions"><a href="{{ route('eventos.detalle', $evento) }}" class="btn btn-primary btn-sm">Ver detalles <i class="fa-solid fa-arrow-right fa-xs"></i></a></div>
                </div>
            </article>
            @empty
            <p style="color:var(--gray-400);grid-column:1/-1;text-align:center;padding:3rem;">No hay eventos próximos programados.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- HOTELES --}}
<section class="section" style="background:#fff;">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label" style="color:#f59e0b;font-weight:700;">
                    <i class="fa-solid fa-hotel"></i> Alojamiento
                </p>
                <h2 class="section-title" style="font-size:2rem;">
                    Hoteles <span style="color:#f59e0b;">Recomendados</span>
                </h2>
                <p style="color:var(--gray-500);font-size:.92rem;margin-top:.3rem;">Descansa y disfruta con la mejor comodidad</p>
            </div>
            <a href="{{ route('hoteles') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @forelse($hoteles_destacados ?? [] as $hotel)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($hotel->imagen)
                        @php $imgSrc = str_starts_with($hotel->imagen,'http') ? $hotel->imagen : asset('storage/'.$hotel->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $hotel->nombre }}" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-hotel\'></i></div>'">
                    @else<div class="card-img-fallback"><i class="fa-solid fa-hotel"></i></div>@endif
                    <span class="card-badge card-badge-accent"><i class="fa-solid fa-star fa-xs"></i> Destacado</span>
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $hotel->nombre }}</h3>
                    <p class="card-meta"><i class="fa-solid fa-location-dot"></i> {{ $hotel->ubicacion ?? 'Ortega, Tolima' }}</p>
                    @if($hotel->precio)
                        <p style="font-size:.9rem;color:var(--green-700);font-weight:700;margin-bottom:.5rem;"><i class="fa-solid fa-tag"></i> Desde ${{ number_format($hotel->precio, 0, ',', '.') }}/noche</p>
                    @endif
                    <div class="card-actions">
                        <a href="{{ route('hoteles.detalle', $hotel) }}" class="btn btn-outline btn-sm">Ver más</a>
                        @auth<a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}" class="btn btn-primary btn-sm">Reservar</a>
                        @else<a href="{{ route('login') }}" class="btn btn-primary btn-sm">Reservar</a>@endauth
                    </div>
                </div>
            </div>
            @empty
            <p style="color:var(--gray-400);grid-column:1/-1;text-align:center;padding:3rem;">No hay hoteles disponibles aún.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- PLANES TURÍSTICOS --}}
@php $planes_home = \App\Models\PlanTuristico::where('publicado', true)->latest()->take(3)->get(); @endphp
@if($planes_home->count() > 0)
<section class="section" style="background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-top:3px solid #86efac;">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label" style="color:var(--green-700);font-weight:700;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Planes especiales
                </p>
                <h2 class="section-title" style="font-size:2rem;">
                    Planes Turísticos <span style="color:var(--green-700);">con Descuento</span>
                </h2>
                <p style="color:var(--gray-500);font-size:.92rem;margin-top:.3rem;">Combos exclusivos con 20% de ahorro garantizado</p>
            </div>
            <a href="{{ route('planes.publico') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @foreach($planes_home as $plan)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($plan->imagen ?? null)
                        @php $imgSrc = str_starts_with($plan->imagen,'http') ? $plan->imagen : asset('storage/'.$plan->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $plan->titulo }}" loading="lazy">
                    @else<div class="card-img-fallback"><i class="fa-solid fa-map-location-dot"></i></div>@endif
                    <span class="card-badge" style="background:#ef4444;">-20% DCTO</span>
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $plan->titulo }}</h3>
                    @if($plan->descripcion ?? null)<p class="card-desc">{{ Str::limit($plan->descripcion, 80) }}</p>@endif
                    @if($plan->precio_total ?? null)
                    <p style="font-size:.9rem;color:var(--green-700);font-weight:700;margin-bottom:.5rem;">
                        <i class="fa-solid fa-tag"></i>
                        <span style="text-decoration:line-through;color:var(--gray-400);font-weight:400;">${{ number_format($plan->precio_total, 0, ',', '.') }}</span>
                        ${{ number_format($plan->precio_total * 0.8, 0, ',', '.') }} COP
                    </p>
                    @endif
                    <div class="card-actions"><a href="{{ route('planes.publico') }}" class="btn btn-primary btn-sm">Ver plan</a></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- BLOG --}}
@if(isset($blog_recientes) && $blog_recientes->count() > 0)
<section class="section" style="background:#f8fafc;">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label" style="color:#0ea5e9;font-weight:700;">
                    <i class="fa-solid fa-newspaper"></i> Blog
                </p>
                <h2 class="section-title" style="font-size:2rem;">
                    Últimas <span style="color:#0ea5e9;">Noticias</span>
                </h2>
                <p style="color:var(--gray-500);font-size:.92rem;margin-top:.3rem;">Historias, tips y novedades de Ortega</p>
            </div>
            <a href="{{ route('blog') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="blog-grid">
            @foreach($blog_recientes as $post)
            @php $postImg = $post->imagen ? (str_starts_with($post->imagen,'http') ? $post->imagen : asset('storage/'.$post->imagen)) : null; @endphp
            <div class="blog-card animate-on-scroll">
                <div class="card-img-wrap" style="width:240px;flex-shrink:0;height:auto;">
                    @if($postImg)
                        <img src="{{ $postImg }}" alt="{{ $post->titulo }}" loading="lazy" style="height:100%;min-height:180px;" onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-newspaper\'></i></div>'">
                    @else<div class="card-img-fallback" style="min-height:180px;"><i class="fa-solid fa-newspaper"></i></div>@endif
                </div>
                <div class="card-content" style="flex:1;">
                    <p class="card-meta"><i class="fa-solid fa-calendar"></i> {{ $post->fecha_publicacion?->format('d M Y') ?? $post->created_at->format('d M Y') }}</p>
                    <h3>{{ $post->titulo }}</h3>
                    <p style="font-size:.875rem;color:var(--gray-600);line-height:1.5;margin-bottom:1rem;">{{ Str::limit(strip_tags($post->contenido), 100) }}</p>
                    <a href="{{ route('blog.post', $post->slug) }}" class="btn btn-outline btn-sm">Leer más <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- GALERÍA DE CARDS --}}
@php $cards = \App\Models\HeroImage::where('activa', true)->where('seccion', 'cards')->orderBy('orden')->get(); @endphp
@if($cards->count() > 0)
<section class="section" style="background:var(--green-900);">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label" style="color:#4ade80;font-weight:700;">
                    <i class="fa-solid fa-camera"></i> Fotografías
                </p>
                <h2 class="section-title" style="color:#fff;font-size:2rem;">
                    Galería de <span style="color:#4ade80;">Paisajes</span>
                </h2>
                <p style="color:rgba(255,255,255,.55);font-size:.92rem;margin-top:.3rem;">La belleza natural de Ortega en imágenes</p>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">
            @foreach($cards as $card)
            @php $imgSrc = str_starts_with($card->url,'http') ? $card->url : asset('storage/'.$card->url); @endphp
            <div style="border-radius:var(--radius-lg);overflow:hidden;aspect-ratio:4/3;position:relative;box-shadow:0 4px 20px rgba(0,0,0,.3);">
                <img src="{{ $imgSrc }}" alt="{{ $card->titulo ?? 'Ortega, Tolima' }}"
                     style="width:100%;height:100%;object-fit:cover;transition:transform .4s ease;"
                     onmouseover="this.style.transform='scale(1.05)'"
                     onmouseout="this.style.transform='scale(1)'">
                @if($card->titulo)
                <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 100%);padding:1rem .85rem .75rem;">
                    <p style="color:#fff;font-size:.85rem;font-weight:600;margin:0;">{{ $card->titulo }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA FINAL --}}
<section class="section" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);text-align:center;">
    <div class="container">
        <p class="section-label" style="color:#4ade80;justify-content:center;font-weight:700;"><i class="fa-solid fa-compass"></i> ¿Listo para explorar?</p>
        <h2 class="section-title" style="color:#fff;font-size:2.2rem;margin-bottom:1rem;">
            Planifica tu Visita<br><span style="color:#4ade80;">a Ortega</span>
        </h2>
        <p style="color:rgba(255,255,255,.7);max-width:500px;margin:0 auto 2rem;line-height:1.7;">Descubre todo lo que Ortega, Tolima tiene para ofrecerte. Naturaleza, cultura, gastronomía y hospitalidad en un solo lugar.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('lugares') }}" class="btn btn-white btn-lg"><i class="fa-solid fa-map-pin"></i> Explorar Lugares</a>
            <a href="{{ route('contacto') }}" class="btn btn-glass btn-lg"><i class="fa-solid fa-envelope"></i> Contáctanos</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
const rutasPorPalabra = {
    'hotel': '{{ route('hoteles') }}', 'hospedaje': '{{ route('hoteles') }}',
    'alojamiento': '{{ route('hoteles') }}', 'finca': '{{ route('hoteles') }}',
    'cabaña': '{{ route('hoteles') }}', 'cabana': '{{ route('hoteles') }}',
    'posada': '{{ route('hoteles') }}', 'evento': '{{ route('eventos') }}',
    'festival': '{{ route('eventos') }}', 'feria': '{{ route('eventos') }}',
    'concierto': '{{ route('eventos') }}', 'restaurante': '{{ route('gastronomia') }}',
    'comida': '{{ route('gastronomia') }}', 'gastronomia': '{{ route('gastronomia') }}',
    'gastronomía': '{{ route('gastronomia') }}', 'plato': '{{ route('gastronomia') }}',
    'cocina': '{{ route('gastronomia') }}', 'lugar': '{{ route('lugares') }}',
    'turismo': '{{ route('lugares') }}', 'parque': '{{ route('lugares') }}',
    'cascada': '{{ route('lugares') }}', 'rio': '{{ route('lugares') }}',
    'río': '{{ route('lugares') }}', 'reserva': '{{ route('lugares') }}',
};
function actualizarDestino(select) {
    const opt = select.options[select.selectedIndex];
    const ruta = opt.getAttribute('data-ruta');
    const form = document.getElementById('hero-search-form');
    if (ruta) form.action = ruta;
}
document.getElementById('hero-search-form').addEventListener('submit', function(e) {
    const input = this.querySelector('input[name="busqueda"]').value.toLowerCase().trim();
    const select = document.getElementById('hero-categoria');
    const optRuta = select.options[select.selectedIndex].getAttribute('data-ruta');
    const rutaLugares = '{{ route('lugares') }}';
    if (optRuta && optRuta !== rutaLugares) { this.action = optRuta; return; }
    for (const [palabra, ruta] of Object.entries(rutasPorPalabra)) {
        if (input.includes(palabra)) { this.action = ruta; return; }
    }
    this.action = rutaLugares;
});
@if($heroImgs->count() > 1)
const slides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.hero-dot');
let current = 0, timer = null;
function goToSlide(idx) {
    slides[current].style.opacity = '0';
    dots[current].style.width = '10px';
    dots[current].style.background = 'rgba(255,255,255,.45)';
    current = idx;
    slides[current].style.opacity = '1';
    dots[current].style.width = '28px';
    dots[current].style.background = '#fff';
}
function nextSlide() { goToSlide((current + 1) % slides.length); }
timer = setInterval(nextSlide, 5000);
document.getElementById('hero-section').addEventListener('mouseenter', () => clearInterval(timer));
document.getElementById('hero-section').addEventListener('mouseleave', () => { timer = setInterval(nextSlide, 5000); });
@endif
</script>
@endpush