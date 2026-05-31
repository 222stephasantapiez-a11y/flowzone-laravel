@extends('layouts.app')

@section('title', 'Planes Turísticos — Ortega, Tolima')
@section('body-class', 'no-hero')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $tipoLabels = ['hotel'=>' Hotel','restaurante'=>' Restaurante','agencia_turismo'=>' Agencia','transporte'=>' Transporte','artesanias'=>' Artesanías','otro'=>' Otro'];
@endphp
<main>

<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-map-location-dot"></i> Turismo</span>
            <h1>Planes Turísticos</h1>
            <p>Descubre los mejores planes con descuentos especiales en Ortega, Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Planes Turísticos</span>
            </nav>
        </div>
    </div>
</section>

<section class="container section">

    {{-- Filtros --}}
    <div class="filters">
        <form method="GET" action="{{ route('planes.publico') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar planes..."
                   value="{{ $busqueda }}" aria-label="Buscar planes">
            <select name="tipo" aria-label="Tipo de plan">
                <option value="">Todos los tipos</option>
                @foreach($tipoLabels as $val => $label)
                <option value="{{ $val }}" {{ $tipo_filtro === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs"></i> Filtrar
            </button>
            <a href="{{ route('planes.publico') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    @if($planes->isEmpty())
    <div style="text-align:center;padding:4rem 1rem;color:var(--gray-400);">
        <i class="fa-solid fa-map-location-dot fa-3x" style="margin-bottom:1rem;opacity:.3;"></i>
        <p style="font-size:1.1rem;">No hay planes publicados aún.</p>
    </div>
    @else
    <div class="grid">
        @foreach($planes as $plan)
        @php
            $imgSrc = $plan->imagen
                ? (Str::startsWith($plan->imagen,'http') ? $plan->imagen : asset('storage/'.$plan->imagen))
                : null;
        @endphp
        <article class="card animate-on-scroll">
            <div class="card-img-wrap">
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $plan->titulo }}" loading="lazy">
                @else
                    <div class="card-img-fallback"><i class="fa-solid fa-map-location-dot"></i></div>
                @endif
                <span class="card-badge" style="background:#ef4444;">-20% DCTO</span>
                <div class="card-img-overlay"></div>
            </div>
            <div class="card-content">
                <h3>{{ $plan->titulo }}</h3>

                @if($plan->empresa)
                <p class="card-meta">
                    <i class="fa-solid fa-building fa-xs"></i>
                    {{ $plan->empresa->nombre }}
                    @if($plan->tipo_plan)
                    · <span style="color:var(--green-700);">{{ $tipoLabels[$plan->tipo_plan] ?? $plan->tipo_plan }}</span>
                    @endif
                </p>
                @endif

                @if($plan->descripcion)
                <p style="font-size:.88rem;color:var(--gray-600);margin:.5rem 0;">{{ Str::limit($plan->descripcion, 100) }}</p>
                @endif

                {{-- Componentes del plan --}}
                <div style="display:flex;flex-direction:column;gap:.3rem;margin:.75rem 0;font-size:.82rem;color:var(--gray-600);">
                    @if($plan->habitacion)
                    <div><span style="color:var(--green-700);">🛏</span> {{ $plan->habitacion->nombre }}
                        @if($plan->hotel) — {{ $plan->hotel->nombre }}@endif
                    </div>
                    @elseif($plan->hotel)
                    <div><span style="color:var(--green-700);">🏨</span> {{ $plan->hotel->nombre }}</div>
                    @endif
                    @if($plan->gastronomia)
                    <div><span style="color:#f97316;">🍽</span> {{ $plan->gastronomia->nombre }}</div>
                    @endif
                    @if($plan->evento)
                    <div><span style="color:#6366f1;">🎭</span> {{ $plan->evento->nombre }}</div>
                    @endif
                    @if($plan->lugar)
                    <div><span style="color:#3b82f6;">📍</span> {{ $plan->lugar->nombre }}</div>
                    @endif
                </div>

                {{-- Precio --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.75rem;">
                    <div>
                        <span style="text-decoration:line-through;color:var(--gray-400);font-size:.82rem;">
                            ${{ number_format($plan->subtotal, 0, ',', '.') }} COP
                        </span>
                        <div style="font-size:1.2rem;font-weight:700;color:var(--green-700);">
                            ${{ number_format($plan->precio_final, 0, ',', '.') }} COP
                        </div>
                    </div>
                    <span style="background:#fee2e2;color:#dc2626;border-radius:2rem;padding:.25rem .7rem;font-size:.78rem;font-weight:700;">
                        Ahorra ${{ number_format($plan->descuento, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Botón reservar --}}
                <div style="margin-top:1rem;">
                    @auth
                        @if($plan->hotel_id)
                        <a href="{{ route('reservar', ['hotel_id' => $plan->hotel_id, 'plan_id' => $plan->id]) }}"
                           class="btn btn-primary btn-sm" style="width:100%;text-align:center;display:block;">
                            <i class="fa-solid fa-calendar-check fa-xs"></i> Reservar este plan
                        </a>
                        @else
                        <button disabled class="btn btn-sm" style="width:100%;text-align:center;display:block;opacity:.5;cursor:not-allowed;background:var(--gray-200);color:var(--gray-500);">
                            <i class="fa-solid fa-circle-info fa-xs"></i> Sin hotel disponible
                        </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="btn btn-primary btn-sm" style="width:100%;text-align:center;display:block;">
                            <i class="fa-solid fa-right-to-bracket fa-xs"></i> Inicia sesión para reservar
                        </a>
                    @endauth
                </div>

            </div>
        </article>
        @endforeach
    </div>
    @endif

</section>
</main>
@endsection