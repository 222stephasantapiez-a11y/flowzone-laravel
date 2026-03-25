@extends('layouts.app')

@section('title', 'Eventos y Actividades')
@section('body-class', 'no-hero')

@section('content')
<main>

{{-- Page Hero --}}
<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow">
                <i class="fa-solid fa-calendar-days"></i> Agenda
            </span>
            <h1>Eventos y Actividades</h1>
            <p>Descubre los próximos eventos culturales y actividades en Ortega, Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Eventos</span>
            </nav>
        </div>
    </div>
</section>

{{-- Contenido --}}
<section class="container section">

    {{-- Filtros --}}
    <div class="filters">
        <form method="GET" action="{{ route('eventos') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar eventos..."
                   value="{{ $busqueda }}" aria-label="Buscar eventos">
            <select name="categoria" aria-label="Filtrar por categoría">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ $categoria_filtro === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs" aria-hidden="true"></i> Filtrar
            </button>
            <a href="{{ route('eventos') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    {{-- Grid de eventos --}}
    <div class="grid">
        @forelse($eventos as $evento)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($evento->imagen)
                        @php
                            $imgSrc = str_starts_with($evento->imagen, 'http')
                                ? $evento->imagen
                                : Storage::disk('public')->url($evento->imagen);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $evento->nombre }}"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-calendar-days\'></i></div>'">
                    @else
                        <div class="card-img-fallback">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </div>
                    @endif

                    @if($evento->categoria)
                        <span class="card-badge">{{ $evento->categoria }}</span>
                    @endif

                    <div class="card-img-overlay" aria-hidden="true"></div>
                </div>

                <div class="card-content">
                    <h3>{{ $evento->nombre }}</h3>

                    <p class="card-meta">
                        <i class="fa-solid fa-calendar fa-xs" aria-hidden="true"></i>
                        {{ $evento->fecha->format('d/m/Y') }}
                        @if($evento->hora)
                            &nbsp;·&nbsp;
                            <i class="fa-solid fa-clock fa-xs" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                        @endif
                    </p>

                    @if($evento->ubicacion)
                        <p class="card-meta">
                            <i class="fa-solid fa-location-dot fa-xs" aria-hidden="true"></i>
                            {{ $evento->ubicacion }}
                        </p>
                    @endif

                    @if($evento->precio > 0)
                        <p class="card-meta" style="color:var(--green-700);font-weight:700;font-size:.95rem;">
                            <i class="fa-solid fa-ticket fa-xs" aria-hidden="true"></i>
                            ${{ number_format($evento->precio, 0, ',', '.') }} COP
                        </p>
                    @else
                        <p class="card-meta" style="color:var(--success);">
                            <i class="fa-solid fa-circle-check fa-xs" aria-hidden="true"></i>
                            Entrada gratuita
                        </p>
                    @endif

                    @if($evento->descripcion)
                        <p class="card-desc">{{ Str::limit($evento->descripcion, 120) }}</p>
                    @endif

                    <div class="card-actions">
                        <a href="{{ route('eventos') }}" class="btn btn-primary btn-sm">
                            Ver detalles <i class="fa-solid fa-arrow-right fa-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div style="grid-column:1/-1;">
                <div class="empty-state">
                    <i class="fa-solid fa-calendar-xmark" aria-hidden="true"></i>
                    <p>No hay eventos próximos programados.</p>
                </div>
            </div>
        @endforelse
    </div>

</section>

</main>
@endsection
