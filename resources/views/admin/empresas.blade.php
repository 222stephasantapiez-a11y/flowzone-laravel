<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand"><h2>FlowZone Admin</h2></div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.lugares.index') }}">📍 Lugares</a>
            <a href="{{ route('admin.hoteles.index') }}">🏨 Hoteles</a>
            <a href="{{ route('admin.eventos.index') }}">📅 Eventos</a>
            <a href="{{ route('admin.empresas.index') }}" class="active">🏢 Empresas</a>
            <a href="{{ route('admin.reservas.index') }}">📋 Reservas</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1> Gestión de Empresas</h1></div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="background:#f8d7da;padding:10px;margin-bottom:15px;border-radius:4px;">
                <strong>Corrige los siguientes errores:</strong>
                <ul style="margin:5px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── Notificaciones pendientes ── --}}
        @if($notifCount > 0)
        <div class="admin-section" style="border-left:4px solid #f59e0b;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <h2 style="margin:0;">🔔 Solicitudes pendientes de empresas
                    <span style="background:#f59e0b;color:#fff;border-radius:20px;padding:2px 10px;font-size:.8rem;margin-left:8px;">{{ $notifCount }}</span>
                </h2>
                <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}">
                    @csrf
                    <button type="submit" class="btn-small btn-edit">Marcar todas como leídas</button>
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
                            <td style="max-width:500px;white-space:pre-wrap;font-size:.9rem;">{{ $notif->mensaje }}</td>
                            <td style="white-space:nowrap;">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.notificaciones.leer', $notif) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-small btn-success">✓ Marcar leída</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Formulario de edición (solo aparece al hacer click en Editar) --}}
        @isset($empresa)
        <div class="admin-section">
            <h2>Editar Empresa</h2>
            <form method="POST" action="{{ route('admin.empresas.update', $empresa) }}" class="admin-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" required value="{{ old('nombre', $empresa->nombre) }}">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}">
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Empresa</button>
                <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        @endisset

        {{-- Tabla de empresas --}}
        <div class="admin-section">
            <h2>Empresas Registradas ({{ $empresas->count() }})</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                                <td>{{ $e->id }}</td>
                                <td><strong>{{ $e->nombre }}</strong></td>
                                <td>{{ $e->usuario->name ?? '—' }}</td>
                                <td>{{ $e->telefono ?? '—' }}</td>
                                <td>{{ $e->direccion ?? '—' }}</td>
                                <td>
                                    @if($e->aprobado)
                                        <span style="color:green;font-weight:bold;">✓ Aprobada</span>
                                    @else
                                        <span style="color:orange;font-weight:bold;">⏳ Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Aprobar (solo si está pendiente) --}}
                                    @unless($e->aprobado)
                                        <form method="POST" action="{{ route('admin.empresas.aprobar', $e) }}" style="display:inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-small btn-edit">✓ Aprobar</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.empresas.rechazar', $e) }}" style="display:inline"
                                              onsubmit="return confirm('¿Rechazar y eliminar esta empresa?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-small btn-delete">✕ Rechazar</button>
                                        </form>
                                    @endunless

                                    {{-- Editar datos --}}
                                    <a href="{{ route('admin.empresas.edit', $e) }}" class="btn-small btn-edit">Editar</a>

                                    {{-- Eliminar --}}
                                    <form method="POST" action="{{ route('admin.empresas.destroy', $e) }}" style="display:inline"
                                          onsubmit="return confirm('¿Eliminar esta empresa definitivamente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small btn-delete">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7">No hay empresas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>
