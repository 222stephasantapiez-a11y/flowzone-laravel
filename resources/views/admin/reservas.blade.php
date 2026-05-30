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
        <div style="display:flex;gap:.5rem;">
            <a href="{{ route('admin.reservas.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.reservas.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
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
                        <span class="badge-estado badge-{{ $r->estado }}">
                            @if($r->estado === 'confirmada')
                                <i class="fa-solid fa-circle-check"></i>
                            @elseif($r->estado === 'pendiente')
                                <i class="fa-solid fa-clock"></i>
                            @else
                                <i class="fa-solid fa-circle-xmark"></i>
                            @endif
                            {{ ucfirst($r->estado) }}
                        </span>
                    </td>
                    <td>
                        {{-- Formulario oculto para el DELETE --}}
                        <form id="form-delete-reserva-{{ $r->id }}" method="POST"
                              action="{{ route('admin.reservas.destroy', $r) }}"
                              style="display:none;">
                            @csrf @method('DELETE')
                        </form>

                        <button type="button"
                                class="btn btn-sm"
                                style="background:var(--danger);color:#fff;border:none;padding:.4rem .8rem;border-radius:var(--radius-sm);cursor:pointer;font-size:.8rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;"
                                onclick="abrirConfirmReserva({{ $r->id }}, '{{ addslashes($r->usuario->name) }}', '{{ addslashes($r->hotel->nombre) }}')">
                            <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                        </button>
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

{{-- ================= MODAL CONFIRMACIÓN ELIMINAR ================= --}}
<div id="modal-confirm-reserva"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="text-align:center;margin-bottom:1.25rem;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.4rem;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:.4rem;">¿Eliminar reserva?</h3>
            <p style="font-size:.88rem;color:#6b7280;" id="confirm-info-reserva"></p>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:1.25rem;">
            <input type="checkbox" id="confirm-check-reserva" style="accent-color:#dc2626;width:16px;height:16px;">
            <span style="font-size:.85rem;color:#991b1b;font-weight:500;">Entiendo que esta acción no se puede deshacer</span>
        </label>
        <div style="display:flex;gap:.75rem;">
            <button type="button" onclick="cerrarConfirmReserva()"
                    style="flex:1;padding:.7rem;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#fff;cursor:pointer;font-size:.88rem;font-weight:600;color:#374151;">
                Cancelar
            </button>
            <button type="button" id="btn-confirmar-delete-reserva" onclick="ejecutarDeleteReserva()" disabled
                    style="flex:1;padding:.7rem;border:none;border-radius:.5rem;background:#dc2626;color:#fff;cursor:pointer;font-size:.88rem;font-weight:600;opacity:.5;transition:opacity .2s;">
                Sí, eliminar
            </button>
        </div>
    </div>
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
// ── Filtros ──
function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

window.addEventListener('load', function () {
    if (
        "{{ request('fecha_inicio') }}" ||
        "{{ request('fecha_fin') }}"    ||
        "{{ request('usuario') }}"      ||
        "{{ request('hotel') }}"        ||
        "{{ request('estado') }}"
    ) {
        document.getElementById('filtrosBox').style.display = 'block';
    }
});

// ── Modal confirmar eliminar reserva ──
let deleteReservaId = null;

function abrirConfirmReserva(id, usuario, hotel) {
    deleteReservaId = id;
    document.getElementById('confirm-info-reserva').textContent =
        'Reserva de ' + usuario + ' en ' + hotel;
    document.getElementById('confirm-check-reserva').checked = false;
    const btn = document.getElementById('btn-confirmar-delete-reserva');
    btn.disabled      = true;
    btn.style.opacity = '.5';
    const modal = document.getElementById('modal-confirm-reserva');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarConfirmReserva() {
    deleteReservaId = null;
    document.getElementById('modal-confirm-reserva').style.display = 'none';
    document.body.style.overflow = '';
}

function ejecutarDeleteReserva() {
    if (deleteReservaId) {
        document.getElementById('form-delete-reserva-' + deleteReservaId).submit();
    }
}

document.getElementById('confirm-check-reserva').addEventListener('change', function () {
    const btn = document.getElementById('btn-confirmar-delete-reserva');
    btn.disabled      = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
});

document.getElementById('modal-confirm-reserva').addEventListener('click', function (e) {
    if (e.target === this) cerrarConfirmReserva();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') cerrarConfirmReserva();
});
</script>
@endpush

@endsection