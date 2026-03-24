<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Hoteles - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand"><h2>FlowZone Admin</h2></div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.lugares.index') }}">📍 Lugares</a>
            <a href="{{ route('admin.hoteles.index') }}" class="active">🏨 Hoteles</a>
            <a href="{{ route('admin.eventos.index') }}">📅 Eventos</a>
            <a href="{{ route('admin.empresas.index') }}">🏢 Empresas</a>
            <a href="{{ route('admin.reservas.index') }}">📋 Reservas</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Gestión de Hoteles</h1></div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario: crear o editar --}}
        <div class="admin-section">
            @isset($hotel)
                <h2>Editar Hotel</h2>
                <form method="POST" action="{{ route('admin.hoteles.update', $hotel) }}" class="admin-form">
                    @method('PUT')
            @else
                <h2>Agregar Hotel</h2>
                <form method="POST" action="{{ route('admin.hoteles.store') }}" class="admin-form">
            @endisset

            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required value="{{ old('nombre', $hotel->nombre ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Precio por Noche</label>
                    <input type="number" name="precio" required value="{{ old('precio', $hotel->precio ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" required>{{ old('descripcion', $hotel->descripcion ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" value="{{ old('ubicacion', $hotel->ubicacion ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Capacidad</label>
                    <input type="number" name="capacidad" value="{{ old('capacidad', $hotel->capacidad ?? '') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Latitud</label>
                    <input type="number" step="0.00000001" name="latitud" value="{{ old('latitud', $hotel->latitud ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Longitud</label>
                    <input type="number" step="0.00000001" name="longitud" value="{{ old('longitud', $hotel->longitud ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Servicios (separados por coma)</label>
                <input type="text" name="servicios" value="{{ old('servicios', $hotel->servicios ?? '') }}">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $hotel->telefono ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $hotel->email ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>URL de Imagen</label>
                <input type="url" name="imagen" required value="{{ old('imagen', $hotel->imagen ?? '') }}">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="disponibilidad"
                        {{ old('disponibilidad', $hotel->disponibilidad ?? true) ? 'checked' : '' }}>
                    Disponible
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($hotel) ? 'Actualizar Hotel' : 'Guardar Hotel' }}
            </button>

            @isset($hotel)
                <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary">Cancelar</a>
            @endisset

            </form>
        </div>

        {{-- Tabla de hoteles --}}
        <div class="admin-section">
            <h2>Hoteles Registrados</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Ubicación</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hoteles as $h)
                        <tr>
                            <td>{{ $h->id }}</td>
                            <td>{{ $h->nombre }}</td>
                            <td>${{ number_format($h->precio, 0) }}</td>
                            <td>{{ $h->ubicacion }}</td>
                            <td>{{ $h->disponibilidad ? '✓' : '✗' }}</td>
                            <td>
                                <a href="{{ route('admin.hoteles.edit', $h) }}" class="btn-small btn-edit">Editar</a>

                                <form method="POST" action="{{ route('admin.hoteles.destroy', $h) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('¿Eliminar este hotel?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No hay hoteles registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>
</body>
</html>
