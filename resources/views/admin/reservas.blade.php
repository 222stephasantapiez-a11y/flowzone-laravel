@extends('layouts.admin')

@section('title', 'Reservas')
@section('page-title', 'Reservas')
@section('page-subtitle', 'Administra todas las reservas del sistema')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-calendar-check" style="color:var(--primary);margin-right:.4rem;"></i>
            Reservas
        </h2>

        <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
            <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nueva Reserva
            </button>

            <a href="{{ route('admin.reservas.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>

            <a href="{{ route('admin.reservas.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>
</div>

{{-- ================= MODAL ================= --}}
@php $editando = isset($reserva); @endphp

<div id="modal-reserva" style="
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
                <i class="fa-solid fa-{{ $editando ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ $editando ? 'Editar Reserva' : 'Nueva Reserva' }}
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
        <div style="padding: 1.75rem; max-height: calc(90vh - 120px); overflow-y: auto;">
            <form method="POST" action="{{ $editando ? route('admin.reservas.update', $reserva) : route('admin.reservas.store') }}" class="admin-form">
                @csrf
                @if($editando) @method('PUT') @endif

                <div class="form-row">
                    <div class="form-group">
                        <label>Usuario</label>
                        <select name="usuario_id" required>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}" {{ $editando && $reserva->usuario_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Hotel</label>
                        <select name="hotel_id" required>
                            @foreach($hoteles as $h)
                                <option value="{{ $h->id }}" {{ $editando && $reserva->hotel_id == $h->id ? 'selected' : '' }}>
                                    {{ $h->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha entrada</label>
                        <input type="date" name="fecha_entrada" required
                               value="{{ $editando ? $reserva->fecha_entrada->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Fecha salida</label>
                        <input type="date" name="fecha_salida" required
                               value="{{ $editando ? $reserva->fecha_salida->format('Y-m-d') : '' }}">
                    </div>
                    <div class="form-group">
                        <label>Personas</label>
                        <input type="number" name="num_personas" min="1"
                               value="{{ $editando ? $reserva->num_personas : 1 }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Precio total</label>
                        <input type="number" name="precio_total"
                               value="{{ $editando ? $reserva->precio_total : 0 }}">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado">
                            <option value="pendiente"  {{ $editando && $reserva->estado == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmada" {{ $editando && $reserva->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="cancelada"  {{ $editando && $reserva->estado == 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; gap:.8rem; margin-top:.5rem; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-{{ $editando ? 'floppy-disk' : 'plus' }}"></i>
                        {{ $editando ? 'Actualizar Reserva' : 'Guardar Reserva' }}
                    </button>
                    <button type="button" onclick="cerrarModal()" class="btn btn-outline">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- ================= LISTADO ================= --}}
<div class="admin-section">

    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Todas las Reservas</h2>

        <div style="display:flex; gap:.5rem; align-items:center;">
            <span class="badge badge-info">{{ $reservas->total() }} total</span>
            <button onclick="toggleFiltros()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
        </div>
    </div>

    {{-- FILTROS --}}
    <div id="filtrosBox" style="display:none; padding: 1rem 0 .5rem;">
        <form method="GET" action="{{ route('admin.reservas.index') }}">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">

                <div class="filter-field">
                    <label class="filter-label">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="filter-input">
                </div>

                <div class="filter-field">
                    <label class="filter-label">Fecha fin</label>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="filter-input">
                </div>

                <div class="filter-field">
                    <label class="filter-label">Usuario</label>
                    <input type="text" name="usuario" value="{{ request('usuario') }}"
                           placeholder="Nombre del usuario..." class="filter-input">
                </div>

                <div class="filter-field">
                    <label class="filter-label">Hotel</label>
                    <input type="text" name="hotel" value="{{ request('hotel') }}"
                           placeholder="Nombre del hotel..." class="filter-input">
                </div>

                <div class="filter-field">
                    <label class="filter-label">Estado</label>
                    <select name="estado" class="filter-input">
                        <option value="">Todos</option>
                        <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="cancelada"  {{ request('estado') == 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div style="display:flex; gap:.5rem; align-items:flex-end; padding-bottom:1px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Aplicar
                    </button>
                    <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
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
                    $direction = $direction ?? 'asc';
                @endphp
                <tr>
                    <th>
                        <a href="{{ route('admin.reservas.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Usuario</th>
                    <th>Hotel</th>
                    <th>
                        <a href="{{ route('admin.reservas.index', array_merge(request()->all(), ['sort' => 'fecha_entrada', 'direction' => ($sort === 'fecha_entrada' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Entrada @if($sort === 'fecha_entrada') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.reservas.index', array_merge(request()->all(), ['sort' => 'fecha_salida', 'direction' => ($sort === 'fecha_salida' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Salida @if($sort === 'fecha_salida') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.reservas.index', array_merge(request()->all(), ['sort' => 'precio_total', 'direction' => ($sort === 'precio_total' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Total @if($sort === 'precio_total') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.reservas.index', array_merge(request()->all(), ['sort' => 'estado', 'direction' => ($sort === 'estado' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Estado @if($sort === 'estado') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($reservas as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->usuario->name }}</td>
                    <td>{{ $r->hotel->nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->fecha_entrada)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->fecha_salida)->format('d/m/Y') }}</td>
                    <td>${{ number_format($r->precio_total) }}</td>
                    <td>
                        <span class="estado-badge estado-{{ $r->estado }}">
                            {{ ucfirst($r->estado) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:.4rem; flex-wrap:nowrap; align-items:center;">

                            @if($r->estado !== 'confirmada')
                            <form method="POST" action="{{ route('admin.reservas.estado', $r) }}" style="margin:0;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estado" value="confirmada">
                                <button type="submit" class="btn-small btn-sm" style="background:#155724;color:#fff;white-space:nowrap;border:none;padding:.3rem .7rem;border-radius:.4rem;cursor:pointer;">
                                    <i class="fa-solid fa-circle-check fa-xs"></i> Confirmar
                                </button>
                            </form>
                            @endif

                            @if($r->estado === 'confirmada')
                            <form method="POST" action="{{ route('admin.reservas.estado', $r) }}" style="margin:0;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estado" value="pendiente">
                                <button type="submit" class="btn-small btn-sm" style="background:#856404;color:#fff;white-space:nowrap;border:none;padding:.3rem .7rem;border-radius:.4rem;cursor:pointer;">
                                    <i class="fa-solid fa-clock fa-xs"></i> Pendiente
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('admin.reservas.edit', $r) }}"
                               class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>

                            <form method="POST" action="{{ route('admin.reservas.destroy', $r) }}" style="margin:0;"
                                  onsubmit="return confirm('¿Eliminar esta reserva?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay reservas registradas aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @include('partials.pagination', ['paginator' => $reservas, 'perPage' => $perPage])

</div>

{{-- ================= ESTILOS ================= --}}
<style>
.estado-badge {
    padding: .3rem .7rem;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 600;
}
.estado-pendiente  { background: #fff3cd; color: #856404; }
.estado-confirmada { background: #d4edda; color: #155724; }
.estado-cancelada  { background: #f8d7da; color: #721c24; }
</style>

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script>
function abrirModal() {
    document.getElementById('modal-reserva').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-reserva').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-reserva').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});

function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

window.addEventListener('load', function () {
    if (
        "{{ request('fecha_inicio') }}" ||
        "{{ request('fecha_fin') }}" ||
        "{{ request('usuario') }}" ||
        "{{ request('hotel') }}" ||
        "{{ request('estado') }}"
    ) {
        document.getElementById('filtrosBox').style.display = 'block';
    }
});

@if(isset($reserva))
    abrirModal();
@endif
</script>
@endpush

@endsection