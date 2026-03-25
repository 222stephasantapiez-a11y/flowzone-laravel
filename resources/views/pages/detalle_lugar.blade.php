@extends('layouts.app')

@section('title', $lugar->nombre)

@php
    $imgSrc = $lugar->imagen
        ? (str_starts_with($lugar->imagen, 'http') ? $lugar->imagen : asset('storage/' . $lugar->imagen))
        : null;
@endphp

@section('content')

{{-- Hero --}}
<section class="detalle-hero"
    style="min-height:60vh;display:flex;align-items:flex-end;position:relative;overflow:hidden;
           background:{{ $imgSrc ? 'url(\''.$imgSrc.'\') center/cover no-repeat' : 'linear-gradient(135deg,var(--green-900),var(--green-700))' }};">
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(27,67,50,.85) 0%,rgba(27,67,50,.45) 60%,transparent 100%);"></div>
    <div class="container" style="position:relative;z-index:2;padding-bottom:3rem;padding-top:calc(var(--navbar-height) + 2rem);">
        <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;align-items:center;">
            <span class="hero-eyebrow">
                <i class="fa-solid fa-map-pin fa-xs"></i> Lugar
            </span>
            @if($lugar->categoria)
                <span style="background:var(--green-600);color:#fff;font-size:.75rem;font-weight:700;padding:.3rem .85rem;border-radius:var(--radius-full);">
                    {{ $lugar->categoria }}
                </span>
            @endif
        </div>
        <h1 style="font-family:var(--font-display);font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:.75rem;">
            {{ $lugar->nombre }}
        </h1>
        @if($lugar->precio_entrada > 0)
            <p style="font-size:1.3rem;font-weight:700;color:var(--gold-400);">
                ${{ number_format($lugar->precio_entrada, 0, ',', '.') }} COP
                <span style="font-size:.9rem;font-weight:400;color:rgba(255,255,255,.7);"> entrada</span>
            </p>
        @else
            <span style="background:rgba(255,255,255,.15);color:#fff;font-size:.85rem;font-weight:600;padding:.4rem 1rem;border-radius:var(--radius-full);backdrop-filter:blur(6px);">
                <i class="fa-solid fa-ticket fa-xs"></i> Entrada gratuita
            </span>
        @endif
    </div>
</section>

{{-- Main content --}}
<section class="container section">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:2.5rem;align-items:start;">

        {{-- Columna principal --}}
        <div>
            {{-- Descripción --}}
            <div class="admin-section">
                <h2 style="font-family:var(--font-display);color:var(--green-800);margin-bottom:1rem;font-size:1.4rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Descripción
                </h2>
                <p style="line-height:1.8;color:var(--gray-600);">{{ $lugar->descripcion }}</p>
            </div>

            {{-- Info grid --}}
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Información
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                    @if($lugar->ubicacion)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-location-dot" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Ubicación</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $lugar->ubicacion }}</p>
                        </div>
                    </div>
                    @endif
                    @if($lugar->horario)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-clock" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Horario</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $lugar->horario }}</p>
                        </div>
                    </div>
                    @endif
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-ticket" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Entrada</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">
                                @if($lugar->precio_entrada > 0)
                                    ${{ number_format($lugar->precio_entrada, 0, ',', '.') }} COP
                                @else
                                    Gratuita
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mapa --}}
            @if($lugar->latitud && $lugar->longitud)
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-map fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Ubicación en el mapa
                </h3>
                <div style="border-radius:var(--radius-md);overflow:hidden;">
                    <iframe width="100%" height="360" frameborder="0" style="border:0;display:block;"
                        src="https://www.google.com/maps?q={{ $lugar->latitud }},{{ $lugar->longitud }}&output=embed"
                        allowfullscreen loading="lazy" title="Mapa {{ $lugar->nombre }}"></iframe>
                </div>
            </div>
            @endif

            {{-- Reviews --}}
            @include('partials.reviews', [
                'stats'          => $stats,
                'miCalificacion' => $miCalificacion,
                'reseñas'        => $reseñas,
                'tipo'           => 'lugar',
                'itemId'         => $lugar->id,
            ])
        </div>

        {{-- Sidebar --}}
        <div style="position:sticky;top:calc(var(--navbar-height) + 1.5rem);">
            <div class="admin-section" style="border-top:4px solid var(--green-700);">
                <h3 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;">Acciones</h3>

                @auth
                    <button class="btn btn-primary btn-block btn-favorito {{ $es_favorito ? 'active' : '' }}"
                            data-tipo="lugar" data-id="{{ $lugar->id }}"
                            style="margin-bottom:.75rem;"
                            aria-label="{{ $es_favorito ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                        <i class="{{ $es_favorito ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                        <span class="btn-fav-label">{{ $es_favorito ? 'En Favoritos' : 'Agregar a Favoritos' }}</span>
                    </button>
                    <a href="{{ route('favoritos') }}" class="btn btn-outline btn-block">
                        <i class="fa-solid fa-list"></i> Ver mis favoritos
                    </a>
                @else
                    <div style="background:var(--gray-50);border:1px solid var(--gray-200);border-radius:var(--radius-md);padding:1rem;text-align:center;margin-bottom:1rem;">
                        <i class="fa-solid fa-lock" style="color:var(--gray-400);font-size:1.5rem;margin-bottom:.5rem;display:block;"></i>
                        <p style="font-size:.85rem;color:var(--gray-600);margin-bottom:.75rem;">Inicia sesión para guardar favoritos</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block">Iniciar Sesión</a>
                    </div>
                @endauth
            </div>

            {{-- Calificación resumen --}}
            @if(isset($stats) && $stats['total'] > 0)
            <div class="admin-section" style="text-align:center;">
                <p style="font-size:3rem;font-weight:800;color:var(--green-800);line-height:1;">{{ number_format($stats['promedio'], 1) }}</p>
                <p style="font-size:.85rem;color:var(--gray-400);margin-bottom:.5rem;">de 5 — {{ $stats['total'] }} reseña{{ $stats['total'] != 1 ? 's' : '' }}</p>
                <div style="display:flex;justify-content:center;gap:.2rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= round($stats['promedio']) ? 'solid' : 'regular' }} fa-star"
                           style="color:var(--gold-500);font-size:1rem;"></i>
                    @endfor
                </div>
            </div>
            @endif
        </div>

    </div>
</section>

@include('partials.favorito_script')

@endsection