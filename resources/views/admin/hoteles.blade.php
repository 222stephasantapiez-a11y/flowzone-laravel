@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Hoteles')
@section('page-title', 'Hoteles')
@section('page-subtitle', 'Agrega, edita o elimina hoteles del sistema')

@section('content')

{{-- Barra superior --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-hotel" style="color:var(--primary);margin-right:.4rem;"></i>
            Hoteles
        </h2>
        <div style="display:flex; gap:.5rem;">
            @unless(isset($hotel))
                <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Nuevo Hotel
                </button>
                <a href="{{ route('admin.hoteles.export.excel') }}" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('admin.hoteles.export.pdf') }}" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            @endunless
        </div>
    </div>
</div>

{{-- ===================== MODAL HOTEL ===================== --}}
<div id="modal-hotel" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(4px);
    z-index: 999;
    overflow-y: auto;
    padding: 2rem 1rem;
">
    <div style="
        background: #fff;
        border-radius: 1rem;
        max-width: 720px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        overflow: hidden;
    ">
        <div style="
            background: linear-gradient(135deg, var(--green-900), var(--green-700));
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <h3 style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-{{ isset($hotel) ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ isset($hotel) ? 'Editar Hotel: ' . $hotel->nombre : 'Nuevo Hotel' }}
            </h3>
            <button onclick="cerrarModal()" style="
                background: rgba(255,255,255,.15);
                border: none; color: #fff;
                width: 32px; height: 32px;
                border-radius: 50%; cursor: pointer;
                font-size: 1rem; display: flex;
                align-items: center; justify-content: center;
                transition: background .2s;
            " onmouseover="this.style.background='rgba(255,255,255,.3)'"
               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 1.75rem;">
            @isset($hotel)
                <form method="POST" action="{{ route('admin.hoteles.update', $hotel) }}"
                      class="admin-form" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.hoteles.store') }}"
                      class="admin-form" enctype="multipart/form-data">
            @endisset
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required maxlength="150"
                           placeholder="Ej: Hotel Campestre El Paraíso"
                           value="{{ old('nombre', $hotel->nombre ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Precio por noche (COP) *</label>
                    <input type="number" name="precio" required min="0" step="1000"
                           placeholder="Ej: 120000"
                           value="{{ old('precio', $hotel->precio ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción *</label>
                <textarea name="descripcion" rows="3" required
                          placeholder="Describe el hotel, sus características y entorno...">{{ old('descripcion', $hotel->descripcion ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" maxlength="200"
                           placeholder="Ej: Km 2 Vía Ortega-Chaparral"
                           value="{{ old('ubicacion', $hotel->ubicacion ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Capacidad (personas)</label>
                    <input type="number" name="capacidad" min="1"
                           placeholder="Ej: 50"
                           value="{{ old('capacidad', $hotel->capacidad ?? '') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" maxlength="20"
                           placeholder="Ej: 3201234567"
                           value="{{ old('telefono', $hotel->telefono ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" maxlength="150"
                           placeholder="hotel@correo.com"
                           value="{{ old('email', $hotel->email ?? '') }}">
                </div>
            </div>

            {{-- ══════════════ SERVICIOS CON TAGS ══════════════ --}}
            <div class="form-group">
                <label>Servicios
                    <span style="font-size:.78rem;color:var(--gray-400);font-weight:400;">(selecciona o agrega otros)</span>
                </label>

                {{-- Tags predefinidos --}}
                <div id="servicios-tags" style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:.75rem;">
                    @foreach(['WiFi','Piscina','Parqueadero','Restaurante','Aire acondicionado','TV Cable','Gimnasio','Bar','Spa','Lavandería','Recepción 24h','Acceso discapacitados','Desayuno incluido','Habitaciones con vista'] as $srv)
                        <button type="button"
                            class="srv-tag"
                            data-valor="{{ $srv }}"
                            onclick="toggleServicio(this)"
                            style="
                                padding:.35rem .85rem;
                                border-radius:2rem;
                                border:1.5px solid var(--gray-200);
                                background:#fff;
                                color:var(--gray-500);
                                font-size:.8rem;
                                cursor:pointer;
                                transition:all .2s;
                                display:flex;align-items:center;gap:.35rem;
                            ">
                            <i class="fa-solid fa-plus" style="font-size:.65rem;"></i> {{ $srv }}
                        </button>
                    @endforeach
                </div>

                {{-- Campo "Otro" --}}
                <div style="display:flex;gap:.5rem;align-items:center;margin-bottom:.75rem;">
                    <input type="text" id="srv-otro-input"
                           placeholder="Otro servicio personalizado..."
                           style="flex:1;"
                           onkeydown="if(event.key==='Enter'){event.preventDefault();agregarOtro();}">
                    <button type="button" onclick="agregarOtro()" class="btn btn-primary"
                            style="padding:.45rem .9rem;white-space:nowrap;flex-shrink:0;">
                        <i class="fa-solid fa-plus"></i> Agregar
                    </button>
                </div>

                {{-- Tags personalizados --}}
                <div id="servicios-custom" style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:.5rem;"></div>

                {{-- Input hidden --}}
                <input type="hidden" name="servicios" id="servicios-hidden"
                       value="{{ old('servicios', $hotel->servicios ?? '') }}">

                {{-- Resumen --}}
                <div id="servicios-resumen"
                     style="font-size:.78rem;color:var(--gray-400);margin-top:.35rem;">
                    Ningún servicio seleccionado
                </div>
            </div>
            {{-- ═════════════════════════════════════════════════ --}}

            @include('partials.map_picker', [
                'mapId'        => 'hotel',
                'latValue'     => old('latitud', $hotel->latitud ?? ''),
                'lngValue'     => old('longitud', $hotel->longitud ?? ''),
                'addressValue' => old('ubicacion', $hotel->ubicacion ?? ''),
            ])

            @include('partials.imagen_field', [
                'currentImage' => $hotel->imagen ?? null,
                'fieldId'      => 'hotel',
            ])

            <div class="form-group" style="display:flex;align-items:center;gap:.6rem;">
                <input type="checkbox" name="disponibilidad" id="disponibilidad"
                       style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;"
                       {{ old('disponibilidad', $hotel->disponibilidad ?? true) ? 'checked' : '' }}>
                <label for="disponibilidad" style="margin:0;cursor:pointer;">Disponible para reservas</label>
            </div>

            @php $empresasHotel = \App\Models\Empresa::where('tipo_empresa','hotel')->where('aprobado',true)->orderBy('nombre')->get(); @endphp
            @if($empresasHotel->count())
            <div class="form-group">
                <label>Empresa propietaria <span style="font-size:.75rem;font-weight:400;color:var(--gray-400);">(opcional)</span></label>
                <select name="empresa_id" style="width:100%;padding:.55rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;outline:none;">
                    <option value="">— Sin empresa asignada —</option>
                    @foreach($empresasHotel as $emp)
                    <option value="{{ $emp->id }}" {{ old('empresa_id', $hotel->empresa_id ?? '') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-{{ isset($hotel) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($hotel) ? 'Actualizar Hotel' : 'Guardar Hotel' }}
                </button>
                @isset($hotel)
                    <a href="{{ route('admin.hoteles.index') }}" class="btn btn-outline">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                @else
                    <button type="button" onclick="cerrarModal()" class="btn btn-outline">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                @endisset
            </div>

            </form>
        </div>
    </div>
</div>

{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <h2>
            <i class="fa-solid fa-list" style="color:var(--primary);"></i> Hoteles Registrados
        </h2>
        <div style="display:flex; align-items:center; gap:.5rem;">
            <span class="badge badge-info">{{ $hoteles->total() }} total</span>
            <button type="button" onclick="toggleFiltrosHoteles()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
        </div>
    </div>

    <div id="filtrosHoteles" style="display:none; margin-bottom:1rem;">
        <form method="GET" action="{{ route('admin.hoteles.index') }}">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
                <div class="filter-field">
                    <label class="filter-label">Ubicación</label>
                    <input type="text" name="ubicacion" value="{{ request('ubicacion') }}"
                           placeholder="Ej: Ortega..." class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Precio máximo</label>
                    <input type="number" name="precio" value="{{ request('precio') }}"
                           placeholder="Ej: 200000" class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Capacidad mínima</label>
                    <input type="number" name="capacidad" value="{{ request('capacidad') }}"
                           placeholder="Ej: 10" class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Estado</label>
                    <select name="disponibilidad" class="filter-input">
                        <option value="">Todos</option>
                        <option value="1" {{ request('disponibilidad') == '1' ? 'selected' : '' }}>Disponible</option>
                        <option value="0" {{ request('disponibilidad') == '0' ? 'selected' : '' }}>No disponible</option>
                    </select>
                </div>
                <div style="display:flex; gap:.5rem; align-items:flex-end; padding-bottom:1px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Aplicar
                    </button>
                    <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                @php
                    $sort      = $sort ?? 'id';
                    $direction = $direction ?? 'asc';
                @endphp
                <tr>
                    <th>
                        <a href="{{ route('admin.hoteles.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Imagen</th>
                    <th>
                        <a href="{{ route('admin.hoteles.index', array_merge(request()->all(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Nombre @if($sort === 'nombre') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.hoteles.index', array_merge(request()->all(), ['sort' => 'precio', 'direction' => ($sort === 'precio' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Precio / noche @if($sort === 'precio') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.hoteles.index', array_merge(request()->all(), ['sort' => 'ubicacion', 'direction' => ($sort === 'ubicacion' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Ubicación @if($sort === 'ubicacion') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.hoteles.index', array_merge(request()->all(), ['sort' => 'capacidad', 'direction' => ($sort === 'capacidad' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Capacidad @if($sort === 'capacidad') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hoteles as $h)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $h->id }}</td>
                        <td class="td-img">
                            @if($h->imagen)
                                @php
                                    $src = str_starts_with($h->imagen, 'http')
                                        ? $h->imagen
                                        : Storage::disk('public')->url($h->imagen);
                                @endphp
                                <img src="{{ $src }}" alt="{{ $h->nombre }}"
                                     onerror="this.style.display='none'">
                            @else
                                <span style="color:var(--gray-lt);font-size:.78rem;">Sin imagen</span>
                            @endif
                        </td>
                        <td><strong>{{ $h->nombre }}</strong></td>
                        <td>${{ number_format($h->precio, 0, ',', '.') }}</td>
                        <td>{{ $h->ubicacion ?? '—' }}</td>
                        <td>{{ $h->capacidad ?? '—' }}</td>
                        <td>
                            @if($h->disponibilidad)
                                <span class="badge badge-success">Disponible</span>
                            @else
                                <span class="badge badge-danger">No disponible</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:.35rem;align-items:flex-start;">
                                <a href="{{ route('admin.hoteles.edit', $h) }}"
                                   style="background:var(--accent);color:#fff;text-decoration:none;padding:.4rem .8rem;border-radius:var(--radius-sm);font-size:.8rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;width:100%;justify-content:center;box-sizing:border-box;">
                                    <i class="fa-solid fa-pen fa-xs"></i> Editar
                                </a>
                                <button type="button"
                                        style="background:var(--danger);color:#fff;border:none;padding:.4rem .8rem;border-radius:var(--radius-sm);cursor:pointer;font-size:.8rem;font-weight:600;width:100%;display:inline-flex;align-items:center;gap:.3rem;justify-content:center;box-sizing:border-box;"
                                        onclick="abrirConfirmHotel({{ $h->id }}, '{{ addslashes($h->nombre) }}')">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                                <form id="form-delete-hotel-{{ $h->id }}" method="POST"
                                      action="{{ route('admin.hoteles.destroy', $h) }}"
                                      style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay hoteles registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $hoteles, 'perPage' => $perPage])
</div>

{{-- MODAL CONFIRMACIÓN ELIMINAR --}}
<div id="modal-confirm-hotel" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="text-align:center;margin-bottom:1.25rem;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.4rem;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:.4rem;">¿Eliminar hotel?</h3>
            <p style="font-size:.88rem;color:#6b7280;" id="confirm-nombre-hotel"></p>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:1.25rem;">
            <input type="checkbox" id="confirm-check-hotel" style="accent-color:#dc2626;width:16px;height:16px;">
            <span style="font-size:.85rem;color:#991b1b;font-weight:500;">Entiendo que esta acción no se puede deshacer</span>
        </label>
        <div style="display:flex;gap:.75rem;">
            <button type="button" onclick="cerrarConfirmHotel()"
                    style="flex:1;padding:.7rem;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#fff;cursor:pointer;font-size:.88rem;font-weight:600;color:#374151;">
                Cancelar
            </button>
            <button type="button" id="btn-confirmar-delete-hotel" onclick="ejecutarDeleteHotel()" disabled
                    style="flex:1;padding:.7rem;border:none;border-radius:.5rem;background:#dc2626;color:#fff;cursor:pointer;font-size:.88rem;font-weight:600;opacity:.5;transition:opacity .2s;">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    mapPickerInit(
        'hotel',
        {{ old('latitud', isset($hotel) && $hotel->latitud ? $hotel->latitud : 'null') }},
        {{ old('longitud', isset($hotel) && $hotel->longitud ? $hotel->longitud : 'null') }}
    );

    @if(isset($hotel))
        abrirModal();
    @endif

    // ── Inicializar servicios (para edición o old()) ──
    inicializarServicios();
});

// ══════════════════════════════════════════════
//  SERVICIOS CON TAGS
// ══════════════════════════════════════════════
const serviciosSeleccionados = new Set();
const SERVICIOS_PREDEFINIDOS = [
    'WiFi','Piscina','Parqueadero','Restaurante','Aire acondicionado',
    'TV Cable','Gimnasio','Bar','Spa','Lavandería','Recepción 24h',
    'Acceso discapacitados','Desayuno incluido','Habitaciones con vista'
];

function inicializarServicios() {
    const hiddenVal = document.getElementById('servicios-hidden')?.value || '';
    if (!hiddenVal.trim()) return;

    hiddenVal.split(',').map(s => s.trim()).filter(Boolean).forEach(srv => {
        serviciosSeleccionados.add(srv);

        // Si coincide con un tag predefinido, activarlo visualmente
        const tagBtn = [...document.querySelectorAll('.srv-tag')]
            .find(b => b.dataset.valor === srv);
        if (tagBtn) {
            activarTag(tagBtn);
        } else {
            // Es un servicio personalizado, crear su chip
            crearChipCustom(srv);
        }
    });

    actualizarResumen();
}

function toggleServicio(btn) {
    const val = btn.dataset.valor;
    if (serviciosSeleccionados.has(val)) {
        serviciosSeleccionados.delete(val);
        desactivarTag(btn);
    } else {
        serviciosSeleccionados.add(val);
        activarTag(btn);
    }
    actualizarServiciosHidden();
}

function activarTag(btn) {
    btn.style.background    = 'var(--primary)';
    btn.style.borderColor   = 'var(--primary)';
    btn.style.color         = '#fff';
    btn.style.fontWeight    = '600';
    btn.querySelector('i').className = 'fa-solid fa-check';
}

function desactivarTag(btn) {
    btn.style.background    = '#fff';
    btn.style.borderColor   = 'var(--gray-200)';
    btn.style.color         = 'var(--gray-500)';
    btn.style.fontWeight    = '400';
    btn.querySelector('i').className = 'fa-solid fa-plus';
}

function agregarOtro() {
    const input = document.getElementById('srv-otro-input');
    const val   = input.value.trim();
    if (!val) return;
    if (serviciosSeleccionados.has(val)) { input.value = ''; return; }

    serviciosSeleccionados.add(val);
    crearChipCustom(val);
    actualizarServiciosHidden();
    input.value = '';
    input.focus();
}

function crearChipCustom(val) {
    const chip = document.createElement('span');
    chip.dataset.valor = val;
    chip.style.cssText = `
        padding:.35rem .85rem;border-radius:2rem;
        background:var(--primary);color:#fff;font-size:.8rem;
        display:inline-flex;align-items:center;gap:.4rem;font-weight:600;
    `;
    // Escapar val para evitar problemas con comillas en el onclick
    chip.innerHTML = `
        ${val}
        <button type="button"
            onclick="eliminarChipCustom(this)"
            style="background:none;border:none;color:#fff;cursor:pointer;
                   padding:0;font-size:.75rem;display:flex;align-items:center;
                   line-height:1;">
            <i class="fa-solid fa-xmark"></i>
        </button>`;
    document.getElementById('servicios-custom').appendChild(chip);
}

function eliminarChipCustom(btn) {
    const chip = btn.closest('span');
    const val  = chip.dataset.valor;
    serviciosSeleccionados.delete(val);
    chip.remove();
    actualizarServiciosHidden();
}

function actualizarServiciosHidden() {
    const arr = [...serviciosSeleccionados];
    document.getElementById('servicios-hidden').value = arr.join(', ');
    actualizarResumen();
}

function actualizarResumen() {
    const arr     = [...serviciosSeleccionados];
    const resumen = document.getElementById('servicios-resumen');
    if (arr.length) {
        resumen.textContent = `✓ ${arr.length} servicio(s): ${arr.join(', ')}`;
        resumen.style.color = 'var(--primary)';
    } else {
        resumen.textContent = 'Ningún servicio seleccionado';
        resumen.style.color = 'var(--gray-400)';
    }
}
// ══════════════════════════════════════════════

function abrirModal() {
    document.getElementById('modal-hotel').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-hotel').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-hotel').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
        cerrarConfirmHotel();
    }
});

// ── Confirmar eliminar hotel ──
let deleteHotelId = null;

function abrirConfirmHotel(id, nombre) {
    deleteHotelId = id;
    document.getElementById('confirm-nombre-hotel').textContent = 'Vas a eliminar: ' + nombre;
    document.getElementById('confirm-check-hotel').checked = false;
    document.getElementById('btn-confirmar-delete-hotel').disabled = true;
    document.getElementById('btn-confirmar-delete-hotel').style.opacity = '.5';
    const modal = document.getElementById('modal-confirm-hotel');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarConfirmHotel() {
    deleteHotelId = null;
    document.getElementById('modal-confirm-hotel').style.display = 'none';
    document.body.style.overflow = '';
}

function ejecutarDeleteHotel() {
    if (deleteHotelId) document.getElementById('form-delete-hotel-' + deleteHotelId).submit();
}

document.getElementById('confirm-check-hotel').addEventListener('change', function () {
    const btn = document.getElementById('btn-confirmar-delete-hotel');
    btn.disabled      = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
});

document.getElementById('modal-confirm-hotel').addEventListener('click', function (e) {
    if (e.target === this) cerrarConfirmHotel();
});

function toggleFiltrosHoteles() {
    const box = document.getElementById('filtrosHoteles');
    box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
}

window.addEventListener('load', function () {
    if (
        "{{ request('ubicacion') }}" ||
        "{{ request('precio') }}"    ||
        "{{ request('capacidad') }}" ||
        "{{ request('disponibilidad') }}"
    ) {
        document.getElementById('filtrosHoteles').style.display = 'block';
    }
});
</script>
@endpush