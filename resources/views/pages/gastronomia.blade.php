@extends('layouts.app')
 
@section('title', 'Gastronomia Local')
@section('body-class', 'no-hero')
 
@section('content')
<main>
 
<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-utensils"></i> Sabores</span>
            <h1>Gastronomia Local</h1>
            <p>Descubre los sabores y tradiciones culinarias de Ortega, Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Gastronomia</span>
            </nav>
        </div>
    </div>
</section>
 
<section class="container section">
 
    <div class="filters">
        <form method="GET" action="{{ route('gastronomia') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar platos o restaurantes..."
                   value="{{ $busqueda }}" aria-label="Buscar gastronomia">
            <select name="tipo" aria-label="Filtrar por tipo">
                <option value="">Todos los tipos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" {{ $tipo_filtro === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs" aria-hidden="true"></i> Filtrar
            </button>
            <a href="{{ route('gastronomia') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    {{-- ══ RESTAURANTES ══ --}}
    @if(isset($restaurantes) && $restaurantes->count() > 0)
    <div style="margin-bottom:2.5rem;">
        <h2 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-store" style="color:var(--green-600);"></i> Restaurantes y locales
        </h2>
        <div class="grid">
            @foreach($restaurantes as $rest)
            <div class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($rest->logo)
                        @php $logoSrc = str_starts_with($rest->logo,'http') ? $rest->logo : asset('storage/'.$rest->logo); @endphp
                        <img src="{{ $logoSrc }}" alt="{{ $rest->nombre }}"
                             style="object-fit:contain;padding:1rem;background:#f8fafc;"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-store\'></i></div>'">
                    @else
                        <div class="card-img-fallback"><i class="fa-solid fa-store"></i></div>
                    @endif
                    <span class="card-badge">Restaurante</span>
                    <div class="card-img-overlay"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $rest->nombre }}</h3>
                    @if($rest->direccion)
                    <p class="card-meta"><i class="fa-solid fa-location-dot fa-xs"></i> {{ $rest->direccion }}</p>
                    @endif
                    @if($rest->telefono)
                    <p class="card-meta"><i class="fa-solid fa-phone fa-xs"></i> {{ $rest->telefono }}</p>
                    @endif
                    @if($rest->descripcion)
                    <p class="card-desc">{{ Str::limit($rest->descripcion, 100) }}</p>
                    @endif
                    @if($rest->servicios && count($rest->servicios))
                    <div style="display:flex;flex-wrap:wrap;gap:.3rem;margin-top:.5rem;">
                        @foreach(array_slice($rest->servicios, 0, 3) as $srv)
                        <span style="background:var(--green-50);color:var(--green-700);border-radius:2rem;padding:.15rem .55rem;font-size:.72rem;font-weight:600;">{{ $srv }}</span>
                        @endforeach
                    </div>
                    @endif
                    <div class="card-actions" style="margin-top:.75rem;">
                        <a href="{{ route('empresas.detalle', $rest) }}" class="btn btn-outline btn-sm">
                            Ver local <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <hr style="border:none;border-top:1px solid var(--gray-100);margin-bottom:2rem;">
    <h2 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-utensils" style="color:var(--green-600);"></i> Platos y especialidades
    </h2>
    @endif

    {{-- ══ PLATOS ══ --}}
    <div class="grid">
        @forelse($platos as $plato)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($plato->imagen)
                        @php $imgSrc = str_starts_with($plato->imagen,'http') ? $plato->imagen : Storage::disk('public')->url($plato->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $plato->nombre }}"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-utensils\'></i></div>'">
                    @else
                        <div class="card-img-fallback"><i class="fa-solid fa-utensils" aria-hidden="true"></i></div>
                    @endif
                    @if($plato->tipo)<span class="card-badge">{{ $plato->tipo }}</span>@endif
                    <div class="card-img-overlay" aria-hidden="true"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $plato->nombre }}</h3>
                    @if($plato->restaurante || $plato->empresa)
                        <p class="card-meta"><i class="fa-solid fa-store fa-xs" aria-hidden="true"></i> {{ $plato->restaurante ?? $plato->empresa?->nombre }}</p>
                    @endif
                    @if($plato->ubicacion)
                        <p class="card-meta"><i class="fa-solid fa-location-dot fa-xs" aria-hidden="true"></i> {{ $plato->ubicacion }}</p>
                    @endif
                    @if($plato->precio_promedio)
                        <p class="card-meta" style="color:var(--green-700);font-weight:700;font-size:.95rem;">
                            <i class="fa-solid fa-tag fa-xs" aria-hidden="true"></i>
                            ${{ number_format($plato->precio_promedio, 0, ',', '.') }} COP
                        </p>
                    @endif
                    @if($plato->descripcion)
                        <p class="card-desc">{{ Str::limit($plato->descripcion, 120) }}</p>
                    @endif
                    @if($plato->telefono)
                        <p class="card-meta" style="margin-top:.5rem;"><i class="fa-solid fa-phone fa-xs" aria-hidden="true"></i> {{ $plato->telefono }}</p>
                    @endif
                </div>
            </article>
        @empty
            <div style="grid-column:1/-1;">
                <div class="empty-state">
                    <i class="fa-solid fa-utensils" aria-hidden="true"></i>
                    <p>No se encontraron platos o restaurantes.</p>
                    <a href="{{ route('gastronomia') }}" class="btn btn-outline" style="margin-top:1rem;">Ver todos</a>
                </div>
            </div>
        @endforelse
    </div>
 
</section>
</main>
@endsection