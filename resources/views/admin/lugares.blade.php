@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Lugares')
@section('page-title', 'Lugares')
@section('page-subtitle', 'Administra los destinos turísticos del sistema')

@section('content')

{{-- Formulario --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($lugar) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($lugar) ? 'Editar Lugar: ' . $lugar->nombre : 'Lugares' }}
        </h2>
        @unless(isset($lugar))
           
            <div style="display:flex; gap:.5rem; margin-bottom:1rem;">

             <a href="{{ route('admin.lugares.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Lugar
            </a>
    <a href="{{ route('admin.lugares.export.excel') }}" class="btn btn-success btn-sm">
        <i class="fa-solid fa-file-excel"></i> Excel
    </a>

    <a href="{{ route('admin.lugares.export.pdf') }}" class="btn btn-danger btn-sm">
        <i class="fa-solid fa-file-pdf"></i> PDF
    </a>
</div>

<form action="{{ route('admin.lugares.import.excel') }}"
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
        @endunless
    </div>

    @isset($lugar)
        <form method="POST" action="{{ route('admin.lugares.update', $lugar) }}"
              class="admin-form" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.lugares.store') }}"
              class="admin-form" enctype="multipart/form-data">
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
            <label>Latitud</label>
            <input type="number" step="0.00000001" name="latitud"
                   placeholder="4.711000"
                   value="{{ old('latitud', $lugar->latitud ?? '') }}">
        </div>
        <div class="form-group">
            <label>Longitud</label>
            <input type="number" step="0.00000001" name="longitud"
                   placeholder="-74.072100"
                   value="{{ old('longitud', $lugar->longitud ?? '') }}">
        </div>
        <div class="form-group">
            <label>Precio Entrada (COP)</label>
            <input type="number" step="0.01" name="precio_entrada"
                   placeholder="0 = gratuito"
                   value="{{ old('precio_entrada', $lugar->precio_entrada ?? '0') }}">
        </div>
    </div>

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
        @endisset
    </div>

    </form>
</div>

<!-- BOTÓN FILTRAR -->
<button onclick="toggleFiltros()" class="btn btn-primary" style="margin-bottom:1rem;">
    <i class="fa-solid fa-filter"></i> Filtrar
</button>

<!-- FORMULARIO OCULTO -->
<form method="GET" action="{{ route('admin.lugares.index') }}"
      class="admin-form"
      id="filtrosBox"
      style="margin-bottom:1rem; display:none;">

    <div class="form-row">

        <!-- NOMBRE -->
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ request('nombre') }}">
        </div>

        <!-- CATEGORÍA (FIJA 🔥) -->
        <div class="form-group">
            <label>Categoría</label>
            <select name="categoria">
                <option value="">Todas</option>

                <option value="natural" {{ request('categoria') == 'natural' ? 'selected' : '' }}>
                    Natural
                </option>

                <option value="cultural" {{ request('categoria') == 'cultural' ? 'selected' : '' }}>
                    Cultural
                </option>

                <option value="histórico" {{ request('categoria') == 'histórico' ? 'selected' : '' }}>
                    Histórico
                </option>

                <option value="aventura" {{ request('categoria') == 'aventura' ? 'selected' : '' }}>
                    Aventura
                </option>
            </select>
        </div>

        <!-- UBICACIÓN -->
        <div class="form-group">
            <label>Ubicación</label>
            <input type="text" name="ubicacion" value="{{ request('ubicacion') }}">
        </div>

        <!-- PRECIO -->
        <div class="form-group">
            <label>Precio máximo entrada</label>
            <input type="number" name="precio_entrada" value="{{ request('precio_entrada') }}">
        </div>

        <!-- BOTÓN -->
        <div class="form-group" style="display:flex;align-items:end;">
            <button type="submit" class="btn btn-primary">
                Aplicar
            </button>
        </div>

    </div>
</form>

<!-- SCRIPT -->
<script>
function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

window.onload = function() {
    const hasFilters =
        "{{ request('nombre') }}" ||
        "{{ request('categoria') }}" ||
        "{{ request('ubicacion') }}" ||
        "{{ request('precio_entrada') }}";

    if (hasFilters) {
        document.getElementById('filtrosBox').style.display = 'block';
    }
}
</script>
{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Lugares Registrados</h2>
        <span class="badge badge-info">{{ $lugares->count() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Ubicación</th>
                    <th>Precio Entrada</th>
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
                            <a href="{{ route('admin.lugares.edit', $l) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.lugares.destroy', $l) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar este lugar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>
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
</div>

@endsection
