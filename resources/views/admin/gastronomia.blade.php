@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Gastronomía')
@section('page-title', 'Gastronomía')
@section('page-subtitle', 'Administra platos, restaurantes y servicios gastronómicos')

@section('content')

<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($gastronomium) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($gastronomium) ? 'Editar: ' . $gastronomium->nombre : 'Gastronomía' }}
        </h2>
        @unless(isset($gastronomium))
            <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
                <a href="{{ route('admin.gastronomia.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Elemento
    <a href="{{ route('admin.gastronomia.export.excel') }}" class="btn btn-success btn-sm">
        <i class="fa-solid fa-file-excel"></i> Excel
    </a>

    <a href="{{ route('admin.gastronomia.export.pdf') }}" class="btn btn-danger btn-sm">
        <i class="fa-solid fa-file-pdf"></i> PDF
    </a>
</div>
        @endunless
        <form action="{{ route('admin.gastronomia.import.excel') }}"
      method="POST"
      enctype="multipart/form-data"
      style="margin-bottom:1rem;">
    @csrf

    <div style="display:flex; gap:.5rem;">
        <input type="file" name="archivo" required>

        <button type="submit" class="btn btn-primary btn-sm">
            Importar Excel
        </button>
    </div>
</form>
    </div>

    @isset($gastronomium)
        <form method="POST" action="{{ route('admin.gastronomia.update', $gastronomium) }}"
              class="admin-form" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.gastronomia.store') }}"
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
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" rows="3"
                  placeholder="Describe el plato, ingredientes principales, historia...">{{ old('descripcion', $gastronomium->descripcion ?? '') }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Precio promedio (COP)</label>
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

    <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-{{ isset($gastronomium) ? 'floppy-disk' : 'plus' }}"></i>
            {{ isset($gastronomium) ? 'Actualizar' : 'Guardar' }}
        </button>
        @isset($gastronomium)
            <a href="{{ route('admin.gastronomia.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        @endisset
    </div>
    </form>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Elementos Registrados</h2>
        <span class="badge badge-info">{{ $items->count() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th><th>Imagen</th><th>Nombre</th><th>Tipo</th>
                    <th>Restaurante</th><th>Precio</th><th>Empresa</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $item->id }}</td>
                        <td class="td-img">
                            @if($item->imagen)
                                @php $src = str_starts_with($item->imagen,'http') ? $item->imagen : Storage::disk('public')->url($item->imagen); @endphp
                                <img src="{{ $src }}" alt="{{ $item->nombre }}" onerror="this.style.display='none'">
                            @else
                                <span style="color:var(--gray-lt);font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->nombre }}</strong></td>
                        <td>@if($item->tipo)<span class="badge badge-info">{{ $item->tipo }}</span>@else —@endif</td>
                        <td>{{ $item->restaurante ?? '—' }}</td>
                        <td>{{ $item->precio_promedio ? '$'.number_format($item->precio_promedio,0) : '—' }}</td>
                        <td>{{ $item->empresa?->nombre ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.gastronomia.edit', $item) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.gastronomia.destroy', $item) }}"
                                  style="display:inline" onsubmit="return confirm('¿Eliminar?')">
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
                            No hay elementos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
