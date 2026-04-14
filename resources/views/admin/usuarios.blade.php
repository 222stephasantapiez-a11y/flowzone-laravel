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
    </div>
</div>

@endsection