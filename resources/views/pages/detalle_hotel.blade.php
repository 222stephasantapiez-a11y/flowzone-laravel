@extends('layouts.app')

@section('title', $hotel->nombre)

@php
    $imgSrc = $hotel->imagen
        ? (str_starts_with($hotel->imagen, 'http') ? $hotel->imagen : asset('storage/' . $hotel->imagen))
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
                <i class="fa-solid fa-hotel fa-xs"></i> Hotel
            </span>
            @if($hotel->disponibilidad)
                <span style="background:var(--success);color:#fff;font-size:.75rem;font-weight:700;padding:.3rem .85rem;border-radius:var(--radius-full);">
                    <i class="fa-solid fa-circle-check fa-xs"></i> Disponible
                </span>
            @else
                <span style="background:var(--danger);color:#fff;font-size:.75rem;font-weight:700;padding:.3rem .85rem;border-radius:var(--radius-full);">
                    No disponible
                </span>
            @endif
        </div>
        <h1 style="font-family:var(--font-display);font-size:clamp(2rem,5vw,3.5rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:.75rem;">
            {{ $hotel->nombre }}
        </h1>
        <p style="font-size:1.5rem;font-weight:700;color:var(--gold-400);">
            ${{ number_format($hotel->precio, 0, ',', '.') }} COP
            <span style="font-size:.9rem;font-weight:400;color:rgba(255,255,255,.7);"> / noche</span>
        </p>
    </div>
</section>

{{-- Main content --}}
<section class="container section">
    <div style="display:grid;grid-template-columns:1fr 340px;gap:2.5rem;align-items:start;">

        {{-- Columna principal --}}
        <div>
            {{-- Descripción --}}
            <div class="admin-section">
                <h2 style="font-family:var(--font-display);color:var(--green-800);margin-bottom:1rem;font-size:1.4rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Descripción
                </h2>
                <p style="line-height:1.8;color:var(--gray-600);">{{ $hotel->descripcion }}</p>
            </div>

            {{-- Servicios --}}
            @if($hotel->servicios)
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-star fa-xs" style="color:var(--gold-500);margin-right:.5rem;"></i>Servicios incluidos
                </h3>
                <ul style="list-style:none;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:.6rem;">
                    @foreach(explode(',', $hotel->servicios) as $servicio)
                    <li style="display:flex;align-items:center;gap:.5rem;font-size:.9rem;color:var(--gray-600);">
                        <i class="fa-solid fa-check" style="color:var(--green-600);font-size:.75rem;flex-shrink:0;"></i>
                        {{ trim($servicio) }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Info grid --}}
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Información
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                    @if($hotel->ubicacion)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-location-dot" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Ubicación</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $hotel->ubicacion }}</p>
                        </div>
                    </div>
                    @endif
                    @if($hotel->capacidad)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-users" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Capacidad</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $hotel->capacidad }} personas</p>
                        </div>
                    </div>
                    @endif
                    @if($hotel->telefono)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-phone" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Teléfono</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $hotel->telefono }}</p>
                        </div>
                    </div>
                    @endif
                    @if($hotel->email)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-envelope" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Email</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $hotel->email }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Mapa --}}
            @if($hotel->latitud && $hotel->longitud)
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-map fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Ubicación en el mapa
                </h3>
                <div style="border-radius:var(--radius-md);overflow:hidden;">
                    <iframe width="100%" height="360" frameborder="0" style="border:0;display:block;"
                        src="https://www.google.com/maps?q={{ $hotel->latitud }},{{ $hotel->longitud }}&output=embed"
                        allowfullscreen loading="lazy" title="Mapa {{ $hotel->nombre }}"></iframe>
                </div>
            </div>
            @endif

            {{-- Reviews --}}
            @include('partials.reviews', [
                'stats'          => $stats,
                'miCalificacion' => $miCalificacion,
                'reseñas'        => $reseñas,
                'tipo'           => 'hotel',
                'itemId'         => $hotel->id,
            ])
        </div>

        {{-- Sidebar --}}
        <div style="position:sticky;top:calc(var(--navbar-height) + 1.5rem);">
            <div class="admin-section" style="border-top:4px solid var(--green-700);">
                <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Precio por noche</p>
                <p style="font-family:var(--font-display);font-size:2.2rem;font-weight:800;color:var(--green-800);line-height:1;">
                    ${{ number_format($hotel->precio, 0, ',', '.') }}
                    <span style="font-size:.9rem;font-weight:400;color:var(--gray-400);">COP</span>
                </p>

                <hr style="border:none;border-top:1px solid var(--gray-200);margin:1.25rem 0;">

                @auth
                    <a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}"
                       class="btn btn-primary btn-block btn-lg" style="margin-bottom:.75rem;">
                        <i class="fa-solid fa-calendar-check"></i> Hacer Reserva
                    </a>
                    <button class="btn btn-outline btn-block btn-favorito {{ $es_favorito ? 'active' : '' }}"
                            data-tipo="hotel" data-id="{{ $hotel->id }}"
                            style="margin-bottom:.75rem;"
                            aria-label="{{ $es_favorito ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                        <i class="{{ $es_favorito ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                        <span class="btn-fav-label">{{ $es_favorito ? 'En Favoritos' : 'Agregar a Favoritos' }}</span>
                    </button>
                    <a href="{{ route('mis-reservas') }}" class="btn btn-outline btn-block">
                        <i class="fa-solid fa-list"></i> Mis reservas
                    </a>
                @else
                    <div style="background:var(--gray-50);border:1px solid var(--gray-200);border-radius:var(--radius-md);padding:1rem;text-align:center;margin-bottom:1rem;">
                        <i class="fa-solid fa-lock" style="color:var(--gray-400);font-size:1.5rem;margin-bottom:.5rem;display:block;"></i>
                        <p style="font-size:.85rem;color:var(--gray-600);margin-bottom:.75rem;">Inicia sesión para reservar</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block" style="margin-bottom:.5rem;">Iniciar Sesión</a>
                        <p style="font-size:.78rem;color:var(--gray-400);">
                            ¿No tienes cuenta? <a href="{{ route('registro') }}" style="color:var(--green-700);">Regístrate gratis</a>
                        </p>
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