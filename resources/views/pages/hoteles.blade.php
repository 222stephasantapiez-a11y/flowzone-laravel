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
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
@endpush

@section('content')
<main>

<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow"><i class="fa-solid fa-hotel"></i> Alojamiento</span>
            <h1>Hoteles en Ortega</h1>
            <p>Encuentra el alojamiento perfecto para tu estadía en el corazón del Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Hoteles</span>
            </nav>
        </div>
    </div>
</section>

<section class="container section">

    <div class="filters">
        <form method="GET" action="{{ route('hoteles') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar hoteles..."
                   value="{{ $busqueda }}" aria-label="Buscar hoteles">
            <input type="number" name="precio_max" placeholder="Precio máximo COP"
                   value="{{ $precio_max }}" min="0" aria-label="Precio máximo">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs" aria-hidden="true"></i> Filtrar
            </button>
            <a href="{{ route('hoteles') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    {{-- Mapa --}}
    <div class="map-container">
        <div id="mapaaa" style="height: 200px;width: 300px;"></div>
    </div>
    
    {{-- Grid de hoteles --}}
    @if($busqueda)
    <div style="margin-bottom:1.5rem;">
        <div id="mapa-hoteles" style="height:360px;border-radius:var(--radius,8px);border:1px solid var(--border,#e2e8f0);overflow:hidden;"></div>
        <p id="mapa-coords" style="font-size:.82rem;color:var(--gray);margin-top:.4rem;min-height:1.2em;"></p>
    </div>
    @endif

    <div class="grid">
        @forelse($hoteles as $hotel)
            <article class="card animate-on-scroll">
                <div class="card-img-wrap">
                    @if($hotel->imagen)
                        @php $imgSrc = str_starts_with($hotel->imagen,'http') ? $hotel->imagen : asset('storage/'.$hotel->imagen); @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $hotel->nombre }}" loading="lazy"
                             onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-hotel\'></i></div>'">
                    @else
                        <div class="card-img-fallback"><i class="fa-solid fa-hotel" aria-hidden="true"></i></div>
                    @endif
                    @if($hotel->disponibilidad)
                        <span class="card-badge">Disponible</span>
                    @else
                        <span class="card-badge" style="background:var(--danger);">No disponible</span>
                    @endif
                    <div class="card-img-overlay" aria-hidden="true"></div>
                </div>
                <div class="card-content">
                    <h3>{{ $hotel->nombre }}</h3>
                    @if($hotel->ubicacion)
                        <p class="card-meta"><i class="fa-solid fa-location-dot fa-xs" aria-hidden="true"></i> {{ $hotel->ubicacion }}</p>
                    @endif
                    <p class="card-meta" style="color:var(--green-700);font-weight:700;font-size:.95rem;">
                        <i class="fa-solid fa-tag fa-xs" aria-hidden="true"></i>
                        ${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche
                    </p>
                    @if($hotel->capacidad)
                        <p class="card-meta"><i class="fa-solid fa-users fa-xs" aria-hidden="true"></i> Capacidad: {{ $hotel->capacidad }} personas</p>
                    @endif
                    @if($hotel->descripcion)
                        <p class="card-desc">{{ Str::limit($hotel->descripcion, 110) }}</p>
                    @endif
                    <div class="card-actions">
                        <a href="{{ route('hoteles.detalle', $hotel) }}" class="btn btn-outline btn-sm">
                            Ver más <i class="fa-solid fa-arrow-right fa-xs" aria-hidden="true"></i>
                        </a>
                        @auth
                            <a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-calendar-check fa-xs" aria-hidden="true"></i> Reservar
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-lock fa-xs" aria-hidden="true"></i> Reservar
                            </a>
                        @endauth
                    </div>
                </div>
            </article>
        @empty
            <div style="grid-column:1/-1;">
                <div class="empty-state">
                    <i class="fa-solid fa-hotel" aria-hidden="true"></i>
                    <p>No se encontraron hoteles disponibles.</p>
                    <a href="{{ route('hoteles') }}" class="btn btn-outline" style="margin-top:1rem;">Ver todos</a>
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
    $mapaHoteles = $hoteles->map(function($h) {
        return [
            'nombre'    => $h->nombre,
            'ubicacion' => $h->ubicacion,
            'lat'       => $h->latitud  ? (float)$h->latitud  : null,
            'lng'       => $h->longitud ? (float)$h->longitud : null,
        ];
    })->values();
@endphp
<script>
(function () {
    var busqueda = {!! json_encode($busqueda ?? '') !!};
    var hoteles  = {!! json_encode($mapaHoteles) !!};

    if (!busqueda || !document.getElementById('mapa-hoteles')) return;

    var map = L.map('mapa-hoteles');
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

    var conCoords = hoteles.filter(function(h) { return h.lat && h.lng; });

    if (conCoords.length > 0) {
        var bounds = [];
        conCoords.forEach(function(h) {
            agregarMarcador(h.lat, h.lng, h.nombre, h.ubicacion);
            bounds.push([h.lat, h.lng]);
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
