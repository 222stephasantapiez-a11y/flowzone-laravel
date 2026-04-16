{{--
    Partial: selector de ubicación con mapa interactivo (Leaflet + Nominatim)
    Variables esperadas:
      $mapId      — prefijo único para IDs (ej: 'hotel', 'lugar', 'gastro')
      $latValue   — valor actual de latitud (puede ser null)
      $lngValue   — valor actual de longitud (puede ser null)
      $addressValue — valor actual de dirección/ubicación para geocodificar (puede ser null)
--}}
@php
    $mapId        = $mapId ?? 'map';
    $latValue     = $latValue ?? '';
    $lngValue     = $lngValue ?? '';
    $addressValue = $addressValue ?? '';
@endphp

@once
@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush
@endonce

<div class="form-group map-picker-group" id="{{ $mapId }}-map-picker">
    <label style="display:flex;align-items:center;gap:.4rem;">
        <i class="fa-solid fa-map-location-dot" style="color:var(--primary);"></i>
        Ubicación en el mapa
    </label>

    {{-- Buscador de dirección --}}
    <div style="display:flex;gap:.5rem;margin-bottom:.6rem;flex-wrap:wrap;">
        <input type="text"
               id="{{ $mapId }}-address-search"
               placeholder="Ingresa una dirección para buscar en el mapa..."
               value="{{ $addressValue }}"
               style="flex:1;min-width:200px;"
               autocomplete="off">
        <button type="button"
                onclick="mapPickerSearch('{{ $mapId }}')"
                class="btn btn-primary btn-sm"
                style="white-space:nowrap;">
            <i class="fa-solid fa-magnifying-glass fa-xs"></i> Buscar
        </button>
    </div>

    {{-- Coordenadas (readonly, se llenan automáticamente) --}}
    <div style="display:flex;gap:.5rem;margin-bottom:.6rem;flex-wrap:wrap;">
        <div style="flex:1;min-width:140px;">
            <label style="font-size:.78rem;color:var(--gray);margin-bottom:.2rem;display:block;">Latitud</label>
            <input type="number" step="0.000001" name="latitud" id="{{ $mapId }}-lat"
                   min="-90" max="90"
                   placeholder="Ej: 4.711000"
                   value="{{ old('latitud', $latValue) }}"
                   oninput="mapPickerUpdateFromInputs('{{ $mapId }}')">
        </div>
        <div style="flex:1;min-width:140px;">
            <label style="font-size:.78rem;color:var(--gray);margin-bottom:.2rem;display:block;">Longitud</label>
            <input type="number" step="0.000001" name="longitud" id="{{ $mapId }}-lng"
                   min="-180" max="180"
                   placeholder="Ej: -74.072100"
                   value="{{ old('longitud', $lngValue) }}"
                   oninput="mapPickerUpdateFromInputs('{{ $mapId }}')">
        </div>
        <div style="display:flex;align-items:flex-end;">
            <button type="button"
                    onclick="mapPickerClear('{{ $mapId }}')"
                    class="btn btn-outline btn-sm"
                    title="Limpiar coordenadas">
                <i class="fa-solid fa-xmark fa-xs"></i>
            </button>
        </div>
    </div>

    {{-- Contenedor del mapa --}}
    <div id="{{ $mapId }}-map-container"
         style="height:320px;border-radius:var(--radius,8px);border:1px solid var(--border,#e2e8f0);overflow:hidden;position:relative;">
        <div id="{{ $mapId }}-map" style="height:100%;width:100%;"></div>
        <div id="{{ $mapId }}-map-loading"
             style="display:none;position:absolute;inset:0;background:rgba(255,255,255,.8);
                    display:flex;align-items:center;justify-content:center;z-index:1000;font-size:.85rem;color:var(--gray);">
            <i class="fa-solid fa-spinner fa-spin" style="margin-right:.4rem;"></i> Buscando...
        </div>
    </div>
    <p class="form-hint" style="margin-top:.4rem;">
        <i class="fa-solid fa-circle-info fa-xs"></i>
        Haz clic en el mapa para ajustar la ubicación manualmente, o busca una dirección arriba.
    </p>
</div>

@once
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
// ── Map Picker Global State ──────────────────────────────────────────────────
window._mapPickers = window._mapPickers || {};

function mapPickerInit(id, lat, lng) {
    const defaultLat = lat || 4.1532;   // Colombia centro
    const defaultLng = lng || -75.2;
    const hasCoords  = lat && lng;

    const map = L.map(id + '-map', { zoomControl: true }).setView(
        [defaultLat, defaultLng], hasCoords ? 14 : 7
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    let marker = null;

    if (hasCoords) {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on('dragend', () => mapPickerSetCoords(id, marker.getLatLng().lat, marker.getLatLng().lng));
    }

    map.on('click', function(e) {
        mapPickerSetCoords(id, e.latlng.lat, e.latlng.lng);
    });

    window._mapPickers[id] = { map, getMarker: () => marker, setMarker: (m) => { marker = m; } };

    // Fix Leaflet tile rendering when inside hidden/collapsed containers
    setTimeout(() => map.invalidateSize(), 300);
}

function mapPickerSetCoords(id, lat, lng) {
    const latRound = Math.round(lat * 1000000) / 1000000;
    const lngRound = Math.round(lng * 1000000) / 1000000;

    document.getElementById(id + '-lat').value = latRound;
    document.getElementById(id + '-lng').value = lngRound;

    const state = window._mapPickers[id];
    if (!state) return;

    const { map } = state;
    let marker = state.getMarker();

    if (marker) {
        marker.setLatLng([latRound, lngRound]);
    } else {
        marker = L.marker([latRound, lngRound], { draggable: true }).addTo(map);
        marker.on('dragend', () => mapPickerSetCoords(id, marker.getLatLng().lat, marker.getLatLng().lng));
        state.setMarker(marker);
    }

    map.setView([latRound, lngRound], Math.max(map.getZoom(), 14));
}

function mapPickerUpdateFromInputs(id) {
    const lat = parseFloat(document.getElementById(id + '-lat').value);
    const lng = parseFloat(document.getElementById(id + '-lng').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        mapPickerSetCoords(id, lat, lng);
    }
}

function mapPickerClear(id) {
    document.getElementById(id + '-lat').value = '';
    document.getElementById(id + '-lng').value = '';
    const state = window._mapPickers[id];
    if (state) {
        const marker = state.getMarker();
        if (marker) {
            state.map.removeLayer(marker);
            state.setMarker(null);
        }
    }
}

async function mapPickerSearch(id) {
    const query = document.getElementById(id + '-address-search').value.trim();
    if (!query) return;

    const loading = document.getElementById(id + '-map-loading');
    if (loading) loading.style.display = 'flex';

    try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&countrycodes=co`;
        const res  = await fetch(url, { headers: { 'Accept-Language': 'es' } });
        const data = await res.json();

        if (data && data.length > 0) {
            const { lat, lon } = data[0];
            mapPickerSetCoords(id, parseFloat(lat), parseFloat(lon));
        } else {
            alert('No se encontró la dirección. Intenta con más detalles o haz clic en el mapa.');
        }
    } catch (e) {
        alert('Error al buscar la dirección. Verifica tu conexión.');
    } finally {
        if (loading) loading.style.display = 'none';
    }
}

// Allow pressing Enter in the search field
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id$="-address-search"]').forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const id = this.id.replace('-address-search', '');
                mapPickerSearch(id);
            }
        });
    });
});
</script>
@endpush
@endonce
