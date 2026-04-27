@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Eventos')
@section('page-title', 'Eventos')
@section('page-subtitle', 'Administra los eventos culturales y turísticos')

@section('content')

{{-- Barra superior --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-calendar" style="color:var(--primary);margin-right:.4rem;"></i>
            Eventos
        </h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Evento
            </button>
            <a href="{{ route('admin.eventos.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.eventos.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            @include('partials.import_modal', [
                'importRoute' => 'admin.eventos.import.excel',
                'sampleFile'  => 'ejemplo_eventos.xlsx',
                'modalId'     => 'importEventos',
                'columns'     => [
                    'nombre'      => 'Nombre del evento (requerido)',
                    'descripcion' => 'Descripción del evento (requerido)',
                    'fecha'       => 'Fecha en formato YYYY-MM-DD (requerido)',
                    'ubicacion'   => 'Lugar donde se realiza',
                    'categoria'   => 'Categoría (Cultural, Deportivo...)',
                    'precio'      => 'Precio en COP (0 = gratuito)',
                    'organizador' => 'Nombre del organizador',
                    'contacto'    => 'Teléfono o email de contacto',
                ],
            ])
        </div>
    </div>
</div>

{{-- ===================== MODAL ===================== --}}
<div id="modal-evento" style="
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
            background: linear-gradient(135deg, var(--purple-900), var(--purple-700));
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <h3 style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-{{ isset($evento) ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ isset($evento) ? 'Editar Evento: ' . $evento->nombre : 'Nuevo Evento' }}
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

            @isset($evento)
                <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" class="admin-form" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.eventos.store') }}" class="admin-form" enctype="multipart/form-data">
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
<!-- BOTÓN FILTRAR -->

<!-- SCRIPT -->



{{-- Tabla --}}  
<div class="admin-section">
    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">

    <h2>
        <i class="fa-solid fa-list" style="color:var(--primary);"></i> Eventos Registrados
    </h2>

    <div style="display:flex; align-items:center; gap:.5rem;">
        <span class="badge badge-info">{{ $eventos->total() }} total</span>

        <button type="button" onclick="toggleFiltrosEventos()" class="btn btn-success btn-sm">
            <i class="fa-solid fa-filter"></i> Filtro
        </button>
    </div>

  </div>

  {{-- FILTROS --}}
  <div id="filtrosEventos" style="display:none; margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.eventos.index') }}">

        <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">

            <div>
                <label>Nombre</label><br>
                <input type="text" name="nombre" value="{{ request('nombre') }}">
            </div>

            <div>
                <label>Fecha</label><br>
                <input type="date" name="fecha" value="{{ request('fecha') }}">
            </div>

            <div>
                <label>Ubicación</label><br>
                <input type="text" name="ubicacion" value="{{ request('ubicacion') }}">
            </div>

            <div>
                <label>Precio</label><br>
                <input type="number" name="precio" value="{{ request('precio') }}">
            </div>

            <div style="display:flex; gap:.5rem;">
                <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
                <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
            </div>

        </div>

    </form>
  </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                @php
                    $sort      = $sort ?? 'fecha';
                    $direction = $direction ?? 'asc';
                @endphp
                <tr>
                    <th>
                        <a href="{{ route('admin.eventos.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Imagen</th>
                    <th>
                        <a href="{{ route('admin.eventos.index', array_merge(request()->all(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Nombre @if($sort === 'nombre') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.eventos.index', array_merge(request()->all(), ['sort' => 'fecha', 'direction' => ($sort === 'fecha' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Fecha @if($sort === 'fecha') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.eventos.index', array_merge(request()->all(), ['sort' => 'ubicacion', 'direction' => ($sort === 'ubicacion' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Ubicación @if($sort === 'ubicacion') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.eventos.index', array_merge(request()->all(), ['sort' => 'precio', 'direction' => ($sort === 'precio' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Precio @if($sort === 'precio') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
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
    @include('partials.pagination', ['paginator' => $eventos, 'perPage' => $perPage])

</div>

@push('scripts')
<script>
function abrirModal() {
    document.getElementById('modal-evento').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-evento').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-evento').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});

@isset($evento)
    abrirModal();
@endisset
</script>

<script>
function toggleFiltrosEventos() {
    const box = document.getElementById('filtrosEventos');

    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

// Mantener abierto si hay filtros activos
window.addEventListener('load', function () {
    if (
        "{{ request('nombre') }}" ||
        "{{ request('fecha') }}" ||
        "{{ request('ubicacion') }}" ||
        "{{ request('precio') }}"
    ) {
        document.getElementById('filtrosEventos').style.display = 'block';
    }
});
</script>
@endpush

@endsection