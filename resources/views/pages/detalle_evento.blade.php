@extends('layouts.app')

@section('title', $evento->nombre)

@php
    $imgSrc = $evento->imagen
        ? (str_starts_with($evento->imagen, 'http') ? $evento->imagen : asset('storage/' . $evento->imagen))
        : null;
@endphp

@section('content')

{{-- Hero --}}
<section class="detalle-hero"
    style="min-height:55vh;display:flex;align-items:flex-end;position:relative;overflow:hidden;
           background:{{ $imgSrc ? 'url(\''.$imgSrc.'\') center/cover no-repeat' : 'linear-gradient(135deg,var(--green-900),var(--green-700))' }};">
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(27,67,50,.88) 0%,rgba(27,67,50,.4) 60%,transparent 100%);"></div>
    <div class="container" style="position:relative;z-index:2;padding-bottom:3rem;padding-top:calc(var(--navbar-height) + 2rem);">
        <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;align-items:center;">
            <span class="hero-eyebrow"><i class="fa-solid fa-calendar-days fa-xs"></i> Evento</span>
            @if($evento->categoria)
                <span style="background:var(--green-600);color:#fff;font-size:.75rem;font-weight:700;padding:.3rem .85rem;border-radius:var(--radius-full);">
                    {{ $evento->categoria }}
                </span>
            @endif
        </div>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:.75rem;">
            {{ $evento->nombre }}
        </h1>
        <div style="display:flex;flex-wrap:wrap;gap:1.25rem;align-items:center;">
            <span style="color:rgba(255,255,255,.9);font-size:1rem;">
                <i class="fa-solid fa-calendar fa-xs"></i>
                {{ $evento->fecha->format('d \d\e F \d\e Y') }}
                @if($evento->hora)
                    &nbsp;·&nbsp; <i class="fa-solid fa-clock fa-xs"></i>
                    {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                @endif
            </span>
            @if($evento->precio > 0)
                <span style="color:var(--gold-400);font-size:1.1rem;font-weight:700;">
                    <i class="fa-solid fa-ticket fa-xs"></i>
                    ${{ number_format($evento->precio, 0, ',', '.') }} COP
                </span>
            @else
                <span style="background:rgba(255,255,255,.15);color:#fff;font-size:.85rem;font-weight:600;padding:.35rem 1rem;border-radius:var(--radius-full);backdrop-filter:blur(6px);">
                    <i class="fa-solid fa-circle-check fa-xs"></i> Entrada gratuita
                </span>
            @endif
        </div>
    </div>
</section>

{{-- Contenido --}}
<section class="container section">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:2.5rem;align-items:start;">

        {{-- Columna principal --}}
        <div>
            <div class="admin-section">
                <h2 style="font-family:var(--font-display);color:var(--green-800);margin-bottom:1rem;font-size:1.4rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Sobre este evento
                </h2>
                <p style="line-height:1.8;color:var(--gray-600);">{{ $evento->descripcion }}</p>
            </div>

            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Información
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-calendar" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Fecha</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $evento->fecha->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @if($evento->hora)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-clock" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Hora</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($evento->ubicacion)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-location-dot" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Ubicación</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $evento->ubicacion }}</p>
                        </div>
                    </div>
                    @endif
                    @if($evento->organizador)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-user-tie" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Organizador</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $evento->organizador }}</p>
                        </div>
                    </div>
                    @endif
                    @if($evento->contacto)
                    <div style="display:flex;gap:.75rem;align-items:flex-start;">
                        <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-phone" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Contacto</p>
                            <p style="font-size:.9rem;color:var(--gray-900);">{{ $evento->contacto }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Eventos relacionados --}}
            @if($relacionados->count())
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1.25rem;font-size:1.1rem;">
                    <i class="fa-solid fa-calendar-days fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Otros eventos
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
                    @foreach($relacionados as $rel)
                    @php $relImg = $rel->imagen ? (str_starts_with($rel->imagen,'http') ? $rel->imagen : asset('storage/'.$rel->imagen)) : null; @endphp
                    <a href="{{ route('eventos.detalle', $rel) }}" style="text-decoration:none;display:block;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;transition:box-shadow .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='none'">
                        <div style="height:120px;overflow:hidden;background:var(--green-50);">
                            @if($relImg)
                                <img src="{{ $relImg }}" alt="{{ $rel->nombre }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-calendar-days" style="font-size:2rem;color:var(--green-300);"></i></div>
                            @endif
                        </div>
                        <div style="padding:.75rem;">
                            <p style="font-weight:700;color:#111;font-size:.9rem;margin-bottom:.25rem;">{{ $rel->nombre }}</p>
                            <p style="font-size:.78rem;color:#6b7280;"><i class="fa-solid fa-calendar fa-xs"></i> {{ $rel->fecha->format('d/m/Y') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div style="position:sticky;top:calc(var(--navbar-height) + 1.5rem);">
            <div class="admin-section" style="border-top:4px solid var(--green-700);">
                <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem;">Precio de entrada</p>
                @if($evento->precio > 0)
                    <p style="font-family:var(--font-display);font-size:2.2rem;font-weight:800;color:var(--green-800);line-height:1;">
                        ${{ number_format($evento->precio, 0, ',', '.') }}
                        <span style="font-size:.9rem;font-weight:400;color:var(--gray-400);">COP</span>
                    </p>
                @else
                    <p style="font-size:1.3rem;font-weight:700;color:var(--success);">
                        <i class="fa-solid fa-circle-check fa-xs"></i> Entrada gratuita
                    </p>
                @endif

                <hr style="border:none;border-top:1px solid var(--gray-200);margin:1.25rem 0;">

                @if($evento->precio > 0)
                <form action="{{ route('cart.add') }}" method="POST" style="margin-bottom:.75rem;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $evento->id }}">
                    <input type="hidden" name="tipo" value="evento">
                    <input type="hidden" name="nombre" value="{{ $evento->nombre }}">
                    <input type="hidden" name="precio" value="{{ $evento->precio }}">
                    <input type="hidden" name="imagen" value="{{ $evento->imagen }}">
                    <input type="hidden" name="cantidad" value="1">
                    <input type="hidden" name="opciones[fecha]" value="{{ $evento->fecha->format('Y-m-d') }}">
                    <button type="submit" class="btn btn-block btn-lg" style="background-color:#f59e0b;color:white;width:100%;">
                        <i class="fa-solid fa-cart-plus"></i> Añadir al carrito
                    </button>
                </form>
                @endif

                <a href="{{ route('eventos') }}" class="btn btn-outline btn-block">
                    <i class="fa-solid fa-arrow-left"></i> Ver todos los eventos
                </a>
            </div>
        </div>

    </div>
</section>

@endsection
