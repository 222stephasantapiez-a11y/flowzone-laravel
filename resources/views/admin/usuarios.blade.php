@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administra usuarios registrados')

@section('content')

<div class="admin-section">

    <div class="admin-section-header">
        <h2>Usuarios registrados</h2>

        <div style="display:flex; gap:.5rem;">
            <a href="{{ route('admin.usuarios.export.excel') }}" class="btn btn-success btn-sm">
                Excel
            </a>

            <a href="{{ route('admin.usuarios.export.pdf') }}" class="btn btn-danger btn-sm">
                PDF
            </a>

            <span class="badge badge-info">
                {{ $usuarios->count() }} total
            </span>
        </div>
    </div>

    <form action="{{ route('admin.usuarios.import.excel') }}"
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

<!-- BOTÓN FILTRAR -->


<!-- FORMULARIO OCULTO -->


    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha registro</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay usuarios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <form method="GET" action="{{ route('admin.usuarios.index') }}"
      class="admin-form"
      id="filtrosBox"
      style="margin-bottom:1rem;">

      <button onclick="toggleFiltros()" class="btn btn-primary" style="margin-bottom:1rem;">
    <i class="fa-solid fa-filter"></i> Filtrar
</button>

    <div class="form-row">

        <!-- ID -->
        <div class="form-group">
            <label>ID</label>
            <input type="number" name="id"
                value="{{ request('id') }}">
        </div>

        <!-- NOMBRE -->
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre"
                value="{{ request('nombre') }}">
        </div>

        <!-- EMAIL -->
        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email"
                value="{{ request('email') }}">
        </div>

        <!-- BOTÓN -->
        <div class="form-group" style="display:flex;align-items:end;">
            <button type="submit" class="btn btn-primary">
                Aplicar
            </button>
        </div>

    </div>
</form>

<!-- SCRIPT -->
<script>
function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

window.onload = function() {
    const hasFilters =
        "{{ request('id') }}" ||
        "{{ request('nombre') }}" ||
        "{{ request('email') }}";

    if (hasFilters) {
        document.getElementById('filtrosBox').style.display = 'block';
    }
}
</script>
    </div>
</div>

@endsection