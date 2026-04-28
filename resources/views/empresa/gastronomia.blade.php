@extends('layouts.empresa')

@section('page-title', 'Gastronomía')
@section('page-subtitle', 'Platos y servicios de {{ $empresa->nombre }}')

@section('topbar-actions')
    @unless(isset($gastronomium))
        <a href="{{ route('empresa.gastronomia.index') }}#form" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus fa-xs"></i> Nuevo plato
        </a>
    @endunless
@endsection

@section('content')

{{-- Formulario crear / editar --}}
<div class="admin-section" id="form">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-{{ isset($gastronomium) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--green-600);"></i>
        {{ isset($gastronomium) ? 'Editar: ' . $gastronomium->nombre : 'Agregar plato o servicio' }}
    </h2>

    @isset($gastronomium)
        <form method="POST" action="{{ route('empresa.gastronomia.update', $gastronomium) }}"
              class="admin-form" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('empresa.gastronomia.store') }}"
              class="admin-form" enctype="multipart/form-data">
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
        <div class="form-group">
            <label>Precio promedio (COP)</label>
            <input type="number" step="0.01" name="precio_promedio"
                   placeholder="Ej: 25000"
                   value="{{ old('precio_promedio', $gastronomium->precio_promedio ?? '') }}">
        </div>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" rows="3"
                  placeholder="Describe el plato o servicio...">{{ old('descripcion', $gastronomium->descripcion ?? '') }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion"
                   value="{{ old('direccion', $gastronomium->direccion ?? '') }}">
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" maxlength="20"
                   value="{{ old('telefono', $gastronomium->telefono ?? '') }}">
        </div>
    </div>

    @include('partials.map_picker', [
        'mapId'        => 'emp-gastro',
        'latValue'     => old('latitud', $gastronomium->latitud ?? ''),
        'lngValue'     => old('longitud', $gastronomium->longitud ?? ''),
        'addressValue' => old('direccion', $gastronomium->direccion ?? ''),
    ])

    <div class="form-group">
        <label>Ingredientes <span style="font-size:.78rem;color:var(--gray-400);font-weight:400;">(separados por coma)</span></label>
        <input type="text" name="ingredientes"
               placeholder="Ej: Cerdo, Arroz, Arveja"
               value="{{ old('ingredientes', $gastronomium->ingredientes ?? '') }}">
    </div>

    @include('partials.imagen_field', [
        'currentImage' => $gastronomium->imagen ?? null,
        'fieldId'      => 'emp-gastro',
    ])

    <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-{{ isset($gastronomium) ? 'floppy-disk' : 'plus' }} fa-xs"></i>
            {{ isset($gastronomium) ? 'Actualizar' : 'Guardar' }}
        </button>
        @isset($gastronomium)
            <a href="{{ route('empresa.gastronomia.index') }}" class="btn btn-outline">Cancelar</a>
        @endisset
    </div>
    </form>
</div>

{{-- Lista de platos --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-utensils" style="color:var(--green-600);"></i> Mis platos y servicios
        </h2>
        <span class="badge badge-info">{{ $items->count() }}</span>
    </div>
    @include('partials.search_bar', [
        'searchRoute' => 'empresa.gastronomia.index',
        'placeholder' => 'Buscar por nombre o tipo...',
        'busqueda'    => $busqueda ?? '',
    ])

    @if($items->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-utensils"></i>
            <p>Aún no has agregado platos o servicios.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $imgSrc = $item->imagen
                                ? (str_starts_with($item->imagen,'http') ? $item->imagen : Storage::disk('public')->url($item->imagen))
                                : null;
                        @endphp
                        <tr>
                            <td>
                                @if($imgSrc)
                                    <img src="{{ $imgSrc }}" alt="{{ $item->nombre }}"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:var(--radius-sm);">
                                @else
                                    <div style="width:48px;height:48px;background:var(--gray-100);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;color:var(--gray-400);">
                                        <i class="fa-solid fa-utensils fa-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $item->nombre }}</strong></td>
                            <td>
                                @if($item->tipo)
                                    <span class="badge badge-info">{{ $item->tipo }}</span>
                                @else
                                    <span style="color:var(--gray-400);">—</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;font-size:.875rem;">
                                @if($item->precio_promedio)
                                    ${{ number_format($item->precio_promedio, 0) }} COP
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('empresa.gastronomia.edit', $item) }}" class="btn-small btn-edit">
                                    <i class="fa-solid fa-pen fa-xs"></i> Editar
                                </a>
                                <form method="POST" action="{{ route('empresa.gastronomia.destroy', $item) }}"
                                      style="display:inline" onsubmit="return confirm('¿Eliminar este plato?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete">
                                        <i class="fa-solid fa-trash fa-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    mapPickerInit(
        'emp-gastro',
        {{ old('latitud', $gastronomium->latitud ?? 'null') }},
        {{ old('longitud', $gastronomium->longitud ?? 'null') }}
    );
});
</script>
@endpush