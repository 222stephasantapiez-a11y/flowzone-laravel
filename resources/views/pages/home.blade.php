@extends('layouts.app')

@section('title', 'FlowZone — Turismo en Ortega, Tolima')

@section('content')

@php
    $heroImg = \App\Models\HeroImage::where('activa', true)->where('seccion', 'hero')->orderBy('orden')->first();
    $heroBg = $heroImg ? $heroImg->public_url : null;
@endphp

{{-- ═══════════════════════════════════════════════════════
     HERO — Full screen landing
═══════════════════════════════════════════════════════ --}}


<section class="hero" @if($heroBg) style="background-image: url('{{ $heroBg }}')" @else style="background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 100%)" @endif>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content fade-in">
            <div class="hero-eyebrow">
                <i class="fa-solid fa-leaf"></i> Ortega, Tolima — Colombia
            </div>
            <h1>Descubre la <span>Naturaleza</span><br>de Ortega</h1>
            <p>Explora los paisajes, sabores y tradiciones del corazón del Tolima. Una experiencia auténtica de turismo rural y cultural.</p>

            <form class="hero-search" action="{{ route('lugares') }}" method="GET">
                <input type="text" name="busqueda" placeholder="¿Qué quieres explorar?">
                <select name="categoria">
                    <option value="">Todas las categorías</option>
                    <option value="naturaleza">Naturaleza</option>
                    <option value="cultura">Cultura</option>
                    <option value="aventura">Aventura</option>
                    <option value="gastronomia">Gastronomía</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Buscar
                </button>
            </form>

            <div class="hero-badges">
                <span class="hero-badge"><i class="fa-solid fa-map-pin"></i> Lugares únicos</span>
                <span class="hero-badge"><i class="fa-solid fa-hotel"></i> Hoteles top</span>
                <span class="hero-badge"><i class="fa-solid fa-utensils"></i> Gastronomía local</span>
                <span class="hero-badge"><i class="fa-solid fa-calendar"></i> Eventos culturales</span>
            </div>
        </div>
    </div>

    <div class="hero-float-cards">
        <div class="hero-float-card">
            <i class="fa-solid fa-map-location-dot"></i>
            <div>
                <strong>Lugares únicos</strong>
                <small style="display:block;opacity:.7">Naturaleza y cultura</small>
            </div>
        </div>
        <div class="hero-float-card">
            <i class="fa-solid fa-star"></i>
            <div>
                <strong>Experiencias top</strong>
                <small style="display:block;opacity:.7">Calificadas por viajeros</small>
            </div>
        </div>
    </div>

    <div class="hero-scroll">
        <i class="fa-solid fa-chevron-down"></i>
        <span>Explorar</span>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     STATS STRIP
═══════════════════════════════════════════════════════ --}}
<div class="container">
    <div class="stats-strip">
        <div class="stats-strip-item">
            <h3>{{ $totalLugares ?? '20+' }}</h3>
            <p>Lugares turísticos</p>
        </div>
        <div class="stats-strip-item">
            <h3>{{ $totalHoteles ?? '10+' }}</h3>
            <p>Hoteles y hospedajes</p>
        </div>
        <div class="stats-strip-item">
            <h3>{{ $totalEventos ?? '15+' }}</h3>
            <p>Eventos al año</p>
        </div>
        <div class="stats-strip-item">
            <h3>500+</h3>
            <p>Visitantes felices</p>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     DESTINOS DESTACADOS
═══════════════════════════════════════════════════════ --}}
<section class="section" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label"><i class="fa-solid fa-map-pin"></i> Destinos</p>
                <h2 class="section-title">Lugares que Debes Visitar</h2>
            </div>
            <a href="{{ route('lugares') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @forelse($lugares_destacados ?? [] as $lugar)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($lugar->imagen)
                        <img src="{{ asset('storage/' . $lugar->imagen) }}" alt="{{ $lugar->nombre }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--green-800),var(--green-600));display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-mountain-sun" style="font-size:3rem;color:rgba(255,255,255,.4);"></i>
                        </div>
                    @endif
                    @if($lugar->categoria)
                        <span class="card-badge">{{ $lugar->categoria }}</span>
                    @endif
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $lugar->nombre }}</h3>
                    <p class="card-meta"><i class="fa-solid fa-location-dot"></i> {{ $lugar->ubicacion ?? 'Ortega, Tolima' }}</p>
                    <p class="card-desc" style="font-size:.875rem;color:var(--gray-600);line-height:1.5;">{{ Str::limit($lugar->descripcion, 80) }}</p>
                    <div class="card-actions">
                        <a href="{{ route('lugares.detalle', $lugar) }}" class="btn btn-outline btn-sm">Ver más</a>
                        @auth
                            <a href="{{ route('reservar') }}" class="btn btn-primary btn-sm">Reservar</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Reservar</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <p style="color:var(--gray-400);grid-column:1/-1;text-align:center;padding:3rem;">No hay lugares disponibles aún.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     EXPERIENCIAS
═══════════════════════════════════════════════════════ --}}
<section class="section" style="background: var(--gray-50);">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label">
                    <i class="fa-solid fa-compass"></i> Experiencias
                </p>
                <h2 class="section-title">Vive Ortega al Máximo</h2>
            </div>
        </div>

        <div class="experience-grid">

            <!-- Card grande -->
            <div class="experience-card tall">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee"
                     alt="Ecoturismo Rural">

                <div class="experience-card-overlay">
                    <span class="exp-tag">Naturaleza</span>
                    <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:.3rem;">
                        Ecoturismo Rural
                    </h3>
                    <p style="font-size:.85rem;opacity:.8;">
                        Senderos, ríos y paisajes únicos del Tolima
                    </p>
                </div>
            </div>

            <!-- Card comida -->
            <div class="experience-card">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836"
                     alt="Sabores Locales">

                <div class="experience-card-overlay">
                    <span class="exp-tag">Gastronomía</span>
                    <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:.3rem;">
                        Sabores Locales
                    </h3>
                    <p style="font-size:.82rem;opacity:.8;">
                        Cocina tradicional tolimense
                    </p>
                </div>
            </div>

            <!-- Card cultura -->
            <div class="experience-card">
                <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30"
                     alt="Festivales">

                <div class="experience-card-overlay">
                    <span class="exp-tag">Cultura</span>
                    <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:.3rem;">
                        Festivales y Eventos
                    </h3>
                    <p style="font-size:.82rem;opacity:.8;">
                        Tradiciones y celebraciones locales
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     HOTELES RECOMENDADOS
═══════════════════════════════════════════════════════ --}}
<section class="section" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label"><i class="fa-solid fa-hotel"></i> Alojamiento</p>
                <h2 class="section-title">Hoteles Recomendados</h2>
            </div>
            <a href="{{ route('hoteles') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @forelse($hoteles_destacados ?? [] as $hotel)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($hotel->imagen)
                        <img src="{{ asset('storage/' . $hotel->imagen) }}" alt="{{ $hotel->nombre }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--green-700),var(--green-500));display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-hotel" style="font-size:3rem;color:rgba(255,255,255,.4);"></i>
                        </div>
                    @endif
                    <span class="card-badge card-badge-accent"><i class="fa-solid fa-star fa-xs"></i> Destacado</span>
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $hotel->nombre }}</h3>
                    <p class="card-meta"><i class="fa-solid fa-location-dot"></i> {{ $hotel->ubicacion ?? 'Ortega, Tolima' }}</p>
                    @if($hotel->precio)
                        <p style="font-size:.9rem;color:var(--green-700);font-weight:700;margin-bottom:.5rem;">
                            <i class="fa-solid fa-tag"></i> Desde ${{ number_format($hotel->precio, 0, ',', '.') }}/noche
                        </p>
                    @endif
                    <div class="card-actions">
                        <a href="{{ route('hoteles.detalle', $hotel) }}" class="btn btn-outline btn-sm">Ver más</a>
                        @auth
                            <a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}" class="btn btn-primary btn-sm">Reservar</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Reservar</a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <p style="color:var(--gray-400);grid-column:1/-1;text-align:center;padding:3rem;">No hay hoteles disponibles aún.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     PRÓXIMOS EVENTOS
═══════════════════════════════════════════════════════ --}}
<section class="section" style="background: var(--gray-50);">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label"><i class="fa-solid fa-calendar-days"></i> Agenda</p>
                <h2 class="section-title">Próximos Eventos</h2>
            </div>
            <a href="{{ route('eventos') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid">
            @forelse($eventos_proximos ?? [] as $evento)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($evento->imagen)
                        <img src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->nombre }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--gold-500),var(--gold-400));display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-calendar-star" style="font-size:3rem;color:rgba(255,255,255,.4);"></i>
                        </div>
                    @endif
                    @if($evento->categoria)
                        <span class="card-badge card-badge-accent">{{ $evento->categoria }}</span>
                    @endif
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $evento->nombre }}</h3>
                    @if($evento->fecha)
                        <p class="card-meta"><i class="fa-solid fa-calendar"></i> {{ \Carbon\Carbon::parse($evento->fecha)->format('d M Y') }}</p>
                    @endif
                    <p class="card-meta"><i class="fa-solid fa-location-dot"></i> {{ $evento->ubicacion ?? 'Ortega, Tolima' }}</p>
                    <div class="card-actions">
                        <a href="{{ route('eventos') }}" class="btn btn-outline btn-sm">Ver detalles</a>
                    </div>
                </div>
            </div>
            @empty
            <p style="color:var(--gray-400);grid-column:1/-1;text-align:center;padding:3rem;">No hay eventos próximos.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     BLOG
═══════════════════════════════════════════════════════ --}}
@if(isset($blog_recientes) && $blog_recientes->count() > 0)
<section class="section" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <div>
                <p class="section-label"><i class="fa-solid fa-newspaper"></i> Blog</p>
                <h2 class="section-title">Últimas Noticias</h2>
            </div>
            <a href="{{ route('blog') }}" class="btn btn-outline">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="blog-grid">
            @foreach($blog_recientes as $post)
            <div class="blog-card animate-on-scroll">
                <div class="card-img-wrap" style="width:240px;flex-shrink:0;height:auto;">
                    @if($post->imagen)
                        <img src="{{ asset('storage/' . $post->imagen) }}" alt="{{ $post->titulo }}" loading="lazy" style="height:100%;min-height:180px;">
                    @else
                        <div style="width:240px;min-height:180px;background:linear-gradient(135deg,var(--green-800),var(--green-600));display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-newspaper" style="font-size:2.5rem;color:rgba(255,255,255,.4);"></i>
                        </div>
                    @endif
                </div>
                <div class="card-content" style="flex:1;">
                    <p class="card-meta"><i class="fa-solid fa-calendar"></i> {{ $post->created_at->format('d M Y') }}</p>
                    <h3>{{ $post->titulo }}</h3>
                    <p style="font-size:.875rem;color:var(--gray-600);line-height:1.5;margin-bottom:1rem;">{{ Str::limit($post->contenido, 100) }}</p>
                    <a href="{{ route('blog.post', $post->slug) }}" class="btn btn-outline btn-sm">Leer más <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════
     CTA FINAL
═══════════════════════════════════════════════════════ --}}
<section class="section" style="background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 100%); text-align: center;">
    <div class="container">
        <p class="section-label" style="color:var(--green-200);justify-content:center;">
            <i class="fa-solid fa-compass"></i> ¿Listo para explorar?
        </p>
        <h2 class="section-title" style="color:var(--white);margin-bottom:1rem;">Planifica tu Visita a Ortega</h2>
        <p style="color:rgba(255,255,255,.75);max-width:500px;margin:0 auto 2rem;line-height:1.7;">
            Descubre todo lo que Ortega, Tolima tiene para ofrecerte. Naturaleza, cultura, gastronomía y hospitalidad en un solo lugar.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('lugares') }}" class="btn btn-white btn-lg">
                <i class="fa-solid fa-map-pin"></i> Explorar Lugares
            </a>
            <a href="{{ route('contacto') }}" class="btn btn-glass btn-lg">
                <i class="fa-solid fa-envelope"></i> Contáctanos
            </a>
        </div>
    </div>
</section>

@endsection
