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
            @include('partials.import_modal', [
                'importRoute' => 'admin.hoteles.import.excel',
                'sampleFile'  => 'ejemplo_hoteles.xlsx',
                'modalId'     => 'importHoteles',
                'columns'     => [
                    'nombre'      => 'Nombre del hotel (requerido)',
                    'descripcion' => 'Descripción del hotel',
                    'precio'      => 'Precio por noche en COP (requerido)',
                    'ubicacion'   => 'Ubicación o zona',
                    'capacidad'   => 'Capacidad en personas',
                    'servicios'   => 'Servicios separados por coma (WiFi, Piscina...)',
                    'telefono'    => 'Teléfono de contacto',
                ],
            ])
        </div>
    </div>
</div>

{{-- ===================== MODAL ===================== --}}
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
        {{-- Header modal --}}
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
                border: none;
                color: #fff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background .2s;
            " onmouseover="this.style.background='rgba(255,255,255,.3)'"
               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Body modal --}}
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

    <div class="form-group">
        <label>Servicios <span class="form-hint" style="display:inline">(separados por coma)</span></label>
        <input type="text" name="servicios"
               placeholder="Ej: WiFi, Piscina, Parqueadero, Restaurante"
               value="{{ old('servicios', $hotel->servicios ?? '') }}">
    </div>

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
{{-- filtro --}}


{{-- Tabla --}}
<div class="admin-section">
  <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">

    <h2>
        <i class="fa-solid fa-list" style="color:var(--primary);"></i> Hoteles Registrados
    </h2>

    <div style="display:flex; align-items:center; gap:.5rem;">

        <span class="badge badge-info">{{ $hoteles->total() }} total</span>

        <button type="button" onclick="toggleFiltrosHoteles()" class="btn btn-success btn-sm">
            <i class="fa-solid fa-filter"></i><p>Filtro</p>
        </button>

    </div>
    <div id="filtrosHoteles" style="display:none; margin-bottom:1rem;">

    <form method="GET" action="{{ route('admin.hoteles.index') }}">

        <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">

            <div>
                <label>Ubicación</label><br>
                <input type="text" name="ubicacion" value="{{ request('ubicacion') }}">
            </div>

            <div>
                <label>Precio máximo</label><br>
                <input type="number" name="precio" value="{{ request('precio') }}">
            </div>

            <div>
                <label>Capacidad</label><br>
                <input type="number" name="capacidad" value="{{ request('capacidad') }}">
            </div>

            <div>
                <label>Estado</label><br>
                <select name="disponibilidad">
                    <option value="">Todos</option>
                    <option value="1" {{ request('disponibilidad') == '1' ? 'selected' : '' }}>
                        Disponible
                    </option>
                    <option value="0" {{ request('disponibilidad') == '0' ? 'selected' : '' }}>
                        No disponible
                    </option>
                </select>
            </div>

            <div style="display:flex; gap:.5rem;">
                <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
                <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
            </div>

        </div>

    </form>
</div>

</div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio / noche</th>
                    <th>Ubicación</th>
                    <th>Capacidad</th>
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
                            <a href="{{ route('admin.hoteles.edit', $h) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.hoteles.destroy', $h) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar {{ addslashes($h->nombre) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>
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

    {{-- Paginación --}}
    @if($hoteles->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $hoteles->firstItem() }}</strong>–<strong>{{ $hoteles->lastItem() }}</strong>
            de <strong>{{ $hoteles->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($hoteles->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $hoteles->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($hoteles->getUrlRange(max(1,$hoteles->currentPage()-2), min($hoteles->lastPage(),$hoteles->currentPage()+2)) as $page => $url)
                @if($page == $hoteles->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($hoteles->hasMorePages())
                <a href="{{ $hoteles->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
            @else
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-right fa-xs"></i></span>
            @endif
        </div>

        <form method="GET" class="per-page-form">
            @foreach(request()->except(['page','per_page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <label class="per-page-label">Filas:</label>
            <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                @foreach([5,10,25,50,100] as $n)
                    <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

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
});

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
        if (e.key === 'Escape') cerrarModal();
    });
</script>

<script>
function toggleFiltrosHoteles() {
    const box = document.getElementById('filtrosHoteles');

    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

// Mantener abierto si hay filtros activos
window.addEventListener('load', function () {
    if (
        "{{ request('ubicacion') }}" ||
        "{{ request('precio') }}" ||
        "{{ request('capacidad') }}" ||
        "{{ request('disponibilidad') }}"
    ) {
        document.getElementById('filtrosHoteles').style.display = 'block';
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // 🔥 SI ESTÁ EDITANDO → ABRIR MODAL
    @if(isset($hotel))
        abrirModal();
    @endif

});
</script>
@endpush
