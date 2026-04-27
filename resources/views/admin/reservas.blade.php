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

<div id="modal-reserva" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:999;padding:2rem;">
    <div style="background:#fff;border-radius:1rem;max-width:720px;margin:auto;overflow:hidden;">

        <div style="background:linear-gradient(135deg,var(--green-900),var(--green-700));padding:1rem;color:#fff;display:flex;justify-content:space-between;">
            <strong>{{ $editando ? 'Editar Reserva' : 'Nueva Reserva' }}</strong>
            <button onclick="cerrarModal()" style="background:none;border:none;color:#fff;">✖</button>
        </div>

        <div style="padding:1.5rem;">
            <form method="POST" action="{{ $editando ? route('admin.reservas.update',$reserva) : route('admin.reservas.store') }}">
                @csrf
                @if($editando) @method('PUT') @endif

                <div class="form-row">
                    <div class="form-group">
                        <label>Usuario</label>
                        <select name="usuario_id" required>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Hotel</label>
                        <select name="hotel_id" required>
                            @foreach($hoteles as $h)
                                <option value="{{ $h->id }}">{{ $h->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <input type="date" name="fecha_entrada" required>
                    <input type="date" name="fecha_salida" required>
                    <input type="number" name="num_personas" min="1" value="1">
                </div>

                <div class="form-row">
                    <input type="number" name="precio_total" value="0">
                    <select name="estado">
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div style="margin-top:1rem;">
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- ================= LISTADO ================= --}}
<div class="admin-section">

    {{-- HEADER TABLA --}}
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list"></i> Todas las Reservas</h2>

        <div style="display:flex; gap:.5rem;">
            <span class="badge badge-info">{{ $reservas->total() }} total</span>
            <button onclick="toggleFiltros()" class="btn btn-success btn-sm">Filtro</button>
        </div>
    </div>

    {{-- FILTROS --}}
    <div id="filtrosBox" style="display:none; margin:1rem 0; background:#fff; padding:1rem; border-radius:.8rem;">
        <form method="GET" action="{{ route('admin.reservas.index') }}">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">
                <div>
                    <label>Fecha inicio</label><br>
                    <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                </div>
                <div>
                    <label>Fecha fin</label><br>
                    <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}">
                </div>
                <div>
                    <label>Estado</label><br>
                    <select name="estado">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado')=='confirmada'?'selected':'' }}>Confirmada</option>
                        <option value="cancelada" {{ request('estado')=='cancelada'?'selected':'' }}>Cancelada</option>
                    </select>
                </div>
                <div style="display:flex; gap:.5rem;">
                    <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
                    <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLA --}}
    <div style="background:#fff;border-radius:1rem;box-shadow:0 10px 30px rgba(0,0,0,.08);overflow:hidden;">

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
                        <td>{{ $r->fecha_entrada }}</td>
                        <td>{{ $r->fecha_salida }}</td>
                        <td>${{ number_format($r->precio_total) }}</td>

                        {{-- ESTADO BONITO --}}
                        <td>
                            <span class="estado-badge estado-{{ $r->estado }}">
                                {{ ucfirst($r->estado) }}
                            </span>
                        </td>

                        {{-- BOTONES HORIZONTALES --}}
                        <td>
                            <div style="display:flex; gap:.5rem; flex-wrap:nowrap;">

                                <a href="{{ route('admin.reservas.edit',$r) }}"
                                   class="btn-small btn-edit btn-sm"
                                   style="background:#d4a017;color:#fff;">
                                    Editar
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.reservas.destroy',$r) }}"
                                      style="margin:0;">
                                    @csrf @method('DELETE')

                                    <button class="btn-small btn-delete btn-sm"
                                            style="background:#e53935;color:#fff;">
                                        Eliminar
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty
                        <tr><td colspan="8">Sin registros</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- PAGINACIÓN --}}
    @include('partials.pagination', ['paginator' => $reservas, 'perPage' => $perPage])

</div>

{{-- ================= ESTILOS ================= --}}
<style>
.estado-badge{
    padding:.3rem .7rem;
    border-radius:999px;
    font-size:.75rem;
    font-weight:600;
}
.estado-pendiente{background:#fff3cd;color:#856404;}
.estado-confirmada{background:#d4edda;color:#155724;}
.estado-cancelada{background:#f8d7da;color:#721c24;}
</style>

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script>
function abrirModal(){document.getElementById('modal-reserva').style.display='block';}
function cerrarModal(){document.getElementById('modal-reserva').style.display='none';}

function toggleFiltros(){
    let box=document.getElementById('filtrosBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

@if(isset($reserva))
abrirModal();
@endif
</script>
@endpush

@endsection