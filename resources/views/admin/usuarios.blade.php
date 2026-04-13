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

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>rol</th>
                    <th>estado</th>
                    <th>telefono</th>
                    <th>Fecha registro</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->rol }}</td>
                        <td>{{ $u->estado}}</td>
                        <td>{{ $u->telefono}}</td>
                        <td>{{ $u->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay usuarios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection