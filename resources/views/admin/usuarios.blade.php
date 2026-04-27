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
        <div id="filtrosBox" style="display:none; margin-bottom:1rem;">
            <form method="GET" action="{{ route('admin.usuarios.index') }}">

                <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">

                    <div>
                        <label>ID</label><br>
                        <input type="number" name="id" value="{{ request('id') }}">
                    </div>

                    <div>
                        <label>Nombre</label><br>
                        <input type="text" name="name" value="{{ request('name') }}">
                    </div>

                    <div>
                        <label>Email</label><br>
                        <input type="text" name="email" value="{{ request('email') }}">
                    </div>

                    <div style="display:flex; gap:.5rem;">
                        <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
                    </div>

                </div>

            </form>
        </div>

        <!-- TABLA -->
       
        <div class="table-responsive">
            @php
            $sort = request('sort', 'id');
            $direction = request('direction', 'asc');
            @endphp
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>
            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), [
                'sort' => 'id',
                'direction' => ($sort == 'id' && $direction == 'asc') ? 'desc' : 'asc'
            ])) }}">
                ID
            </a>
        </th>

        <th>
            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), [
                'sort' => 'name',
                'direction' => ($sort == 'name' && $direction == 'asc') ? 'desc' : 'asc'
            ])) }}">
                Nombre
            </a>
        </th>

        <th>
            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), [
                'sort' => 'email',
                'direction' => ($sort == 'email' && $direction == 'asc') ? 'desc' : 'asc'
            ])) }}">
                Email
            </a>
        </th>

        <th>Rol</th>
        <th>Estado</th>
        <th>Teléfono</th>

        <th>
            <a href="{{ route('admin.usuarios.index', array_merge(request()->all(), [
                'sort' => 'created_at',
                'direction' => ($sort == 'created_at' && $direction == 'asc') ? 'desc' : 'asc'
            ])) }}">
                Fecha
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
    @if($usuarios->hasPages())
    <div class="pagination-bar">

        <div class="pagination-info">
            Mostrando <strong>{{ $usuarios->firstItem() }}</strong>–<strong>{{ $usuarios->lastItem() }}</strong>
            de <strong>{{ $usuarios->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($usuarios->onFirstPage())
                <span class="page-btn page-btn--disabled">‹</span>
            @else
                <a href="{{ $usuarios->previousPageUrl() }}" class="page-btn">‹</a>
            @endif

            @foreach($usuarios->getUrlRange(max(1,$usuarios->currentPage()-2), min($usuarios->lastPage(),$usuarios->currentPage()+2)) as $page => $url)
                @if($page == $usuarios->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($usuarios->hasMorePages())
                <a href="{{ $usuarios->nextPageUrl() }}" class="page-btn">›</a>
            @else
                <span class="page-btn page-btn--disabled">›</span>
            @endif
        </div>

        <form method="GET">
            @foreach(request()->except(['page','per_page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach

            <label>Filas:</label>
            <select name="per_page" onchange="this.form.submit()">
                @foreach([5,10,25,50,100] as $n)
                    <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>

    </div>
    @endif

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