@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Eventos')
@section('page-title', 'Eventos')
@section('page-subtitle', 'Administra los eventos culturales y turísticos')

@section('content')

{{-- Formulario --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($evento) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($evento) ? 'Editar Evento: ' . $evento->nombre : 'Eventos' }}
        </h2>
        @unless(isset($evento))
            <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
                <a href="{{ route('admin.eventos.index') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Nuevo Evento
                </a>
                <a href="{{ route('admin.eventos.export.excel') }}" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('admin.eventos.export.pdf') }}" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </div>
        @endunless
        <form action="{{ route('admin.eventos.import.excel') }}"
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

    @isset($evento)
        <form method="POST" action="{{ route('admin.eventos.update', $evento) }}"
              class="admin-form" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.eventos.store') }}"
              class="admin-form" enctype="multipart/form-data">
    @endisset
    @csrf

    <div class="form-row">
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required maxlength="150"
                   placeholder="Ej: Festival del Folclor Tolimense"
                   value="{{ old('nombre', $evento->nombre ?? '') }}">
        </div>
        <div class="form-group">
            <label>Categoría</label>
            <input type="text" name="categoria"
                   placeholder="Ej: Cultural, Deportivo..."
                   value="{{ old('categoria', $evento->categoria ?? '') }}">
        </div>
    </div>

    <div class="form-group">
        <label>Descripción *</label>
        <textarea name="descripcion" rows="3" required
                  placeholder="Describe el evento, actividades y detalles...">{{ old('descripcion', $evento->descripcion ?? '') }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Fecha *</label>
            <input type="date" name="fecha" required
                   value="{{ old('fecha', isset($evento) ? $evento->fecha->format('Y-m-d') : '') }}">
        </div>
        <div class="form-group">
            <label>Hora</label>
            <input type="time" name="hora"
                   value="{{ old('hora', $evento->hora ?? '') }}">
        </div>
        <div class="form-group">
            <label>Precio (COP)</label>
            <input type="number" step="0.01" name="precio"
                   placeholder="0 = gratuito"
                   value="{{ old('precio', $evento->precio ?? '0') }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Ubicación</label>
            <input type="text" name="ubicacion"
                   placeholder="Ej: Plaza Principal de Ortega"
                   value="{{ old('ubicacion', $evento->ubicacion ?? '') }}">
        </div>
        <div class="form-group">
            <label>Organizador</label>
            <input type="text" name="organizador"
                   value="{{ old('organizador', $evento->organizador ?? '') }}">
        </div>
        <div class="form-group">
            <label>Contacto</label>
            <input type="text" name="contacto"
                   placeholder="Teléfono o email"
                   value="{{ old('contacto', $evento->contacto ?? '') }}">
        </div>
    </div>

    @include('partials.imagen_field', [
        'currentImage' => $evento->imagen ?? null,
        'fieldId'      => 'evento',
    ])

    <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-{{ isset($evento) ? 'floppy-disk' : 'plus' }}"></i>
            {{ isset($evento) ? 'Actualizar Evento' : 'Guardar Evento' }}
        </button>
        @isset($evento)
            <a href="{{ route('admin.eventos.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        @endisset
    </div>

    </form>
</div>

{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Eventos Registrados</h2>
        <span class="badge badge-info">{{ $eventos->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Ubicación</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eventos as $e)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $e->id }}</td>
                        <td class="td-img">
                            @if($e->imagen)
                                @php
                                    $src = str_starts_with($e->imagen, 'http')
                                        ? $e->imagen
                                        : Storage::disk('public')->url($e->imagen);
                                @endphp
                                <img src="{{ $src }}" alt="{{ $e->nombre }}"
                                     onerror="this.style.display='none'">
                            @else
                                <span style="color:var(--gray-lt);font-size:.78rem;">Sin imagen</span>
                            @endif
                        </td>
                        <td><strong>{{ $e->nombre }}</strong></td>
                        <td style="white-space:nowrap;">{{ $e->fecha->format('d/m/Y') }}</td>
                        <td>{{ $e->ubicacion ?? '—' }}</td>
                        <td>
                            @if($e->precio > 0)
                                ${{ number_format($e->precio, 0) }}
                            @else
                                <span class="badge badge-success">Gratuito</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.eventos.edit', $e) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.eventos.destroy', $e) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar este evento?')">
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
                            No hay eventos registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($eventos->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $eventos->firstItem() }}</strong>–<strong>{{ $eventos->lastItem() }}</strong>
            de <strong>{{ $eventos->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($eventos->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $eventos->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($eventos->getUrlRange(max(1,$eventos->currentPage()-2), min($eventos->lastPage(),$eventos->currentPage()+2)) as $page => $url)
                @if($page == $eventos->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($eventos->hasMorePages())
                <a href="{{ $eventos->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
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