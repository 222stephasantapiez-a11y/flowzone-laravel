@extends('layouts.app')

@section('title', 'Lugares Turísticos')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
@endpush        

@section('body-class', 'no-hero')

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
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
@endpush

@section('content')

<main>

<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-map-location-dot"></i> Destinos</span>
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

<section class="container section">

    <div class="filters">
        <form method="GET" action="{{ route('lugares') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar dirección o lugar..."
                   value="{{ $busqueda }}" aria-label="Buscar lugares">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs" aria-hidden="true"></i> Filtrar
            </button>
            <a href="{{ route('lugares') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>
        
    {{-- Mapa --}}
    <div class="map-container"> 
        <div id="mapaaa" style="height: 200px;width: 300px;"></div>
    </div>
    
    {{-- Grid de lugares --}}

    @if($busqueda)
    <div style="margin-bottom:1.5rem;">
        <div id="mapa-lugares" style="height:360px;border-radius:var(--radius,8px);border:1px solid var(--border,#e2e8f0);overflow:hidden;"></div>
        <p id="mapa-coords" style="font-size:.82rem;color:var(--gray);margin-top:.4rem;min-height:1.2em;"></p>
    </div>
    @endif

    <div class="grid">
        @forelse($lugares as $lugar)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($lugar->imagen)
                        @php $imgSrc = str_starts_with($lugar->imagen,'http') ? $lugar->imagen : asset('storage/'.$lugar->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $lugar->nombre }}" loading="lazy"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-mountain-sun\'></i></div>'">
                    @else
                        <div class="card-img-fallback">
                            <i ></i>
                        </div>
                        <div class="card-img-fallback"><i></i></div>
                    @endif
                    @if($lugar->categoria)<span class="card-badge">{{ $lugar->categoria }}</span>@endif
                    <div class="card-img-overlay" aria-hidden="true"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $lugar->nombre }}</h3>
                    @if($lugar->ubicacion)
                        <p class="card-meta"><i class="fa-solid fa-location-dot fa-xs" aria-hidden="true"></i> {{ $lugar->ubicacion }}</p>
                    @endif
                    @if($lugar->precio_entrada > 0)
                        <p class="card-meta" style="color:var(--green-700);font-weight:700;font-size:.95rem;">
                            <i class="fa-solid fa-ticket fa-xs" aria-hidden="true"></i>
                            ${{ number_format($lugar->precio_entrada, 0, ',', '.') }} COP
                        </p>
                    @else
                        <p class="card-meta" style="color:var(--success);">
                            <i class="fa-solid fa-circle-check fa-xs" aria-hidden="true"></i> Entrada gratuita
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

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@php
    $mapaLugares = $lugares->map(function($l) {
        return [
            'nombre'    => $l->nombre,
            'ubicacion' => $l->ubicacion,
            'categoria' => $l->categoria,
            'lat'       => $l->latitud  ? (float)$l->latitud  : null,
            'lng'       => $l->longitud ? (float)$l->longitud : null,
        ];
    })->values();
@endphp
<script>
(function () {
    var busqueda = {!! json_encode($busqueda ?? '') !!};
    var lugares  = {!! json_encode($mapaLugares) !!};

    if (!busqueda || !document.getElementById('mapa-lugares')) return;

    var map = L.map('mapa-lugares');
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

    var conCoords = lugares.filter(function(l) { return l.lat && l.lng; });

    if (conCoords.length > 0) {
        var bounds = [];
        conCoords.forEach(function(l) {
            agregarMarcador(l.lat, l.lng, l.nombre, l.ubicacion || l.categoria);
            bounds.push([l.lat, l.lng]);
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
