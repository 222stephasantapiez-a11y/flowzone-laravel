
@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Gastronomía')
@section('page-title', 'Gastronomía')
@section('page-subtitle', 'Administra platos, restaurantes y servicios gastronómicos')

@section('content')

{{-- Barra superior --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-utensils" style="color:var(--primary);margin-right:.4rem;"></i>
            Gastronomía
        </h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Elemento
            </button>
            <a href="{{ route('admin.gastronomia.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.gastronomia.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
             @include('partials.import_modal', [
                'importRoute' => 'admin.gastronomia.import.excel',
                'sampleFile'  => 'ejemplo_gastronomia.xlsx',
                'modalId'     => 'importGastronomia',
                'columns'     => [
                    'nombre'      => 'Nombre del plato o restaurante (requerido)',
                    'descripcion' => 'Descripción',
                    'ubicacion'   => 'Ubicación o zona',
                    'precio'      => 'Precio promedio en COP',
                    'tipo'        => 'Tipo (Plato típico, Bebida, Postre, Restaurante...)',
                    'restaurante' => 'Nombre del establecimiento',
                    'direccion'   => 'Dirección física',
                    'telefono'    => 'Teléfono de contacto',
                ],
            ])
        </div>
    </div>
</div>


{{-- ===================== MODAL ===================== --}}
<div id="modal-gastronomia" style="
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
            background: linear-gradient(135deg, var(--orange-900), var(--orange-700));
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <h3 style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-{{ isset($gastronomium) ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ isset($gastronomium) ? 'Editar: ' . $gastronomium->nombre : 'Nuevo Elemento' }}
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
        <div style="padding: 1.75rem;max-height:calc(90vh - 120px);overflow-y:auto;">

            @isset($gastronomium)
                <form method="POST" action="{{ route('admin.gastronomia.update', $gastronomium) }}" class="admin-form" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.gastronomia.store') }}" class="admin-form" enctype="multipart/form-data">
            @endisset
            @csrf

    <div class="form-row">
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required maxlength="150"
                   placeholder="Ej: Lechona Tolimense"
                   value="{{ old('nombre', $gastronomium->nombre ?? '') }}">
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select name="tipo">
                <option value="">— Seleccionar —</option>
                @foreach(['Plato típico','Bebida','Postre','Restaurante','Cafetería','Snack'] as $t)
                    <option value="{{ $t }}"
                        {{ old('tipo', $gastronomium->tipo ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" rows="3"
                  placeholder="Describe el plato, ingredientes principales, historia...">{{ old('descripcion', $gastronomium->descripcion ?? '') }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Precio (COP)</label>
            <input type="number" step="0.01" name="precio_promedio"
                   placeholder="Ej: 25000"
                   value="{{ old('precio_promedio', $gastronomium->precio_promedio ?? '') }}">
        </div>
        <div class="form-group">
            <label>Restaurante / Establecimiento</label>
            <input type="text" name="restaurante" maxlength="150"
                   placeholder="Nombre del lugar"
                   value="{{ old('restaurante', $gastronomium->restaurante ?? '') }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion"
                   placeholder="Ej: Calle 5 #10-20, Ortega"
                   value="{{ old('direccion', $gastronomium->direccion ?? '') }}">
        </div>
        <div class="form-group">
            <label>Ubicación</label>
            <input type="text" name="ubicacion"
                   placeholder="Barrio o zona"
                   value="{{ old('ubicacion', $gastronomium->ubicacion ?? '') }}">
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" maxlength="20"
                   value="{{ old('telefono', $gastronomium->telefono ?? '') }}">
        </div>
    </div>

    @include('partials.map_picker', [
        'mapId'        => 'gastro',
        'latValue'     => old('latitud', $gastronomium->latitud ?? ''),
        'lngValue'     => old('longitud', $gastronomium->longitud ?? ''),
        'addressValue' => old('direccion', $gastronomium->direccion ?? ''),
    ])

    <div class="form-group">
        <label>Ingredientes principales <span class="form-hint" style="display:inline">(separados por coma)</span></label>
        <input type="text" name="ingredientes"
               placeholder="Ej: Cerdo, Arroz, Arveja, Zanahoria"
               value="{{ old('ingredientes', $gastronomium->ingredientes ?? '') }}">
    </div>

    <div class="form-group">
        <label>Empresa asociada</label>
        <select name="empresa_id">
            <option value="">— Sin empresa —</option>
            @foreach($empresas as $emp)
                <option value="{{ $emp->id }}"
                    {{ old('empresa_id', $gastronomium->empresa_id ?? '') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    @include('partials.imagen_field', [
        'currentImage' => $gastronomium->imagen ?? null,
        'fieldId'      => 'gastro',
    ])

            <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-{{ isset($gastronomium) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($gastronomium) ? 'Actualizar' : 'Guardar' }}
                </button>
                @isset($gastronomium)
                    <a href="{{ route('admin.gastronomia.index') }}" class="btn btn-outline">
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
{{-- ================= LISTADO ================= --}}
<div class="admin-section">

    {{-- HEADER TABLA + FILTRO DERECHA --}}
    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">

        <h2>
            <i class="fa-solid fa-list" style="color:var(--primary);"></i> Elementos
        </h2>

        <div style="display:flex; align-items:center; gap:.5rem;">
            <span class="badge badge-info">{{ $gastronomias->total() }} total</span>

            <button type="button" onclick="toggleFiltros()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
        </div>

    </div>

    {{-- FILTROS HORIZONTALES --}}
    <div id="filtrosBox" style="display:none; margin-bottom:1rem;">
        <form method="GET" action="{{ route('admin.gastronomia.index') }}">

            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">

                <div>
                    <label>Nombre</label><br>
                    <input type="text" name="nombre" value="{{ request('nombre') }}">
                </div>

                <div>
                    <label>Tipo</label><br>
                    <input type="text" name="tipo" value="{{ request('tipo') }}">
                </div>

                <div>
                    <label>Restaurante</label><br>
                    <input type="text" name="restaurante" value="{{ request('restaurante') }}">
                </div>

                 <div>
                    <label>Empresa</label><br>
                    <input type="number" name="empresa" value="{{ request('empresa') }}">
                </div>

                <div>
                    <label>Precio máximo</label><br>
                    <input type="number" name="precio" value="{{ request('precio') }}">
                </div>

                <div style="display:flex; gap:.5rem;">
                    <button class="btn btn-primary btn-sm">Aplicar</button>
                    <a href="{{ route('admin.gastronomia.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
                </div>

            </div>

        </form>
    </div>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                @php
                    $sort      = $sort ?? 'id';
                    $direction = $direction ?? 'desc';
                @endphp
                <tr>
                    <th>
                        <a href="{{ route('admin.gastronomia.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Imagen</th>
                    <th>
                        <a href="{{ route('admin.gastronomia.index', array_merge(request()->all(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Nombre @if($sort === 'nombre') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.gastronomia.index', array_merge(request()->all(), ['sort' => 'tipo', 'direction' => ($sort === 'tipo' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Tipo @if($sort === 'tipo') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.gastronomia.index', array_merge(request()->all(), ['sort' => 'restaurante', 'direction' => ($sort === 'restaurante' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Restaurante @if($sort === 'restaurante') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Empresa</th>
                    <th>
                        <a href="{{ route('admin.gastronomia.index', array_merge(request()->all(), ['sort' => 'precio_promedio', 'direction' => ($sort === 'precio_promedio' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Precio @if($sort === 'precio_promedio') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($gastronomias as $item)
                <tr>
                    <td>{{ $item->id }}</td>

                    <td>
                        @if($item->imagen)
                            @php
                                $src = str_starts_with($item->imagen, 'http')
                                    ? $item->imagen
                                    : Storage::disk('public')->url($item->imagen);
                            @endphp
                            <img src="{{ $src }}" width="50" style="border-radius:6px;object-fit:cover;height:40px;">
                        @else
                            <span style="color:var(--gray-lt);font-size:.78rem;">Sin imagen</span>
                        @endif
                    </td>

                    <td><strong>{{ $item->nombre }}</strong></td>
                    <td>{{ $item->tipo ?? '—' }}</td>
                    <td>{{ $item->restaurante ?? '—' }}</td>
                    <td>{{ $item->empresa?->nombre ?? '—' }}</td>
                    <td>
                        @if($item->precio_promedio)
                            ${{ number_format($item->precio_promedio, 0) }}
                        @else
                            —
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.gastronomia.edit', $item) }}" class="btn-small btn-edit btn-sm">
                            <i class="fa-solid fa-pen fa-xs"></i> Editar
                        </a>

                        <form method="POST" action="{{ route('admin.gastronomia.destroy', $item) }}" style="display:inline"
                              onsubmit="return confirm('¿Eliminar este elemento?')">
                            @csrf @method('DELETE')
                            <button class="btn-small btn-delete btn-sm">
                                <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">
                        <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                        No hay elementos registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $gastronomias, 'perPage' => $perPage])

</div>

@endsection

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script>
function abrirModal() {
    document.getElementById('modal-gastronomia').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-gastronomia').style.display = 'none';
    document.body.style.overflow = '';
}

// cerrar al hacer click fuera
document.getElementById('modal-gastronomia').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});

// abrir automáticamente en editar
@isset($gastronomium)
    abrirModal();
@endisset

// toggle filtros
function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

// mantener filtros abiertos
window.addEventListener('load', function () {
    if (
        "{{ request('nombre') }}" ||
        "{{ request('tipo') }}" ||
        "{{ request('restaurante') }}" ||
        "{{ request('empresa') }}" ||
        "{{ request('precio') }}"
    ) {
        document.getElementById('filtrosBox').style.display = 'block';
    }
});
</script>
@endpush

