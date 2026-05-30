@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administra usuarios registrados')

@section('content')

<div class="admin-section">
    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <h2 style="margin:0;"><i class="fa-solid fa-users"></i> Usuarios</h2>
        <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.usuarios.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.usuarios.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>
</div>

<div class="admin-section">
    <div style="padding: 1.75rem;">
        <div class="admin-section-header">
            <h2>
                <i class="fa-solid fa-list" style="color:var(--primary);"></i>
                Usuarios Registrados
            </h2>
            <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                <span class="badge badge-info">{{ $usuarios->total() }} total</span>
                <button type="button" onclick="toggleFiltros()" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-filter"></i> Filtro
                </button>
            </div>
        </div>

        <div id="filtrosBox" style="display:none; padding: 1rem 0 .5rem;">
            <form method="GET" action="{{ route('admin.usuarios.index') }}">
                <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
                    <div class="filter-field">
                        <label class="filter-label">ID</label>
                        <input type="number" name="id" value="{{ request('id') }}" placeholder="Ej: 5" class="filter-input">
                    </div>
                    <div class="filter-field">
                        <label class="filter-label">Nombre</label>
                        <input type="text" name="name" value="{{ request('name') }}" placeholder="Buscar por nombre..." class="filter-input">
                    </div>
                    <div class="filter-field">
                        <label class="filter-label">Email</label>
                        <input type="text" name="email" value="{{ request('email') }}" placeholder="Buscar por correo..." class="filter-input">
                    </div>
                    <div style="display:flex; gap:.5rem; align-items:flex-end; padding-bottom:1px;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-magnifying-glass"></i> Aplicar
                        </button>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm">
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
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}" style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                ID @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'])) }}" style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                Nombre @if($sort === 'name') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => ($sort === 'email' && $direction === 'asc') ? 'desc' : 'asc'])) }}" style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                Email @if($sort === 'email') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Teléfono</th>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc'])) }}" style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                Fecha @if($sort === 'created_at') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->rol }}</td>
                            <td>
                                <span class="badge {{ $u->estado == 'activo' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $u->estado }}
                                </span>
                            </td>
                            <td>{{ $u->telefono }}</td>
                            <td>{{ $u->created_at }}</td>
                            <td>
                                <button type="button" class="btn btn-sm"
                                        style="background:var(--danger);color:#fff;border:none;padding:.4rem .8rem;border-radius:var(--radius-sm);cursor:pointer;font-size:.8rem;font-weight:600;"
                                        onclick="abrirConfirm({{ $u->id }}, '{{ addslashes($u->name) }}')">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                                <form id="form-delete-{{ $u->id }}" method="POST"
                                      action="{{ route('admin.usuarios.destroy', $u) }}"
                                      style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">
                                <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination', ['paginator' => $usuarios, 'perPage' => $perPage])

    </div>
</div>

<div id="modal-confirm" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="text-align:center;margin-bottom:1.25rem;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.4rem;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:.4rem;">¿Eliminar usuario?</h3>
            <p style="font-size:.88rem;color:#6b7280;" id="confirm-nombre"></p>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:1.25rem;">
            <input type="checkbox" id="confirm-check" style="accent-color:#dc2626;width:16px;height:16px;">
            <span style="font-size:.85rem;color:#991b1b;font-weight:500;">Entiendo que esta acción no se puede deshacer</span>
        </label>
        <div style="display:flex;gap:.75rem;">
            <button type="button" onclick="cerrarConfirm()"
                    style="flex:1;padding:.7rem;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#fff;cursor:pointer;font-size:.88rem;font-weight:600;color:#374151;">
                Cancelar
            </button>
            <button type="button" id="btn-confirmar-delete" onclick="ejecutarDelete()" disabled
                    style="flex:1;padding:.7rem;border:none;border-radius:.5rem;background:#dc2626;color:#fff;cursor:pointer;font-size:.88rem;font-weight:600;opacity:.5;transition:opacity .2s;">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let deleteId = null;

function abrirConfirm(id, nombre) {
    deleteId = id;
    document.getElementById('confirm-nombre').textContent = 'Vas a eliminar a: ' + nombre;
    document.getElementById('confirm-check').checked = false;
    document.getElementById('btn-confirmar-delete').disabled = true;
    document.getElementById('btn-confirmar-delete').style.opacity = '.5';
    const modal = document.getElementById('modal-confirm');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarConfirm() {
    deleteId = null;
    document.getElementById('modal-confirm').style.display = 'none';
    document.body.style.overflow = '';
}

function ejecutarDelete() {
    if (deleteId) document.getElementById('form-delete-' + deleteId).submit();
}

document.getElementById('confirm-check').addEventListener('change', function () {
    const btn = document.getElementById('btn-confirmar-delete');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
});

document.getElementById('modal-confirm').addEventListener('click', function (e) {
    if (e.target === this) cerrarConfirm();
});

function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
}

window.addEventListener('load', function () {
    if ("{{ request('id') }}" || "{{ request('name') }}" || "{{ request('email') }}") {
        document.getElementById('filtrosBox').style.display = 'block';
    }
});
</script>
@endpush

@endsection