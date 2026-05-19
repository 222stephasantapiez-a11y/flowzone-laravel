@extends('layouts.empresa')

@section('page-title', 'Generador de Planes')
@section('page-subtitle')Planes turísticos de {{ $empresa->nombre }}@endsection

@section('topbar-actions')
    <a href="{{ route('planes.publico') }}" target="_blank" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-globe fa-xs"></i> Ver en sitio
    </a>
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $tipo = $empresa->tipo_empresa ?? 'otro';
    $tipoLabels = ['hotel'=>'Hotel/Hospedaje','restaurante'=>'Restaurante','agencia_turismo'=>'Agencia de turismo','transporte'=>'Transporte','artesanias'=>'Artesanías','otro'=>'Otro'];
@endphp

{{-- ══ GENERADOR INTELIGENTE ══ --}}
<div class="admin-section" style="margin-bottom:1.5rem;border:2px dashed #b7e4c7;background:#f8fffe;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;">
        <div>
            <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;margin:0 0 .25rem;">
                <i class="fa-solid fa-wand-magic-sparkles" style="color:var(--green-600);"></i>
                Generador de Planes — <span style="color:var(--green-700);">{{ $tipoLabels[$tipo] ?? 'Tu empresa' }}</span>
            </h2>
            <p style="font-size:.82rem;color:var(--gray-400);margin:0;">
                @if($tipo === 'hotel') Genera planes con habitaciones, gastronomía y lugares turísticos.
                @elseif($tipo === 'restaurante') Genera planes con tus platos, eventos y lugares cercanos.
                @elseif(in_array($tipo, ['agencia_turismo','transporte'])) Genera planes de recorrido con hoteles, lugares y eventos.
                @else Genera planes combinando los servicios disponibles.
                @endif
            </p>
        </div>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
            <button type="button" id="btnFiltros" onclick="toggleFiltros()"
                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;font-size:.82rem;font-weight:600;border-radius:var(--radius-full);border:1.5px solid var(--gray-200);background:#fff;color:var(--gray-600);cursor:pointer;">
                <i class="fa-solid fa-sliders fa-xs"></i> Filtros
            </button>
            <button type="button" id="btnGenerar" onclick="generarPlan()"
                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:var(--green-700);color:#fff;cursor:pointer;box-shadow:0 2px 8px rgba(45,106,79,.25);">
                <i class="fa-solid fa-dice fa-xs"></i> Generar plan
            </button>
            <button type="button" id="btnLimpiar" onclick="limpiarPlan()"
                    style="display:none;align-items:center;gap:.4rem;padding:.5rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:1.5px solid #f87171;background:#fff;color:#c0392b;cursor:pointer;">
                <i class="fa-solid fa-xmark fa-xs"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- Panel de filtros opcionales --}}
    <div id="panelFiltros" style="display:none;background:#f0fdf4;border-radius:var(--radius-md);padding:1rem 1.25rem;margin-bottom:1rem;border:1px solid #b7e4c7;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;align-items:end;">
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--green-700);text-transform:uppercase;margin-bottom:.3rem;">Fecha inicio</label>
                <input type="date" id="f_fecha_inicio" style="width:100%;padding:.45rem .75rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);font-size:.85rem;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--green-700);text-transform:uppercase;margin-bottom:.3rem;">Fecha fin</label>
                <input type="date" id="f_fecha_fin" style="width:100%;padding:.45rem .75rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);font-size:.85rem;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--green-700);text-transform:uppercase;margin-bottom:.3rem;">Hora inicio</label>
                <input type="time" id="f_hora_inicio" style="width:100%;padding:.45rem .75rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);font-size:.85rem;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--green-700);text-transform:uppercase;margin-bottom:.3rem;">Hora fin</label>
                <input type="time" id="f_hora_fin" style="width:100%;padding:.45rem .75rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);font-size:.85rem;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--green-700);text-transform:uppercase;margin-bottom:.3rem;">Radio (km)</label>
                <input type="number" id="f_radio" value="50" min="1" max="200"
                       style="width:100%;padding:.45rem .75rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);font-size:.85rem;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--green-700);text-transform:uppercase;margin-bottom:.3rem;">Ubicación</label>
                <button type="button" onclick="usarMiUbicacion()"
                        style="width:100%;padding:.45rem .75rem;font-size:.82rem;font-weight:600;border-radius:var(--radius-md);border:1.5px solid #b7e4c7;background:#fff;color:var(--green-700);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.4rem;">
                    <i class="fa-solid fa-location-crosshairs fa-xs"></i>
                    <span id="btnUbicacionLabel">Usar mi ubicación</span>
                </button>
                <input type="hidden" id="f_lat">
                <input type="hidden" id="f_lng">
            </div>
        </div>
    </div>

    {{-- Resultado generado --}}
    <div id="planGenerado" style="display:none;">
        <div style="overflow:hidden;border-radius:var(--radius-md);border:1px solid #b7e4c7;">
            <div style="background:var(--green-700);color:#fff;padding:.75rem 1.25rem;text-align:center;font-weight:700;font-size:.9rem;">
                <i class="fa-solid fa-tag fa-xs"></i> ¡PLAN ESPECIAL CON 20% DE DESCUENTO!
            </div>

            {{-- Cards dinámicas según tipo --}}
            <div id="planCards" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;padding:1.25rem;background:#fff;">
                {{-- Se llenan por JS --}}
            </div>

            {{-- Footer precio + formulario guardar --}}
            <div style="background:#1e293b;color:#fff;padding:1.1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem;">
                <div>
                    <span id="planSubtotal" style="text-decoration:line-through;color:#94a3b8;margin-right:.75rem;"></span>
                    <span style="background:#ef4444;padding:2px 8px;border-radius:5px;font-size:.75rem;font-weight:700;">-20% DCTO</span>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <div style="text-align:right;">
                        <div style="font-size:.78rem;color:#94a3b8;">Precio Total:</div>
                        <div id="planPrecioFinal" style="font-size:1.6rem;font-weight:900;color:#fbbf24;"></div>
                    </div>
                    <button type="button" onclick="abrirModalGuardar()"
                            style="padding:.5rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:#fbbf24;color:#1e293b;cursor:pointer;">
                        <i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar plan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="planVacio" style="text-align:center;color:#64748b;padding:.5rem 0;font-size:.88rem;">
        Haz clic en <strong>Generar plan</strong> para crear una combinación con 20% de descuento.
    </div>
</div>

{{-- ══ MODAL GUARDAR PLAN ══ --}}
<div id="modalGuardar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;overflow-y:auto;padding:1.5rem 1rem;">
    <div style="background:#fff;border-radius:var(--radius-lg);max-width:560px;margin:0 auto;box-shadow:0 24px 64px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100);">
            <h3 style="margin:0;font-size:1rem;font-weight:700;color:var(--gray-900);">Guardar plan turístico</h3>
            <button onclick="cerrarModalGuardar()" style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--gray-400);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('empresa.planes.guardar') }}" enctype="multipart/form-data" class="admin-form" style="padding:1.5rem;">
            @csrf
            {{-- Campos ocultos del plan generado --}}
            <input type="hidden" name="evento_id"      id="g_evento_id">
            <input type="hidden" name="gastronomia_id" id="g_gastronomia_id">
            <input type="hidden" name="hotel_id"       id="g_hotel_id">
            <input type="hidden" name="lugar_id"       id="g_lugar_id">
            <input type="hidden" name="habitacion_id"  id="g_habitacion_id">
            <input type="hidden" name="subtotal"       id="g_subtotal">
            <input type="hidden" name="descuento"      id="g_descuento">
            <input type="hidden" name="precio_final"   id="g_precio_final">

            <div class="form-group">
                <label>Título del plan *</label>
                <input type="text" name="titulo" required maxlength="200"
                       placeholder="Ej: Fin de semana en Ortega" value="{{ old('titulo') }}">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" maxlength="1000"
                          placeholder="Describe qué incluye este plan...">{{ old('descripcion') }}</textarea>
            </div>
            <div class="form-group">
                <label>Imagen del plan</label>
                <input type="file" name="imagen_file" accept="image/*" style="margin-bottom:.4rem;">
                <input type="url" name="imagen_url" placeholder="O pega una URL: https://..."
                       style="width:100%;padding:.6rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" name="publicado" value="1" id="g_publicado"
                       style="accent-color:var(--green-700);width:16px;height:16px;">
                <label for="g_publicado" style="cursor:pointer;font-size:.9rem;font-weight:500;margin:0;">
                    Publicar en el sitio web ahora
                </label>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
                <button type="button" onclick="cerrarModalGuardar()" class="btn btn-outline">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ PLANES GUARDADOS ══ --}}
<div class="admin-section">
    <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-star" style="color:#fbbf24;"></i> Mis planes guardados
        <span class="badge badge-info">{{ $planes->count() }}</span>
        @if($planes->where('publicado',true)->count())
        <span class="badge badge-success">{{ $planes->where('publicado',true)->count() }} publicados</span>
        @endif
    </h2>

    @if($planes->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-map-location-dot"></i>
            <p>No has guardado planes aún. Genera uno arriba y guárdalo.</p>
        </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;">
        @foreach($planes as $p)
        @php
            $imgSrc = $p->imagen
                ? (Str::startsWith($p->imagen,'http') ? $p->imagen : Storage::url($p->imagen))
                : null;
        @endphp
        <div style="border:1px solid var(--gray-200);border-radius:var(--radius-lg);overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.06);">
            @if($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $p->titulo }}" style="width:100%;height:140px;object-fit:cover;">
            @else
            <div style="width:100%;height:80px;background:linear-gradient(135deg,var(--green-700),var(--green-900));display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-map-location-dot fa-2x" style="color:rgba(255,255,255,.4);"></i>
            </div>
            @endif

            <div style="padding:1rem;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;margin-bottom:.5rem;">
                    <h3 style="font-size:.92rem;font-weight:700;color:var(--gray-900);margin:0;">{{ $p->titulo }}</h3>
                    @if($p->publicado)
                        <span class="badge badge-success" style="white-space:nowrap;font-size:.72rem;">Publicado</span>
                    @else
                        <span class="badge badge-warning" style="white-space:nowrap;font-size:.72rem;">Borrador</span>
                    @endif
                </div>

                @if($p->descripcion)
                <p style="font-size:.82rem;color:var(--gray-500);margin-bottom:.75rem;">{{ Str::limit($p->descripcion, 80) }}</p>
                @endif

                {{-- Componentes del plan --}}
                <div style="display:flex;flex-direction:column;gap:.25rem;margin-bottom:.75rem;font-size:.8rem;">
                    @if($p->habitacion)
                    <div><span style="color:var(--green-700);">🛏</span> {{ $p->habitacion->nombre }} — {{ $p->hotel?->nombre }}</div>
                    @elseif($p->hotel)
                    <div><span style="color:var(--green-700);">🏨</span> {{ $p->hotel->nombre }}</div>
                    @endif
                    @if($p->gastronomia)
                    <div><span style="color:#f97316;">🍽</span> {{ $p->gastronomia->nombre }}</div>
                    @endif
                    @if($p->evento)
                    <div><span style="color:#6366f1;">🎭</span> {{ $p->evento->nombre }}</div>
                    @endif
                    @if($p->lugar)
                    <div><span style="color:#3b82f6;">📍</span> {{ $p->lugar->nombre }}</div>
                    @endif
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                    <div>
                        <span style="text-decoration:line-through;color:#94a3b8;font-size:.78rem;">${{ number_format($p->subtotal, 0) }}</span>
                        <span style="font-weight:700;color:var(--green-700);font-size:1rem;margin-left:.4rem;">${{ number_format($p->precio_final, 0) }}</span>
                    </div>
                    <span style="background:#fee2e2;color:#dc2626;border-radius:2rem;padding:.15rem .5rem;font-size:.72rem;font-weight:700;">-20%</span>
                </div>

                <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                    <form method="POST" action="{{ route('empresa.planes.publicar', $p) }}" style="display:inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-small {{ $p->publicado ? 'btn-warning' : 'btn-success' }}">
                            <i class="fa-solid fa-{{ $p->publicado ? 'eye-slash' : 'eye' }} fa-xs"></i>
                            {{ $p->publicado ? 'Ocultar' : 'Publicar' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('empresa.planes.destroy', $p) }}" style="display:inline"
                          onsubmit="return confirm('¿Eliminar este plan?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-small btn-delete">
                            <i class="fa-solid fa-trash fa-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script>
const GENERAR_URL = "{{ route('empresa.planes.generar') }}";
const CSRF        = "{{ csrf_token() }}";
const TIPO_EMPRESA = "{{ $tipo }}";

let planActual = null;

// Colores por tipo de componente
const colores = {
    habitacion: { bg:'#f0fdf4', border:'#22c55e', color:'#16a34a', emoji:'🛏' },
    hotel:      { bg:'#f0fdf4', border:'#22c55e', color:'#16a34a', emoji:'🏨' },
    gastronomia:{ bg:'#fff7ed', border:'#f97316', color:'#ea580c', emoji:'🍽' },
    evento:     { bg:'#f5f3ff', border:'#6366f1', color:'#4f46e5', emoji:'🎭' },
    lugar:      { bg:'#eff6ff', border:'#3b82f6', color:'#2563eb', emoji:'📍' },
};

function crearCard(tipo, nombre, precio) {
    const c = colores[tipo] || colores.hotel;
    return `<div style="padding:.85rem 1rem;border-left:4px solid ${c.border};background:${c.bg};border-radius:0 var(--radius-md) var(--radius-md) 0;">
        <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:${c.color};margin-bottom:.2rem;">${c.emoji} ${tipo.charAt(0).toUpperCase()+tipo.slice(1)}</div>
        <div style="font-weight:700;color:var(--gray-900);font-size:.9rem;">${nombre}</div>
        ${precio > 0 ? `<div style="font-size:.78rem;color:${c.color};margin-top:.15rem;">$${precio.toLocaleString('es-CO')}</div>` : ''}
    </div>`;
}

function generarPlan() {
    const btn = document.getElementById('btnGenerar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-xs"></i> Generando...';

    const body = {};
    const fi = document.getElementById('f_fecha_inicio')?.value;
    const ff = document.getElementById('f_fecha_fin')?.value;
    const hi = document.getElementById('f_hora_inicio')?.value;
    const hf = document.getElementById('f_hora_fin')?.value;
    const lat = document.getElementById('f_lat')?.value;
    const lng = document.getElementById('f_lng')?.value;
    const radio = document.getElementById('f_radio')?.value;
    if (fi) body.fecha_inicio = fi;
    if (ff) body.fecha_fin = ff;
    if (hi) body.hora_inicio = hi;
    if (hf) body.hora_fin = hf;
    if (lat && lng) { body.ubicacion_lat = lat; body.ubicacion_lng = lng; }
    if (radio) body.radio_km = radio;

    fetch(GENERAR_URL, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(body),
    })
    .then(r => r.json())
    .then(d => {
        if (d.error) { alert(d.error); return; }
        planActual = d;

        // Construir cards según tipo de empresa
        let cards = '';
        if (d.habitacion) cards += crearCard('habitacion', d.habitacion.nombre, d.habitacion.precio);
        else if (d.hotel) cards += crearCard('hotel', d.hotel.nombre, d.hotel.precio);
        if (d.gastronomia) cards += crearCard('gastronomia', d.gastronomia.nombre, d.gastronomia.precio);
        if (d.evento)      cards += crearCard('evento', d.evento.nombre, d.evento.precio);
        if (d.lugar)       cards += crearCard('lugar', d.lugar.nombre, d.lugar.precio);
        // Platos extra para restaurante
        if (d.platos_extra) {
            d.platos_extra.forEach(p => { cards += crearCard('gastronomia', p.nombre, p.precio); });
        }

        document.getElementById('planCards').innerHTML = cards;
        const subtotal = Number(d.subtotal ?? 0); const precioFinal = Number(d.precioFinal ?? 0);
        document.getElementById('planSubtotal').textContent    = 'Antes: $' + subtotal.toLocaleString('es-CO');
        document.getElementById('planPrecioFinal').textContent = '$' + precioFinal.toLocaleString('es-CO');

        document.getElementById('planVacio').style.display    = 'none';
        document.getElementById('planGenerado').style.display = 'block';
        document.getElementById('btnLimpiar').style.display   = 'inline-flex';
    })
    .catch(e => alert('Error al generar el plan: ' + e.message))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-dice fa-xs"></i> Generar plan';
    });
}

function limpiarPlan() {
    planActual = null;
    document.getElementById('planGenerado').style.display = 'none';
    document.getElementById('planVacio').style.display    = 'block';
    document.getElementById('btnLimpiar').style.display   = 'none';
}

function toggleFiltros() {
    const p = document.getElementById('panelFiltros');
    p.style.display = p.style.display === 'none' ? 'block' : 'none';
}

function usarMiUbicacion() {
    const lbl = document.getElementById('btnUbicacionLabel');
    if (!navigator.geolocation) { alert('Tu navegador no soporta geolocalización.'); return; }
    lbl.textContent = 'Obteniendo...';
    navigator.geolocation.getCurrentPosition(
        pos => {
            document.getElementById('f_lat').value = pos.coords.latitude;
            document.getElementById('f_lng').value = pos.coords.longitude;
            lbl.textContent = `✓ ${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
        },
        () => { lbl.textContent = 'No se pudo obtener'; }
    );
}

function abrirModalGuardar() {
    if (!planActual) return;
    // Poblar campos ocultos
    document.getElementById('g_evento_id').value      = planActual.evento?.id || '';
    document.getElementById('g_gastronomia_id').value = planActual.gastronomia?.id || '';
    document.getElementById('g_hotel_id').value       = planActual.hotel?.id || '';
    document.getElementById('g_lugar_id').value       = planActual.lugar?.id || '';
    document.getElementById('g_habitacion_id').value  = planActual.habitacion?.id || '';
    document.getElementById('g_subtotal').value       = planActual.subtotal;
    document.getElementById('g_descuento').value      = planActual.descuento;
    document.getElementById('g_precio_final').value   = planActual.precioFinal;

    document.getElementById('modalGuardar').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalGuardar() {
    document.getElementById('modalGuardar').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modalGuardar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalGuardar();
});
</script>
@endpush

@endsection
