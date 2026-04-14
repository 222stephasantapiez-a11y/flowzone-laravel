@extends('layouts.app')

@section('title', 'Eventos y Actividades')
@section('body-class', 'no-hero')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
@endpush

@section('content')
<main>

<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-calendar-days"></i> Agenda</span>
            <h1>Eventos y Actividades</h1>
            <p>Descubre los proximos eventos culturales y actividades en Ortega, Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Eventos</span>
            </nav>
        </div>
    </div>
</section>

<section class="container section">

    <div class="filters">
        <form method="GET" action="{{ route('eventos') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar eventos..."
                   value="{{ $busqueda }}" aria-label="Buscar eventos">
            <select name="categoria" aria-label="Filtrar por categoria">
                <option value="">Todas las categorias</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ $categoria_filtro === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs" aria-hidden="true"></i> Filtrar
            </button>
            <a href="{{ route('eventos') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    @if($busqueda)
    <div style="margin-bottom:1.5rem;">
        <div id="mapa-eventos" style="height:360px;border-radius:var(--radius,8px);border:1px solid var(--border,#e2e8f0);overflow:hidden;"></div>
        <p id="mapa-coords" style="font-size:.82rem;color:var(--gray);margin-top:.4rem;min-height:1.2em;"></p>
    </div>
    @endif

    <div class="grid">
        @forelse($eventos as $evento)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($evento->imagen)
                        @php $imgSrc = str_starts_with($evento->imagen,'http') ? $evento->imagen : Storage::disk('public')->url($evento->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $evento->nombre }}"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-calendar-days\'></i></div>'">
                    @else
                        <div class="card-img-fallback"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i></div>
                    @endif
                    @if($evento->categoria)<span class="card-badge">{{ $evento->categoria }}</span>@endif
                    <div class="card-img-overlay" aria-hidden="true"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $evento->nombre }}</h3>
                    <p class="card-meta">
                        <i class="fa-solid fa-calendar fa-xs" aria-hidden="true"></i>
                        {{ $evento->fecha->format('d/m/Y') }}
                        @if($evento->hora)
                            &nbsp;·&nbsp;<i class="fa-solid fa-clock fa-xs" aria-hidden="true"></i>
                            {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                        @endif
                    </p>
                    @if($evento->ubicacion)
                        <p class="card-meta"><i class="fa-solid fa-location-dot fa-xs" aria-hidden="true"></i> {{ $evento->ubicacion }}</p>
                    @endif
                    @if($evento->precio > 0)
                        <p class="card-meta" style="color:var(--green-700);font-weight:700;font-size:.95rem;">
                            <i class="fa-solid fa-ticket fa-xs" aria-hidden="true"></i>
                            ${{ number_format($evento->precio, 0, ',', '.') }} COP
                        </p>
                    @else
                        <p class="card-meta" style="color:var(--success);">
                            <i class="fa-solid fa-circle-check fa-xs" aria-hidden="true"></i> Entrada gratuita
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
                    <p>No hay eventos proximos programados.</p>
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
    $mapaEventos = $eventos->map(function($e) {
        return [
            'nombre'    => $e->nombre,
            'ubicacion' => $e->ubicacion,
        ];
    })->values();
@endphp
<script>
(function () {
    var busqueda = {!! json_encode($busqueda ?? '') !!};
    var eventos  = {!! json_encode($mapaEventos) !!};

    if (!busqueda || !document.getElementById('mapa-eventos')) return;

    var map = L.map('mapa-eventos');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    map.setView([3.9278, -75.2561], 10);

    var coordsEl = document.getElementById('mapa-coords');
    if (coordsEl) coordsEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-xs"></i> Buscando ubicacion...';

    function mostrarCoordenadas(lat, lng, label) {
        if (coordsEl) {
            coordsEl.innerHTML = '<i class="fa-solid fa-location-dot fa-xs"></i> ' + label +
                ' &nbsp;|&nbsp; <strong>Lat:</strong> ' + lat.toFixed(6) +
                ' &nbsp;<strong>Lng:</strong> ' + lng.toFixed(6);
        }
    }

    function geocodificar(query, callback) {
        fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query + ', Colombia'), {
            headers: { 'Accept-Language': 'es' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data && data.length) {
                callback(null, { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon), display: data[0].display_name });
            } else {
                callback('not_found', null);
            }
        })
        .catch(function() { callback('error', null); });
    }

    var eventosConUbicacion = eventos.filter(function(e) { return e.ubicacion; });
    var bounds = [];
    var pendientes = eventosConUbicacion.length;

    if (pendientes === 0) {
        // Sin ubicaciones en eventos, geocodificar la búsqueda directamente
        geocodificar(busqueda, function(err, coords) {
            if (!err && coords) {
                var m = L.marker([coords.lat, coords.lng]).addTo(map);
                m.bindPopup('<strong>' + busqueda + '</strong><br><small>' + coords.display + '</small>');
                map.setView([coords.lat, coords.lng], 13);
                mostrarCoordenadas(coords.lat, coords.lng, busqueda);
            } else {
                if (coordsEl) coordsEl.innerHTML = '<i class="fa-solid fa-circle-exclamation fa-xs" style="color:var(--danger)"></i> Ubicacion no encontrada para "' + busqueda + '"';
            }
        });
    } else {
        eventosConUbicacion.forEach(function(ev) {
            geocodificar(ev.ubicacion, function(err, coords) {
                pendientes--;
                if (!err && coords) {
                    var m = L.marker([coords.lat, coords.lng]).addTo(map);
                    m.bindPopup('<strong>' + ev.nombre + '</strong><br><small>' + ev.ubicacion + '</small>');
                    m.on('click', function() { mostrarCoordenadas(coords.lat, coords.lng, ev.nombre); });
                    bounds.push([coords.lat, coords.lng]);
                }
                if (pendientes === 0) {
                    if (bounds.length > 0) {
                        mostrarCoordenadas(bounds[0][0], bounds[0][1], eventosConUbicacion[0].nombre);
                        if (bounds.length === 1) { map.setView(bounds[0], 14); }
                        else { map.fitBounds(bounds, { padding: [40, 40] }); }
                    } else {
                        // Fallback a geocodificar la búsqueda
                        geocodificar(busqueda, function(err2, coords2) {
                            if (!err2 && coords2) {
                                var m2 = L.marker([coords2.lat, coords2.lng]).addTo(map);
                                m2.bindPopup('<strong>' + busqueda + '</strong>');
                                map.setView([coords2.lat, coords2.lng], 13);
                                mostrarCoordenadas(coords2.lat, coords2.lng, busqueda);
                            } else {
                                if (coordsEl) coordsEl.innerHTML = '<i class="fa-solid fa-circle-exclamation fa-xs" style="color:var(--danger)"></i> Ubicacion no encontrada.';
                            }
                        });
                    }
                }
            });
        });
    }
})();
</script>
@endpush
