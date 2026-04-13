@extends('layouts.admin')

@section('title', 'Reservas')
@section('page-title', 'Reservas')
@section('page-subtitle', 'Administra todas las reservas del sistema')

@section('content')

{{-- Formulario --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($reserva) ? 'pen-to-square' : 'plus-circle' }}"
               style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($reserva) ? 'Editar Reserva #' . $reserva->id : 'Reservas' }}
        </h2>

        @unless(isset($reserva))
            <a href="{{ route('admin.reservas.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nueva Reserva
            </a>
        @endunless
    </div>

    @isset($reserva)
        <form method="POST" action="{{ route('admin.reservas.update', $reserva) }}" class="admin-form">
            @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.reservas.store') }}" class="admin-form">
    @endisset
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Usuario *</label>
                <select name="usuario_id" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}"
                            {{ old('usuario_id', $reserva->usuario_id ?? '') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Hotel *</label>
                <select name="hotel_id" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($hoteles as $h)
                        <option value="{{ $h->id }}"
                            {{ old('hotel_id', $reserva->hotel_id ?? '') == $h->id ? 'selected' : '' }}>
                            {{ $h->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Fecha Entrada *</label>
                <input type="date" name="fecha_entrada" required
                       value="{{ old('fecha_entrada', isset($reserva) ? $reserva->fecha_entrada->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
                <label>Fecha Salida *</label>
                <input type="date" name="fecha_salida" required
                       value="{{ old('fecha_salida', isset($reserva) ? $reserva->fecha_salida->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
                <label>Nº Personas *</label>
                <input type="number" name="num_personas" min="1" required
                       value="{{ old('num_personas', $reserva->num_personas ?? '1') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Precio Total (COP) *</label>
                <input type="number" step="0.01" name="precio_total" min="0" required
                       value="{{ old('precio_total', $reserva->precio_total ?? '0') }}">
            </div>

            <div class="form-group">
                <label>Estado *</label>
                <select name="estado" required>
                    @foreach(['pendiente', 'confirmada', 'cancelada'] as $estado)
                        <option value="{{ $estado }}"
                            {{ old('estado', $reserva->estado ?? 'pendiente') === $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-{{ isset($reserva) ? 'floppy-disk' : 'plus' }}"></i>
                {{ isset($reserva) ? 'Actualizar Reserva' : 'Guardar Reserva' }}
            </button>

            @isset($reserva)
                <a href="{{ route('admin.reservas.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </a>
            @endisset
        </div>
    </form>
</div>

{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-list" style="color:var(--primary);"></i>
            Todas las Reservas
        </h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <a href="{{ route('admin.reservas.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.reservas.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            <span class="badge badge-info">{{ $reservas->total() }} total</span>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('admin.reservas.index') }}"
          class="admin-form" style="margin-bottom:1rem;">
        <div class="form-row">
            <div class="form-group">
                <label>Fecha entrada</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
            </div>

            <div class="form-group">
                <label>Fecha salida</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelada"  {{ request('estado') == 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <div class="form-group" style="display:flex;align-items:end;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>
            </div>
        </div>
    </form>

    {{-- Importar Excel --}}
    <form action="{{ route('admin.reservas.import.excel') }}"
          method="POST"
          enctype="multipart/form-data"
          style="margin-bottom:1rem;">
        @csrf
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <input type="file" name="archivo" required>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-upload"></i> Importar Excel
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Hotel</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Personas</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservas as $r)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $r->id }}</td>
                        <td>{{ $r->usuario->name ?? '—' }}</td>
                        <td>{{ $r->hotel->nombre ?? '—' }}</td>
                        <td>{{ $r->fecha_entrada->format('d/m/Y') }}</td>
                        <td>{{ $r->fecha_salida->format('d/m/Y') }}</td>
                        <td>{{ $r->num_personas }}</td>
                        <td>${{ number_format($r->precio_total, 0) }}</td>
                        <td>
                            <span class="estado-badge estado-{{ $r->estado }}">
                                {{ ucfirst($r->estado) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.reservas.edit', $r) }}"
                               class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.reservas.destroy', $r) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar esta reserva?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            No hay reservas registradas aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($reservas->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $reservas->firstItem() }}</strong>–<strong>{{ $reservas->lastItem() }}</strong>
            de <strong>{{ $reservas->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($reservas->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $reservas->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($reservas->getUrlRange(max(1,$reservas->currentPage()-2), min($reservas->lastPage(),$reservas->currentPage()+2)) as $page => $url)
                @if($page == $reservas->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($reservas->hasMorePages())
                <a href="{{ $reservas->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
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