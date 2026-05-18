@extends('layouts.app')

@section('title', 'Explorar Mapa — FlowZone')
@section('body-class', 'no-hero maps-page')

@section('content')

<div class="maps-layout">

    {{-- ── Panel lateral ── --}}
    <aside class="maps-sidebar" id="mapsSidebar">

        {{-- Header del panel --}}
        <div class="maps-sidebar-head">
            <div class="maps-sidebar-brand">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Explorar</span>
            </div>
            <p class="maps-sidebar-sub">Ortega, Tolima · Colombia</p>
        </div>

        {{-- Buscador --}}
        <div class="maps-search-wrap">
            <div class="maps-search-box">
                <i class="fa-solid fa-magnifying-glass maps-search-icon"></i>
                <input type="text"
                       id="mapsSearch"
                       class="maps-search-input"
                       placeholder="¿A dónde quieres ir?"
                       autocomplete="off"
                       aria-label="Buscar lugares, hoteles o eventos">
                <button class="maps-search-clear" id="mapsSearchClear" title="Limpiar" style="display:none;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Filtros de tipo --}}
            <div class="maps-filters">
                <button class="maps-filter-btn active" data-tipo="todos">
                    <i class="fa-solid fa-layer-group fa-xs"></i> Todos
                </button>
                <button class="maps-filter-btn" data-tipo="lugar">
                    <i class="fa-solid fa-mountain-sun fa-xs"></i> Lugares
                </button>
                <button class="maps-filter-btn" data-tipo="hotel">
                    <i class="fa-solid fa-hotel fa-xs"></i> Hoteles
                </button>
            </div>
        </div>

        {{-- Contador --}}
        <div class="maps-results-bar">
            <span id="mapsCount">{{ count($puntos) }} sitios encontrados</span>
            <span class="maps-results-dot" id="mapsLoading" style="display:none;">
                <i class="fa-solid fa-circle-notch fa-spin fa-xs"></i> Buscando...
            </span>
        </div>

        {{-- Lista de resultados --}}
        <div class="maps-list" id="mapsList">
            @forelse($puntos as $p)
                <div class="maps-item"
                     data-tipo="{{ $p['tipo'] }}"
                     data-lat="{{ $p['lat'] }}"
                     data-lng="{{ $p['lng'] }}"
                     data-id="{{ $p['tipo'] }}-{{ $p['id'] }}"
                     onclick="flyTo({{ $p['lat'] }}, {{ $p['lng'] }}, '{{ $p['tipo'] }}-{{ $p['id'] }}')">
                    <div class="maps-item-icon maps-icon-{{ $p['tipo'] }}">
                        <i class="fa-solid {{ $p['tipo'] === 'hotel' ? 'fa-hotel' : 'fa-mountain-sun' }}"></i>
                    </div>
                    <div class="maps-item-body">
                        <p class="maps-item-name">{{ $p['nombre'] }}</p>
                        @if($p['ubicacion'])
                            <p class="maps-item-loc">
                                <i class="fa-solid fa-location-dot fa-xs"></i> {{ $p['ubicacion'] }}
                            </p>
                        @endif
                        <div class="maps-item-footer">
                            @if($p['categoria'])
                                <span class="maps-item-tag">{{ $p['categoria'] }}</span>
                            @endif
                            <span class="maps-item-price">{{ $p['precio'] }}</span>
                        </div>
                    </div>
                    <a href="{{ $p['url'] }}" class="maps-item-link" title="Ver detalle" onclick="event.stopPropagation()">
                        <i class="fa-solid fa-arrow-right fa-xs"></i>
                    </a>
                </div>
            @empty
                <div class="maps-empty" id="mapsEmpty">
                    <i class="fa-solid fa-map-pin"></i>
                    <p>No hay sitios con coordenadas registradas aún.</p>
                </div>
            @endforelse
        </div>

    </aside>

    {{-- ── Mapa ── --}}
    <div class="maps-map-wrap">
        <div id="mapsMap"></div>

        {{-- Botón centrar --}}
        <button class="maps-center-btn" id="mapsCenterBtn" title="Centrar mapa">
            <i class="fa-solid fa-crosshairs"></i>
        </button>

        {{-- Toggle sidebar mobile --}}
        <button class="maps-toggle-btn" id="mapsToggleBtn" title="Ver lista">
            <i class="fa-solid fa-list"></i>
            <span>Lista</span>
        </button>
    </div>

</div>

{{-- Popup template (oculto) --}}
<template id="popupTpl">
    <div class="maps-popup">
        <div class="maps-popup-img" id="pp-img"></div>
        <div class="maps-popup-body">
            <span class="maps-popup-tag" id="pp-tag"></span>
            <p class="maps-popup-name" id="pp-name"></p>
            <p class="maps-popup-loc" id="pp-loc"></p>
            <p class="maps-popup-price" id="pp-price"></p>
            <a class="maps-popup-btn" id="pp-url" href="#">Ver detalle →</a>
        </div>
    </div>
</template>

{{-- Leaflet CSS --}}
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ── Layout general ── */
.maps-page { overflow: hidden; }

.maps-layout {
    display: flex;
    height: calc(100vh - var(--navbar-height));
    margin-top: var(--navbar-height);
    overflow: hidden;
}

/* ── Sidebar ── */
.maps-sidebar {
    width: 360px;
    flex-shrink: 0;
    background: var(--white);
    border-right: 1px solid var(--gray-200);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 10;
    box-shadow: 2px 0 12px rgba(0,0,0,.06);
}

.maps-sidebar-head {
    padding: 1.25rem 1.4rem 1rem;
    border-bottom: 1px solid var(--gray-100);
    background: linear-gradient(135deg, var(--green-900), var(--green-800));
    color: var(--white);
}

.maps-sidebar-brand {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 800;
    margin-bottom: .2rem;
}

.maps-sidebar-brand i { color: var(--green-400); font-size: 1.1rem; }

.maps-sidebar-sub {
    font-size: .78rem;
    color: rgba(255,255,255,.55);
    letter-spacing: .04em;
}

/* ── Buscador ── */
.maps-search-wrap {
    padding: 1rem 1.1rem .75rem;
    border-bottom: 1px solid var(--gray-100);
}

.maps-search-box {
    position: relative;
    margin-bottom: .75rem;
}

.maps-search-icon {
    position: absolute;
    left: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: .85rem;
    pointer-events: none;
}

.maps-search-input {
    width: 100%;
    padding: .75rem 2.5rem .75rem 2.4rem;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-full);
    font-family: var(--font-body);
    font-size: .9rem;
    color: var(--gray-900);
    background: var(--gray-50);
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

.maps-search-input:focus {
    border-color: var(--green-700);
    box-shadow: 0 0 0 3px rgba(64,145,108,.1);
    background: var(--white);
}

.maps-search-input::placeholder { color: var(--gray-400); }

.maps-search-clear {
    position: absolute;
    right: .75rem;
    top: 50%;
    transform: translateY(-50%);
    background: var(--gray-200);
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .65rem;
    color: var(--gray-600);
    transition: background .15s;
}

.maps-search-clear:hover { background: var(--gray-400); color: var(--white); }

/* Filtros */
.maps-filters {
    display: flex;
    gap: .4rem;
}

.maps-filter-btn {
    flex: 1;
    padding: .45rem .5rem;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-full);
    background: var(--white);
    font-family: var(--font-body);
    font-size: .75rem;
    font-weight: 600;
    color: var(--gray-600);
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .3rem;
}

.maps-filter-btn:hover {
    border-color: var(--green-600);
    color: var(--green-700);
}

.maps-filter-btn.active {
    background: var(--green-800);
    border-color: var(--green-800);
    color: var(--white);
}

/* ── Barra de resultados ── */
.maps-results-bar {
    padding: .6rem 1.4rem;
    font-size: .75rem;
    color: var(--gray-400);
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--gray-100);
    background: var(--gray-50);
}

.maps-results-dot { color: var(--green-700); }

/* ── Lista ── */
.maps-list {
    flex: 1;
    overflow-y: auto;
    padding: .5rem 0;
}

.maps-list::-webkit-scrollbar { width: 4px; }
.maps-list::-webkit-scrollbar-track { background: transparent; }
.maps-list::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 2px; }

.maps-item {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: .85rem 1.1rem;
    cursor: pointer;
    border-bottom: 1px solid var(--gray-100);
    transition: background .15s;
    position: relative;
}

.maps-item:hover { background: var(--gray-50); }
.maps-item.active { background: var(--green-50); border-left: 3px solid var(--green-700); }

.maps-item-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .9rem;
    flex-shrink: 0;
    color: var(--white);
}

.maps-icon-lugar { background: linear-gradient(135deg, var(--green-700), var(--green-600)); }
.maps-icon-hotel { background: linear-gradient(135deg, #3b82f6, #2563eb); }

.maps-item-body { flex: 1; min-width: 0; }

.maps-item-name {
    font-size: .88rem;
    font-weight: 700;
    color: var(--gray-900);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: .15rem;
}

.maps-item-loc {
    font-size: .75rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: .25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: .3rem;
}

.maps-item-footer {
    display: flex;
    align-items: center;
    gap: .5rem;
    flex-wrap: wrap;
}

.maps-item-tag {
    font-size: .68rem;
    font-weight: 700;
    background: var(--green-50);
    color: var(--green-800);
    padding: .15rem .5rem;
    border-radius: var(--radius-full);
    text-transform: uppercase;
    letter-spacing: .04em;
}

.maps-item-price {
    font-size: .75rem;
    font-weight: 600;
    color: var(--green-700);
}

.maps-item-link {
    width: 28px;
    height: 28px;
    background: var(--gray-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    text-decoration: none;
    flex-shrink: 0;
    transition: background .15s, color .15s;
}

.maps-item-link:hover { background: var(--green-700); color: var(--white); }

.maps-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    color: var(--gray-400);
}

.maps-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; opacity: .3; }
.maps-empty p { font-size: .88rem; }

/* ── Mapa ── */
.maps-map-wrap {
    flex: 1;
    position: relative;
    overflow: hidden;
}

#mapsMap {
    width: 100%;
    height: 100%;
}

.maps-center-btn,
.maps-toggle-btn {
    position: absolute;
    z-index: 500;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    cursor: pointer;
    box-shadow: var(--shadow-md);
    font-family: var(--font-body);
    transition: background .15s, box-shadow .15s;
}

.maps-center-btn {
    bottom: 1.5rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-700);
    font-size: .95rem;
}

.maps-center-btn:hover { background: var(--green-50); color: var(--green-700); }

.maps-toggle-btn {
    bottom: 1.5rem;
    left: 1rem;
    display: none;
    align-items: center;
    gap: .4rem;
    padding: .5rem .9rem;
    font-size: .82rem;
    font-weight: 600;
    color: var(--gray-700);
}

.maps-toggle-btn:hover { background: var(--green-50); }

/* ── Popup Leaflet ── */
.maps-popup {
    min-width: 220px;
    max-width: 260px;
}

.maps-popup-img {
    height: 110px;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    background-size: cover;
    background-position: center;
    border-radius: var(--radius-md) var(--radius-md) 0 0;
    margin: -12px -12px 0;
}

.maps-popup-body { padding: .75rem 0 0; }

.maps-popup-tag {
    display: inline-block;
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    background: var(--green-50);
    color: var(--green-800);
    padding: .15rem .5rem;
    border-radius: var(--radius-full);
    margin-bottom: .4rem;
}

.maps-popup-name {
    font-weight: 700;
    font-size: .92rem;
    color: var(--gray-900);
    margin-bottom: .2rem;
    line-height: 1.3;
}

.maps-popup-loc {
    font-size: .75rem;
    color: var(--gray-400);
    margin-bottom: .2rem;
}

.maps-popup-price {
    font-size: .78rem;
    font-weight: 600;
    color: var(--green-700);
    margin-bottom: .65rem;
}

.maps-popup-btn {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: var(--green-800);
    color: var(--white);
    font-size: .78rem;
    font-weight: 600;
    padding: .4rem .85rem;
    border-radius: var(--radius-full);
    text-decoration: none;
    transition: background .15s;
}

.maps-popup-btn:hover { background: var(--green-700); }

/* ── Responsive ── */
@media (max-width: 768px) {
    .maps-layout { flex-direction: column; }

    .maps-sidebar {
        width: 100%;
        height: 50vh;
        border-right: none;
        border-bottom: 1px solid var(--gray-200);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 20;
        transform: translateY(calc(100% - 56px));
        transition: transform .3s ease;
    }

    .maps-sidebar.open { transform: translateY(0); }

    .maps-map-wrap { height: 100%; }

    .maps-toggle-btn { display: flex; }
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    // ── Datos iniciales desde PHP ──
    var puntosIniciales = @json($puntos);

    // ── Inicializar mapa centrado en Ortega, Tolima ──
    var map = L.map('mapsMap', {
        center: [4.1833, -75.2167],
        zoom: 13,
        zoomControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    // ── Iconos personalizados ──
    function makeIcon(tipo) {
        var color = tipo === 'hotel' ? '#3b82f6' : '#2d6a4f';
        var icon  = tipo === 'hotel' ? 'fa-hotel' : 'fa-mountain-sun';
        var svg = '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="44" viewBox="0 0 36 44">'
            + '<path d="M18 0C8.06 0 0 8.06 0 18c0 13.5 18 26 18 26S36 31.5 36 18C36 8.06 27.94 0 18 0z" fill="' + color + '"/>'
            + '<circle cx="18" cy="18" r="10" fill="rgba(255,255,255,.25)"/>'
            + '</svg>';
        return L.divIcon({
            html: '<div style="position:relative;width:36px;height:44px;">'
                + '<img src="data:image/svg+xml;base64,' + btoa(svg) + '" style="width:36px;height:44px;">'
                + '<i class="fa-solid ' + icon + '" style="position:absolute;top:10px;left:50%;transform:translateX(-50%);color:#fff;font-size:13px;"></i>'
                + '</div>',
            className: '',
            iconSize: [36, 44],
            iconAnchor: [18, 44],
            popupAnchor: [0, -44],
        });
    }

    // ── Marcadores ──
    var markers = {};
    var markerLayer = L.layerGroup().addTo(map);

    function buildPopup(p) {
        var imgStyle = p.imagen
            ? 'background-image:url(' + p.imagen + ');'
            : '';
        return '<div class="maps-popup">'
            + '<div class="maps-popup-img" style="' + imgStyle + '"></div>'
            + '<div class="maps-popup-body">'
            + '<span class="maps-popup-tag">' + (p.categoria || p.tipo) + '</span>'
            + '<p class="maps-popup-name">' + p.nombre + '</p>'
            + (p.ubicacion ? '<p class="maps-popup-loc">📍 ' + p.ubicacion + '</p>' : '')
            + '<p class="maps-popup-price">' + p.precio + '</p>'
            + '<a class="maps-popup-btn" href="' + p.url + '">Ver detalle →</a>'
            + '</div></div>';
    }

    function renderMarkers(puntos) {
        markerLayer.clearLayers();
        markers = {};
        puntos.forEach(function (p) {
            if (!p.lat || !p.lng) return;
            var m = L.marker([p.lat, p.lng], { icon: makeIcon(p.tipo) })
                .bindPopup(buildPopup(p), { maxWidth: 280, className: 'maps-leaflet-popup' });
            m.addTo(markerLayer);
            markers[p.tipo + '-' + p.id] = m;
        });
    }

    renderMarkers(puntosIniciales);

    // ── Fly to desde lista ──
    window.flyTo = function (lat, lng, key) {
        // Quitar active de todos
        document.querySelectorAll('.maps-item').forEach(function (el) {
            el.classList.remove('active');
        });
        // Activar el item clickeado
        var item = document.querySelector('[data-id="' + key + '"]');
        if (item) item.classList.add('active');

        map.flyTo([lat, lng], 16, { duration: 0.8 });
        if (markers[key]) {
            setTimeout(function () { markers[key].openPopup(); }, 850);
        }
    };

    // ── Centrar ──
    document.getElementById('mapsCenterBtn').addEventListener('click', function () {
        map.flyTo([4.1833, -75.2167], 13, { duration: 0.6 });
    });

    // ── Toggle sidebar mobile ──
    var sidebar = document.getElementById('mapsSidebar');
    document.getElementById('mapsToggleBtn').addEventListener('click', function () {
        sidebar.classList.toggle('open');
    });

    // ── Búsqueda ──
    var searchInput  = document.getElementById('mapsSearch');
    var searchClear  = document.getElementById('mapsSearchClear');
    var countEl      = document.getElementById('mapsCount');
    var loadingEl    = document.getElementById('mapsLoading');
    var listEl       = document.getElementById('mapsList');
    var tipoActivo   = 'todos';
    var debounceTimer;

    function renderList(puntos) {
        if (puntos.length === 0) {
            listEl.innerHTML = '<div class="maps-empty"><i class="fa-solid fa-magnifying-glass"></i><p>Sin resultados para tu búsqueda.</p></div>';
            countEl.textContent = '0 sitios encontrados';
            renderMarkers([]);
            return;
        }

        var filtered = tipoActivo === 'todos' ? puntos : puntos.filter(function (p) { return p.tipo === tipoActivo; });
        countEl.textContent = filtered.length + ' sitio' + (filtered.length !== 1 ? 's' : '') + ' encontrado' + (filtered.length !== 1 ? 's' : '');

        listEl.innerHTML = filtered.map(function (p) {
            var iconClass = p.tipo === 'hotel' ? 'fa-hotel' : 'fa-mountain-sun';
            return '<div class="maps-item" data-tipo="' + p.tipo + '" data-lat="' + p.lat + '" data-lng="' + p.lng + '" data-id="' + p.tipo + '-' + p.id + '"'
                + ' onclick="flyTo(' + p.lat + ',' + p.lng + ',\'' + p.tipo + '-' + p.id + '\')">'
                + '<div class="maps-item-icon maps-icon-' + p.tipo + '"><i class="fa-solid ' + iconClass + '"></i></div>'
                + '<div class="maps-item-body">'
                + '<p class="maps-item-name">' + p.nombre + '</p>'
                + (p.ubicacion ? '<p class="maps-item-loc"><i class="fa-solid fa-location-dot fa-xs"></i> ' + p.ubicacion + '</p>' : '')
                + '<div class="maps-item-footer">'
                + (p.categoria ? '<span class="maps-item-tag">' + p.categoria + '</span>' : '')
                + '<span class="maps-item-price">' + p.precio + '</span>'
                + '</div></div>'
                + '<a href="' + p.url + '" class="maps-item-link" title="Ver detalle" onclick="event.stopPropagation()"><i class="fa-solid fa-arrow-right fa-xs"></i></a>'
                + '</div>';
        }).join('');

        renderMarkers(filtered.filter(function (p) { return p.lat && p.lng; }));
    }

    function buscar() {
        var q = searchInput.value.trim();
        searchClear.style.display = q ? 'flex' : 'none';

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            loadingEl.style.display = 'flex';

            fetch('{{ route('maps.buscar') }}?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    loadingEl.style.display = 'none';
                    renderList(data);
                })
                .catch(function () {
                    loadingEl.style.display = 'none';
                });
        }, 300);
    }

    searchInput.addEventListener('input', buscar);

    searchClear.addEventListener('click', function () {
        searchInput.value = '';
        searchClear.style.display = 'none';
        renderList(puntosIniciales);
    });

    // ── Filtros de tipo ──
    document.querySelectorAll('.maps-filter-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.maps-filter-btn').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            tipoActivo = btn.dataset.tipo;

            var q = searchInput.value.trim();
            if (q) {
                buscar();
            } else {
                var filtered = tipoActivo === 'todos'
                    ? puntosIniciales
                    : puntosIniciales.filter(function (p) { return p.tipo === tipoActivo; });
                renderList(filtered);
            }
        });
    });

})();
</script>
@endpush

@endsection