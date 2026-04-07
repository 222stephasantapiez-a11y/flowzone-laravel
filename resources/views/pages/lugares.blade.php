@extends('layouts.app')

@section('title', 'Lugares Turísticos')
@section('body-class', 'no-hero')

@section('content')
<main>

{{-- Page Hero --}}
<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow">
                <i class="fa-solid fa-map-location-dot"></i> Destinos
            </span>
            <h1>Lugares Turísticos</h1>
            <p>Descubre los mejores destinos y paisajes de Ortega, Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Lugares</span>
            </nav>
        </div>
    </div>
</section>

{{-- Contenido --}}
<section class="container section">

    {{-- Filtros --}}
    <div class="filters">
        <form method="GET" action="{{ route('lugares') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar lugares..."
                   value="{{ $busqueda }}" aria-label="Buscar lugares">
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
            <a href="{{ route('lugares') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    {{-- Grid de lugares --}}
    <div class="grid">
        @forelse($lugares as $lugar)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($lugar->imagen)
                        @php
                            $imgSrc = str_starts_with($lugar->imagen, 'http')
                                ? $lugar->imagen
                                : asset('storage/' . $lugar->imagen);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $lugar->nombre }}"
                             loading="lazy"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-mountain-sun\'></i></div>'">
                    @else
                        <div class="card-img-fallback">
                            <i ></i>
                        </div>
                    @endif

                    @if($lugar->categoria)
                        <span class="card-badge">{{ $lugar->categoria }}</span>
                    @endif

                    <div class="card-img-overlay" aria-hidden="true"></div>
                </div>

                <div class="card-content">
                    <h3>{{ $lugar->nombre }}</h3>

                    @if($lugar->ubicacion)
                        <p class="card-meta">
                            <i class="fa-solid fa-location-dot fa-xs" aria-hidden="true"></i>
                            {{ $lugar->ubicacion }}
                        </p>
                    @endif

                    @if($lugar->precio_entrada > 0)
                        <p class="card-meta" style="color:var(--green-700);font-weight:700;font-size:.95rem;">
                            <i class="fa-solid fa-ticket fa-xs" aria-hidden="true"></i>
                            ${{ number_format($lugar->precio_entrada, 0, ',', '.') }} COP
                        </p>
                    @else
                        <p class="card-meta" style="color:var(--success);">
                            <i class="fa-solid fa-circle-check fa-xs" aria-hidden="true"></i>
                            Entrada gratuita
                        </p>
                    @endif

                    @if($lugar->descripcion)
                        <p class="card-desc">{{ Str::limit($lugar->descripcion, 120) }}</p>
                    @endif

                    <div class="card-actions">
                        <a href="{{ route('lugares.detalle', $lugar) }}" class="btn btn-primary btn-sm">
                            Ver más <i class="fa-solid fa-arrow-right fa-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div style="grid-column:1/-1;">
                <div class="empty-state">
                    <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i>
                    <p>No se encontraron lugares turísticos.</p>
                    <a href="{{ route('lugares') }}" class="btn btn-outline" style="margin-top:1rem;">Ver todos</a>
                </div>
            </div>
        @endforelse
    </div>

</section>

</main>
@endsection
