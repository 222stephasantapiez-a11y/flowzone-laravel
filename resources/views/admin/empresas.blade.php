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

{{-- SECCIÓN DEL GENERADOR DE PLANES --}}
<div class="admin-section" style="margin-bottom: 2rem; background: #f8fafc; border: 2px dashed #cbd5e1;">
    <div class="admin-section-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--primary);"></i> Generador de Planes Turísticos</h2>
        <a href="{{ route('admin.empresas.index', ['generar' => 'true']) }}" class="btn btn-primary">
            <i class="fa-solid fa-dice"></i> Generar Nuevo Plan
        </a>
    </div>

    @if(isset($plan))
        <div style="margin-top: 1.5rem; background: white; border-radius: 15px; shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); overflow: hidden; border: 1px solid #e2e8f0;">
            <div style="background: var(--primary); color: white; padding: 1rem; text-align: center; font-weight: bold;">
                ¡OFERTA ESPECIAL: PLAN TURÍSTICO CON 20% DE DESCUENTO!
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; padding: 1.5rem;">
                <div style="padding: 1rem; border-left: 4px solid #6366f1; background: #f5f3ff;">
                    <small style="color: #6366f1; font-weight: bold; text-transform: uppercase;">Evento</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['evento']->nombre }}</p>
                </div>
                <div style="padding: 1rem; border-left: 4px solid #f97316; background: #fff7ed;">
                    <small style="color: #f97316; font-weight: bold; text-transform: uppercase;">Gastronomía</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['gastronomia']->nombre }}</p>
                </div>
                <div style="padding: 1rem; border-left: 4px solid #22c55e; background: #f0fdf4;">
                    <small style="color: #22c55e; font-weight: bold; text-transform: uppercase;">Hotel</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['hotel']->nombre }}</p>
                </div>
                <div style="padding: 1rem; border-left: 4px solid #3b82f6; background: #eff6ff;">
                    <small style="color: #3b82f6; font-weight: bold; text-transform: uppercase;">Lugar</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['lugar']->nombre }}</p>
                </div>
            </div>

            <div style="background: #1e293b; color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="text-decoration: line-through; color: #94a3b8; margin-right: 1rem;">Antes: ${{ number_format($plan['subtotal'], 0, ',', '.') }}</span>
                    <span style="background: #ef4444; padding: 2px 8px; border-radius: 5px; font-size: 0.8rem;">-20% DCTO</span>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 0.9rem; color: #94a3b8;">Precio Total Plan:</p>
                    <p style="margin: 0; font-size: 1.8rem; font-weight: bold; color: #fbbf24;">${{ number_format($plan['precioFinal'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @else
        <p style="text-align: center; color: #64748b; padding: 1rem;">Haz clic en el botón para generar una combinación aleatoria con descuento.</p>
    @endif
</div>
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
