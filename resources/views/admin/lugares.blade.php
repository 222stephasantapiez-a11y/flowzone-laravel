@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Lugares')
@section('page-title', 'Lugares')
@section('page-subtitle', 'Administra los destinos turísticos del sistema')

@section('content')

{{-- Barra superior --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-map-location-dot" style="color:var(--primary);margin-right:.4rem;"></i>
            Lugares
        </h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Lugar
            </button>
            <a href="{{ route('admin.lugares.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.lugares.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            @include('partials.import_modal', [
                'importRoute' => 'admin.lugares.import.excel',
                'sampleFile'  => 'ejemplo_lugares.xlsx',
                'modalId'     => 'importLugares',
                'columns'     => [
                    'nombre'         => 'Nombre del lugar (requerido)',
                    'descripcion'    => 'Descripción del lugar (requerido)',
                    'ubicacion'      => 'Ubicación o dirección',
                    'categoria'      => 'Categoría (Naturaleza, Histórico...)',
                    'precio_entrada' => 'Precio de entrada en COP (0 = gratuito)',
                    'horario'        => 'Horario de atención',
                ],
            ])
        </div>
    </div>
</div>

{{-- ===================== MODAL ===================== --}}
<div id="modal-lugar" style="
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
                <i class="fa-solid fa-{{ isset($lugar) ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ isset($lugar) ? 'Editar Lugar: ' . $lugar->nombre : 'Nuevo Lugar' }}
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

        <div style="padding: 1.75rem;max-height:calc(90vh - 120px);overflow-y:auto;">
            @isset($lugar)
                <form method="POST" action="{{ route('admin.lugares.update', $lugar) }}" class="admin-form" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.lugares.store') }}" class="admin-form" enctype="multipart/form-data">
            @endisset
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required maxlength="150"
                           placeholder="Ej: Cascada El Salto"
                           value="{{ old('nombre', $lugar->nombre ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Categoría *</label>
                    <input type="text" name="categoria" required maxlength="100"
                           placeholder="Ej: Naturaleza, Histórico..."
                           value="{{ old('categoria', $lugar->categoria ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción *</label>
                <textarea name="descripcion" rows="3" required
                          placeholder="Describe el lugar, su historia y atractivos...">{{ old('descripcion', $lugar->descripcion ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion"
                           placeholder="Ej: Vereda El Limón, Ortega"
                           value="{{ old('ubicacion', $lugar->ubicacion ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Horario</label>
                    <input type="text" name="horario"
                           placeholder="Ej: Lun-Dom 8am-5pm"
                           value="{{ old('horario', $lugar->horario ?? '') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Precio Entrada (COP)</label>
                    <input type="number" step="0.01" name="precio_entrada"
                           placeholder="0 = gratuito"
                           value="{{ old('precio_entrada', $lugar->precio_entrada ?? '0') }}">
                </div>
            </div>

            @include('partials.map_picker', [
                'mapId'        => 'lugar',
                'latValue'     => old('latitud', $lugar->latitud ?? ''),
                'lngValue'     => old('longitud', $lugar->longitud ?? ''),
                'addressValue' => old('ubicacion', $lugar->ubicacion ?? ''),
            ])

            @include('partials.imagen_field', [
                'currentImage' => $lugar->imagen ?? null,
                'fieldId'      => 'lugar',
            ])

            <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-{{ isset($lugar) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($lugar) ? 'Actualizar Lugar' : 'Guardar Lugar' }}
                </button>
                @isset($lugar)
                    <a href="{{ route('admin.lugares.index') }}" class="btn btn-outline">
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
            <i class="fa-solid fa-list" style="color:var(--primary);"></i> Lugares Registrados
        </h2>
        <div style="display:flex; align-items:center; gap:.5rem;">
            <span class="badge badge-info">{{ $lugares->total() }} total</span>
            <button type="button" onclick="toggleFiltrosLugares()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
        </div>
    </div>

    <div id="filtrosLugares" style="display:none; padding: 1rem 0 .5rem;">
        <form method="GET" action="{{ route('admin.lugares.index') }}">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
                <div class="filter-field">
                    <label class="filter-label">Nombre</label>
                    <input type="text" name="nombre" value="{{ request('nombre') }}"
                           placeholder="Buscar por nombre..." class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Categoría</label>
                    <select name="categoria" class="filter-input">
                        <option value="">Todas</option>
                        <option value="natural"   {{ request('categoria') == 'natural'   ? 'selected' : '' }}>Natural</option>
                        <option value="cultural"  {{ request('categoria') == 'cultural'  ? 'selected' : '' }}>Cultural</option>
                        <option value="histórico" {{ request('categoria') == 'histórico' ? 'selected' : '' }}>Histórico</option>
                        <option value="aventura"  {{ request('categoria') == 'aventura'  ? 'selected' : '' }}>Aventura</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label class="filter-label">Ubicación</label>
                    <input type="text" name="ubicacion" value="{{ request('ubicacion') }}"
                           placeholder="Ej: Ortega..." class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Precio entrada máx.</label>
                    <input type="number" name="precio_entrada" value="{{ request('precio_entrada') }}"
                           placeholder="Ej: 10000" class="filter-input">
                </div>
                <div style="display:flex; gap:.5rem; align-items:flex-end; padding-bottom:1px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Aplicar
                    </button>
                    <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary btn-sm">
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
                        <a href="{{ route('admin.lugares.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Imagen</th>
                    <th>
                        <a href="{{ route('admin.lugares.index', array_merge(request()->all(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Nombre @if($sort === 'nombre') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.lugares.index', array_merge(request()->all(), ['sort' => 'categoria', 'direction' => ($sort === 'categoria' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Categoría @if($sort === 'categoria') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.lugares.index', array_merge(request()->all(), ['sort' => 'ubicacion', 'direction' => ($sort === 'ubicacion' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Ubicación @if($sort === 'ubicacion') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.lugares.index', array_merge(request()->all(), ['sort' => 'precio_entrada', 'direction' => ($sort === 'precio_entrada' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Precio Entrada @if($sort === 'precio_entrada') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lugares as $l)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $l->id }}</td>
                        <td class="td-img">
                            @if($l->imagen)
                                @php
                                    $src = str_starts_with($l->imagen, 'http')
                                        ? $l->imagen
                                        : Storage::disk('public')->url($l->imagen);
                                @endphp
                                <img src="{{ $src }}" alt="{{ $l->nombre }}"
                                     onerror="this.style.display='none'">
                            @else
                                <span style="color:var(--gray-lt);font-size:.78rem;">Sin imagen</span>
                            @endif
                        </td>
                        <td><strong>{{ $l->nombre }}</strong></td>
                        <td><span class="badge badge-info">{{ $l->categoria }}</span></td>
                        <td>{{ $l->ubicacion ?? '—' }}</td>
                        <td>
                            @if($l->precio_entrada > 0)
                                ${{ number_format($l->precio_entrada, 0) }}
                            @else
                                <span class="badge badge-success">Gratuito</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;flex-wrap:wrap;gap:.35rem;align-items:center;">
                                <a href="{{ route('admin.lugares.edit', $l) }}" class="btn-small btn-edit btn-sm">
                                    <i class="fa-solid fa-pen fa-xs"></i> Editar
                                </a>
                                <button type="button" class="btn-small btn-delete btn-sm"
                                        onclick="abrirConfirmLugar({{ $l->id }}, '{{ addslashes($l->nombre) }}')">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                                <form id="form-delete-lugar-{{ $l->id }}" method="POST"
                                      action="{{ route('admin.lugares.destroy', $l) }}"
                                      style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay lugares registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $lugares, 'perPage' => $perPage])

</div>

{{-- MODAL CONFIRMACIÓN ELIMINAR --}}
<div id="modal-confirm-lugar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="text-align:center;margin-bottom:1.25rem;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.4rem;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:.4rem;">¿Eliminar lugar?</h3>
            <p style="font-size:.88rem;color:#6b7280;" id="confirm-nombre-lugar"></p>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:1.25rem;">
            <input type="checkbox" id="confirm-check-lugar" style="accent-color:#dc2626;width:16px;height:16px;">
            <span style="font-size:.85rem;color:#991b1b;font-weight:500;">Entiendo que esta acción no se puede deshacer</span>
        </label>
        <div style="display:flex;gap:.75rem;">
            <button type="button" onclick="cerrarConfirmLugar()"
                    style="flex:1;padding:.7rem;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#fff;cursor:pointer;font-size:.88rem;font-weight:600;color:#374151;">
                Cancelar
            </button>
            <button type="button" id="btn-confirmar-delete-lugar" onclick="ejecutarDeleteLugar()" disabled
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
        'lugar',
        {{ old('latitud', isset($lugar) && $lugar->latitud ? $lugar->latitud : 'null') }},
        {{ old('longitud', isset($lugar) && $lugar->longitud ? $lugar->longitud : 'null') }}
    );

    @if(isset($lugar))
        abrirModal();
    @endif
});

function abrirModal() {
    document.getElementById('modal-lugar').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-lugar').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-lugar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
        cerrarConfirmLugar();
    }
});

// ── Confirmar eliminar lugar ──
let deleteLugarId = null;

function abrirConfirmLugar(id, nombre) {
    deleteLugarId = id;
    document.getElementById('confirm-nombre-lugar').textContent = 'Vas a eliminar: ' + nombre;
    document.getElementById('confirm-check-lugar').checked = false;
    document.getElementById('btn-confirmar-delete-lugar').disabled = true;
    document.getElementById('btn-confirmar-delete-lugar').style.opacity = '.5';
    const modal = document.getElementById('modal-confirm-lugar');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarConfirmLugar() {
    deleteLugarId = null;
    document.getElementById('modal-confirm-lugar').style.display = 'none';
    document.body.style.overflow = '';
}

function ejecutarDeleteLugar() {
    if (deleteLugarId) document.getElementById('form-delete-lugar-' + deleteLugarId).submit();
}

document.getElementById('confirm-check-lugar').addEventListener('change', function () {
    const btn = document.getElementById('btn-confirmar-delete-lugar');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
});

document.getElementById('modal-confirm-lugar').addEventListener('click', function (e) {
    if (e.target === this) cerrarConfirmLugar();
});

function toggleFiltrosLugares() {
    const box = document.getElementById('filtrosLugares');
    box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
}

window.addEventListener('load', function () {
    if (
        "{{ request('nombre') }}" ||
        "{{ request('categoria') }}" ||
        "{{ request('ubicacion') }}" ||
        "{{ request('precio_entrada') }}"
    ) {
        document.getElementById('filtrosLugares').style.display = 'block';
    }
});
</script>
@endpush