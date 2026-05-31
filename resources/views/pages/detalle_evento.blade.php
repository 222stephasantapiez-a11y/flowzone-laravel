@extends('layouts.app')

@section('title', $evento->nombre . ' — FlowZone')
@section('body-class', 'no-hero')

@section('content')
<main>

<section class="page-hero" style="
    background:
        @if($evento->imagen)
        linear-gradient(135deg,rgba(10,40,20,.75) 0%,rgba(30,80,50,.55) 100%),
        url('{{ str_starts_with($evento->imagen,"http") ? $evento->imagen : asset("storage/".$evento->imagen) }}') center/cover no-repeat
        @else
        linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%)
        @endif
    ;">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-calendar-days"></i> Evento</span>
            <h1>{{ $evento->nombre }}</h1>
            @if($evento->categoria)
            <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;border-radius:2rem;padding:.3rem .9rem;font-size:.82rem;font-weight:600;">
                {{ $evento->categoria }}
            </span>
            @endif
            <nav class="breadcrumb" aria-label="Breadcrumb" style="margin-top:1rem;">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right"></i>
                <a href="{{ route('eventos') }}">Eventos</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span>{{ Str::limit($evento->nombre, 30) }}</span>
            </nav>
        </div>
    </div>
</section>

<section class="container section">
    <div style="display:grid;grid-template-columns:1fr 340px;gap:2rem;align-items:start;">

        {{-- Columna principal --}}
        <div>
            {{-- Imagen --}}
            @if($evento->imagen)
            @php $imgSrc = str_starts_with($evento->imagen,'http') ? $evento->imagen : asset('storage/'.$evento->imagen); @endphp
            <div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:2rem;box-shadow:0 4px 20px rgba(0,0,0,.1);">
                <img src="{{ $imgSrc }}" alt="{{ $evento->nombre }}" style="width:100%;max-height:420px;object-fit:cover;">
            </div>
            @endif

            {{-- Descripción --}}
            @if($evento->descripcion)
            <div style="background:#fff;border-radius:var(--radius-lg);padding:1.75rem;border:1px solid var(--gray-100);margin-bottom:1.5rem;">
                <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
                    <i class="fa-solid fa-circle-info" style="color:var(--green-600);"></i> Sobre este evento
                </h2>
                <p style="color:var(--gray-600);line-height:1.8;font-size:.95rem;">{{ $evento->descripcion }}</p>
            </div>
            @endif

            {{-- Organizador --}}
            @if($evento->organizador)
            <div style="background:#f0fdf4;border-radius:var(--radius-lg);padding:1.25rem 1.5rem;border:1px solid #bbf7d0;">
                <p style="margin:0;font-size:.88rem;color:var(--green-800);">
                    <i class="fa-solid fa-user-tie fa-xs"></i>
                    <strong>Organizador:</strong> {{ $evento->organizador }}
                </p>
            </div>
            @endif
        </div>

        {{-- Sidebar info --}}
        <div style="position:sticky;top:1.5rem;">
            <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--gray-100);box-shadow:0 2px 16px rgba(0,0,0,.06);overflow:hidden;">
                <div style="background:linear-gradient(135deg,var(--green-900),var(--green-700));padding:1.25rem 1.5rem;">
                    <h3 style="color:#fff;font-size:1rem;font-weight:700;margin:0;">Información del evento</h3>
                </div>
                <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1rem;">

                    {{-- Fecha --}}
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:38px;height:38px;background:#f0fdf4;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-calendar" style="color:var(--green-700);font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.06em;">Fecha</div>
                            <div style="font-weight:600;color:var(--gray-800);font-size:.9rem;">{{ $evento->fecha->format('d \d\e F \d\e Y') }}</div>
                        </div>
                    </div>

                    {{-- Hora --}}
                    @if($evento->hora)
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:38px;height:38px;background:#f0fdf4;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-clock" style="color:var(--green-700);font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.06em;">Hora</div>
                            <div style="font-weight:600;color:var(--gray-800);font-size:.9rem;">{{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }} hrs</div>
                        </div>
                    </div>
                    @endif

                    {{-- Ubicación --}}
                    @if($evento->ubicacion)
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:38px;height:38px;background:#f0fdf4;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-location-dot" style="color:var(--green-700);font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.06em;">Lugar</div>
                            <div style="font-weight:600;color:var(--gray-800);font-size:.9rem;">{{ $evento->ubicacion }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- Precio --}}
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:38px;height:38px;background:{{ $evento->precio > 0 ? '#fef9c3' : '#f0fdf4' }};border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-ticket" style="color:{{ $evento->precio > 0 ? '#ca8a04' : 'var(--green-700)' }};font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.06em;">Entrada</div>
                            @if($evento->precio > 0)
                            <div style="font-weight:700;color:#ca8a04;font-size:1rem;">${{ number_format($evento->precio, 0, ',', '.') }} COP</div>
                            @else
                            <div style="font-weight:700;color:var(--green-700);font-size:.9rem;"><i class="fa-solid fa-circle-check fa-xs"></i> Gratuita</div>
                            @endif
                        </div>
                    </div>

                    {{-- Contacto --}}
                    @if($evento->contacto)
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div style="width:38px;height:38px;background:#f0fdf4;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-phone" style="color:var(--green-700);font-size:.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.06em;">Contacto</div>
                            <div style="font-weight:600;color:var(--gray-800);font-size:.9rem;">{{ $evento->contacto }}</div>
                        </div>
                    </div>
                    @endif

                </div>

                <div style="padding:0 1.5rem 1.5rem;">
                    <a href="{{ route('eventos') }}" class="btn btn-outline btn-sm" style="width:100%;text-align:center;display:block;">
                        <i class="fa-solid fa-arrow-left fa-xs"></i> Ver todos los eventos
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

</main>
@endsection