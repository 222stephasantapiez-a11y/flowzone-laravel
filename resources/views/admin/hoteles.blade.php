@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Hoteles')
@section('page-title', 'Hoteles')
@section('page-subtitle', 'Agrega, edita o elimina hoteles del sistema')

@section('content')

{{-- Formulario --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($hotel) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($hotel) ? 'Editar Hotel: ' . $hotel->nombre : 'Hoteles' }}
        </h2>
        @unless(isset($hotel))
        
               <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
                 <a href="{{ route('admin.hoteles.index') }}" class="btn btn-primary  btn-sm ">
                <i class="fa-solid fa-plus"></i> Nuevo Hotel
            </a>
    <a href="{{ route('admin.hoteles.export.excel') }}" class="btn btn-success btn-sm">
        <i class="fa-solid fa-file-excel"></i> Excel
    </a>

    <a href="{{ route('admin.hoteles.export.pdf') }}" class="btn btn-danger btn-sm">
        <i class="fa-solid fa-file-pdf"></i> PDF
    </a>
  </div>
        @endunless

    </div>

  <form action="{{ route('admin.hoteles.import.excel') }}"
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
        @endisset
    </div>

    </form>
</div>

{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Hoteles Registrados</h2>
        <span class="badge badge-info">{{ $hoteles->total() }} total</span>
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
</script>
@endpush
