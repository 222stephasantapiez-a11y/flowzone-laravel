@extends('layouts.app')

@section('title', 'Hoteles en Ortega')
@section('body-class', 'no-hero')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
@endpush 
@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>

    var map = L.map('mapaaa').setView([3.9377, -75.2230], 14);

     L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
         attribution: 'yo'
     }).addTo(map);

     L.marker([3.9377, -75.2230]).addTo(map)
         .bindPopup('Aquí estás 📍');

     setTimeout(() => {
         map.invalidateSize();
    }, 100);

</script>
@endpush
@section('title', 'Gastronomia Local')
@section('body-class', 'no-hero')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
@endpush

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

    {{-- Mapa --}}
    <div class="map-container">
        <div id="mapaaa" style="height: 200px;width: 300px;"></div>
    </div>
    
    {{-- Grid de platos/restaurantes --}}
    @if($busqueda)
    <div style="margin-bottom:1.5rem;">
        <div id="mapa-gastronomia" style="height:360px;border-radius:var(--radius,8px);border:1px solid var(--border,#e2e8f0);overflow:hidden;"></div>
        <p id="mapa-coords" style="font-size:.82rem;color:var(--gray);margin-top:.4rem;min-height:1.2em;"></p>
    </div>
    @endif

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

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@php
    $mapaPlatos = $platos->map(function($p) {
        return [
            'nombre'      => $p->nombre,
            'restaurante' => $p->restaurante,
            'ubicacion'   => $p->ubicacion,
            'lat'         => $p->latitud  ? (float)$p->latitud  : null,
            'lng'         => $p->longitud ? (float)$p->longitud : null,
        ];
    })->values();
@endphp
<script>
(function () {
    var busqueda = {!! json_encode($busqueda ?? '') !!};
    var platos   = {!! json_encode($mapaPlatos) !!};

    if (!busqueda || !document.getElementById('mapa-gastronomia')) return;

    var map = L.map('mapa-gastronomia');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    var coordsEl = document.getElementById('mapa-coords');

    function mostrarCoordenadas(lat, lng, label) {
        if (coordsEl) {
            coordsEl.innerHTML = '<i class="fa-solid fa-location-dot fa-xs"></i> ' + label +
                ' &nbsp;|&nbsp; <strong>Lat:</strong> ' + lat.toFixed(6) +
                ' &nbsp;<strong>Lng:</strong> ' + lng.toFixed(6);
        }
    }

    function agregarMarcador(lat, lng, nombre, extra) {
        var m = L.marker([lat, lng]).addTo(map);
        m.bindPopup('<strong>' + nombre + '</strong>' + (extra ? '<br><small>' + extra + '</small>' : ''));
        m.on('click', function() { mostrarCoordenadas(lat, lng, nombre); });
        return m;
    }

    var conCoords = platos.filter(function(p) { return p.lat && p.lng; });

    if (conCoords.length > 0) {
        var bounds = [];
        conCoords.forEach(function(p) {
            agregarMarcador(p.lat, p.lng, p.nombre, p.restaurante || p.ubicacion);
            bounds.push([p.lat, p.lng]);
        });
        mostrarCoordenadas(conCoords[0].lat, conCoords[0].lng, conCoords[0].nombre);
        if (bounds.length === 1) { map.setView(bounds[0], 14); }
        else { map.fitBounds(bounds, { padding: [40, 40] }); }
    } else {
        map.setView([3.9278, -75.2561], 10);
        if (coordsEl) coordsEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-xs"></i> Buscando ubicacion...';

        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(busqueda + ', Colombia'), {
            headers: { 'Accept-Language': 'es' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data && data.length) {
                var lat = parseFloat(data[0].lat);
                var lng = parseFloat(data[0].lon);
                agregarMarcador(lat, lng, busqueda, data[0].display_name);
                map.setView([lat, lng], 13);
                mostrarCoordenadas(lat, lng, busqueda);
            } else {
                if (coordsEl) coordsEl.innerHTML = '<i class="fa-solid fa-circle-exclamation fa-xs" style="color:var(--danger)"></i> Ubicacion no encontrada para "' + busqueda + '"';
            }
        })
        .catch(function() {
            if (coordsEl) coordsEl.innerHTML = '<i class="fa-solid fa-circle-exclamation fa-xs" style="color:var(--danger)"></i> Error al conectar con el servicio de mapas.';
        });
    }
})();
</script>
@endpush
