@extends('layouts.admin')

@section('title', 'Empresas')
@section('page-title', 'Empresas')
@section('page-subtitle', 'Aprueba, edita y administra las empresas registradas')

@section('content')

{{-- Notificaciones pendientes --}}
@if($notifCount > 0)
<div class="dash-card" style="border-left:4px solid var(--warning);margin-bottom:1.5rem;">
    <div class="dash-card-header">
        <h2>
            <i class="fa-solid fa-bell" style="color:var(--warning);margin-right:6px"></i>
            Solicitudes pendientes
            <span class="badge badge-warning" style="margin-left:.5rem;">{{ $notifCount }}</span>
        </h2>
        
        <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-small btn-edit">
                <i class="fa-solid fa-check-double fa-xs"></i> Marcar todas leídas
            </button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>Empresa</th><th>Solicitud</th><th>Fecha</th><th>Acción</th></tr>
            </thead>
            <tbody>
                @foreach($notificaciones as $notif)
                <tr style="background:#fffbeb;">
                    <td><strong>{{ $notif->empresa->nombre ?? '—' }}</strong></td>
                    <td style="max-width:500px;white-space:pre-wrap;font-size:.88rem;">{{ $notif->mensaje }}</td>
                    <td style="white-space:nowrap;color:var(--gray);">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.notificaciones.leer', $notif) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-small btn-success">
                                <i class="fa-solid fa-check fa-xs"></i> Leída
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Formulario edición --}}
@isset($empresa)
<div class="admin-section">
    <h2>
        <i class="fa-solid fa-pen-to-square" style="color:var(--primary);margin-right:.4rem;"></i>
        Editar Empresa: {{ $empresa->nombre }}
    </h2>
    <form method="POST" action="{{ route('admin.empresas.update', $empresa) }}" class="admin-form">
        @csrf @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required maxlength="200"
                       value="{{ old('nombre', $empresa->nombre) }}">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" maxlength="30"
                       value="{{ old('telefono', $empresa->telefono) }}">
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" maxlength="400"
                   value="{{ old('direccion', $empresa->direccion) }}">
        </div>

        <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Actualizar Empresa
            </button>
            <a href="{{ route('admin.empresas.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@endisset


{{-- Tabla empresas --}}
<div class="admin-section">

    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Empresas Registradas</h2>
        <span class="badge badge-info">{{ $empresas->count() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Responsable</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresas as $e)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $e->id }}</td>
                        <td><strong>{{ $e->nombre }}</strong></td>
                        <td>{{ $e->usuario->name ?? '—' }}</td>
                        <td>{{ $e->telefono ?? '—' }}</td>
                        <td>{{ $e->direccion ?? '—' }}</td>
                        <td>
                            @if($e->aprobado)
                                <span class="badge badge-success">
                                    <i class="fa-solid fa-check fa-xs"></i> Aprobada
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    <i class="fa-solid fa-clock fa-xs"></i> Pendiente
                                </span>
                            @endif
                        </td>
                        <td>
                            @unless($e->aprobado)
                                <form method="POST" action="{{ route('admin.empresas.aprobar', $e) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-small btn-success btn-sm">
                                        <i class="fa-solid fa-check fa-xs"></i> Aprobar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.empresas.rechazar', $e) }}" style="display:inline"
                                      onsubmit="return confirm('¿Rechazar y eliminar esta empresa?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-small btn-delete btn-sm">
                                        <i class="fa-solid fa-xmark fa-xs"></i> Rechazar
                                    </button>
                                </form>
                            @endunless
                            <a href="{{ route('admin.empresas.edit', $e) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.empresas.destroy', $e) }}" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar esta empresa definitivamente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay empresas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        
        <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
    <a href="{{ route('admin.empresas.export.excel') }}" class="btn btn-success btn-sm">
        Excel
    </a>

    <a href="{{ route('admin.empresas.export.pdf') }}" class="btn btn-danger btn-sm">
        PDF
    </a>

    <form action="{{ route('admin.empresas.import.excel') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        <input type="file" name="archivo" required>
        <button type="submit" class="btn btn-primary btn-sm">
            Importar
        </button>
    </form>
</div>

    </div>
</div>

@endsection
