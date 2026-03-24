<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Eventos - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand"><h2>🌄 FlowZone Admin</h2></div>
        <nav class="admin-nav">
            <a href="#">📊 Dashboard</a>
            <a href="{{ route('admin.lugares.index') }}">📍 Lugares</a>
            <a href="{{ route('admin.hoteles.index') }}">🏨 Hoteles</a>
            <a href="{{ route('admin.eventos.index') }}" class="active">📅 Eventos</a>
            <a href="#">🏢 Empresas</a>
            <a href="#">📋 Reservas</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Gestión de Eventos</h1></div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario crear / editar --}}
        <div class="admin-section">
            @isset($evento)
                <h2>Editar Evento</h2>
                <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" class="admin-form">
                    @method('PUT')
            @else
                <h2>Agregar Evento</h2>
                <form method="POST" action="{{ route('admin.eventos.store') }}" class="admin-form">
            @endisset

            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required value="{{ old('nombre', $evento->nombre ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <input type="text" name="categoria" value="{{ old('categoria', $evento->categoria ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" required>{{ old('descripcion', $evento->descripcion ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fecha</label>
                    {{-- Al editar, Carbon ya parseó la fecha; usamos format() para el input --}}
                    <input type="date" name="fecha" required
                        value="{{ old('fecha', isset($evento) ? $evento->fecha->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label>Hora</label>
                    <input type="time" name="hora" value="{{ old('hora', $evento->hora ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio', $evento->precio ?? '0') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" value="{{ old('ubicacion', $evento->ubicacion ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Organizador</label>
                    <input type="text" name="organizador" value="{{ old('organizador', $evento->organizador ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Contacto</label>
                    <input type="text" name="contacto" value="{{ old('contacto', $evento->contacto ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>URL de Imagen</label>
                <input type="url" name="imagen" required value="{{ old('imagen', $evento->imagen ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($evento) ? 'Actualizar Evento' : 'Guardar Evento' }}
            </button>

            @isset($evento)
                <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary">Cancelar</a>
            @endisset

            </form>
        </div>

        {{-- Tabla de eventos --}}
        <div class="admin-section">
            <h2>Eventos Registrados</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Ubicación</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
                            <td>{{ $e->nombre }}</td>
                            <td>{{ $e->fecha->format('d/m/Y') }}</td>
                            <td>{{ $e->ubicacion }}</td>
                            <td>${{ number_format($e->precio, 0) }}</td>
                            <td>
                                <a href="{{ route('admin.eventos.edit', $e) }}" class="btn-small btn-edit">Editar</a>

                                <form method="POST" action="{{ route('admin.eventos.destroy', $e) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('¿Eliminar este evento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No hay eventos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>
</body>
</html>
