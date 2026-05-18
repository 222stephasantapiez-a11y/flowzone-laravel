@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administra usuarios registrados')

@section('content')

<div class="admin-section">

    <!-- HEADER PRINCIPAL -->
    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <h2 style="margin:0;"><i class="fa-solid fa-users"></i> Usuarios</h2>

        <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.usuarios.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>

            <a href="{{ route('admin.usuarios.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>

            @include('partials.import_modal', [
                'importRoute' => 'admin.usuarios.import.excel',
                'sampleFile'  => 'ejemplo_usuarios.xlsx',
                'modalId'     => 'importUsuarios',
                'columns'     => [
                    'name'  => 'Nombre completo del usuario (requerido)',
                    'email' => 'Correo electrónico único (requerido)',
                ],
            ])
        </div>
    </div>

    <!-- CARD -->

    </div>

     <div class="admin-section" >

        <!-- HEADER TABLA --> <div style="padding: 1.75rem;">
        <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-list" style="color:var(--primary);"></i>
            Usuarios Registrados
        </h2>
    <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                <span class="badge badge-info">
                    {{ $usuarios->total() }} total
                </span>

                <button type="button" onclick="toggleFiltros()" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-filter"></i><p>Filtro</p>
                </button>
            </div>

        </div>

        <!-- FILTROS -->
        <div id="filtrosBox" style="display:none; padding: 1rem 0 .5rem;">
            <form method="GET" action="{{ route('admin.usuarios.index') }}">
                <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">

                    <div class="filter-field">
                        <label class="filter-label">ID</label>
                        <input type="number" name="id" value="{{ request('id') }}"
                               placeholder="Ej: 5" class="filter-input">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Nombre</label>
                        <input type="text" name="name" value="{{ request('name') }}"
                               placeholder="Buscar por nombre..." class="filter-input">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Email</label>
                        <input type="text" name="email" value="{{ request('email') }}"
                               placeholder="Buscar por correo..." class="filter-input">
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

        <!-- TABLA -->
       
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    @php
                        $sort      = $sort ?? 'id';
                        $direction = $direction ?? 'asc';
                    @endphp
                    <tr>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                ID @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                Nombre @if($sort === 'name') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => ($sort === 'email' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                Email @if($sort === 'email') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Teléfono</th>
                        <th>
                            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                                Fecha @if($sort === 'created_at') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                            </a>
                        </th>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No hay usuarios</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    <!-- PAGINACIÓN -->
    @include('partials.pagination', ['paginator' => $usuarios, 'perPage' => $perPage])

</div>

<!-- SCRIPT -->
<script>
function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = (box.style.display === 'none' || box.style.display === '') 
        ? 'block' 
        : 'none';
}

window.addEventListener('load', function () {
    if (
        "{{ request('id') }}" ||
        "{{ request('name') }}" ||
        "{{ request('email') }}"
    ) {
        document.getElementById('filtrosBox').style.display = 'block';
    }
});
</script>

@endsection