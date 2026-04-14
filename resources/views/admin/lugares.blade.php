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
        @endisset
    </div>

    </form>
</div>

{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Lugares Registrados</h2>
        <span class="badge badge-info">{{ $lugares->total() }} total</span>
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

    {{-- Paginación --}}
    @if($lugares->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $lugares->firstItem() }}</strong>–<strong>{{ $lugares->lastItem() }}</strong>
            de <strong>{{ $lugares->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($lugares->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $lugares->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($lugares->getUrlRange(max(1,$lugares->currentPage()-2), min($lugares->lastPage(),$lugares->currentPage()+2)) as $page => $url)
                @if($page == $lugares->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($lugares->hasMorePages())
                <a href="{{ $lugares->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
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
        'lugar',
        {{ old('latitud', isset($lugar) && $lugar->latitud ? $lugar->latitud : 'null') }},
        {{ old('longitud', isset($lugar) && $lugar->longitud ? $lugar->longitud : 'null') }}
    );
});
</script>
@endpush
