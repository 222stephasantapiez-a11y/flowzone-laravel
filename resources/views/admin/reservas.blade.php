@extends('layouts.admin')

@section('title', 'Reservas')
@section('page-title', 'Reservas')
@section('page-subtitle', 'Administra todas las reservas del sistema')

@section('content')

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

<div class="admin-section">

    <div class="admin-section-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Todas las Reservas</h2>
        <div style="display:flex;gap:.5rem;align-items:center;">
            <span class="badge badge-info">{{ $reservas->total() }} total</span>
            <button onclick="toggleFiltros()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
        </div>
    </div>

    <div id="filtrosBox" style="display:none;padding:1rem 0 .5rem;">
        <form method="GET" action="{{ route('admin.reservas.index') }}">
            <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
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
                    <input type="text" name="usuario" value="{{ request('usuario') }}" placeholder="Nombre del usuario..." class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Hotel</label>
                    <input type="text" name="hotel" value="{{ request('hotel') }}" placeholder="Nombre del hotel..." class="filter-input">
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
                <div style="display:flex;gap:.5rem;align-items:flex-end;padding-bottom:1px;">
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
                </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay reservas registradas aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $reservas, 'perPage' => $perPage])

</div>

@push('scripts')
<script>
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
</script>
@endpush

@endsection