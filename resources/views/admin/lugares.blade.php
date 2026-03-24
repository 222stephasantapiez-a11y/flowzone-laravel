<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Lugares - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand"><h2>FlowZone Admin</h2></div>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.lugares.index') }}" class="active">📍 Lugares</a>
            <a href="{{ route('admin.hoteles.index') }}">🏨 Hoteles</a>
            <a href="{{ route('admin.eventos.index') }}">📅 Eventos</a>
            <a href="{{ route('admin.empresas.index') }}">🏢 Empresas</a>
            <a href="{{ route('admin.reservas.index') }}" >📋 Reservas</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Gestión de Lugares Turísticos</h1></div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario crear / editar --}}
        <div class="admin-section">
            @isset($lugar)
                <h2>Editar Lugar</h2>
                <form method="POST" action="{{ route('admin.lugares.update', $lugar) }}" class="admin-form">
                    @method('PUT')
            @else
                <h2>Agregar Lugar</h2>
                <form method="POST" action="{{ route('admin.lugares.store') }}" class="admin-form">
            @endisset

            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required value="{{ old('nombre', $lugar->nombre ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <input type="text" name="categoria" required value="{{ old('categoria', $lugar->categoria ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="4" required>{{ old('descripcion', $lugar->descripcion ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" value="{{ old('ubicacion', $lugar->ubicacion ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Horario</label>
                    <input type="text" name="horario" value="{{ old('horario', $lugar->horario ?? '') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Latitud</label>
                    <input type="number" step="0.00000001" name="latitud" value="{{ old('latitud', $lugar->latitud ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Longitud</label>
                    <input type="number" step="0.00000001" name="longitud" value="{{ old('longitud', $lugar->longitud ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Precio Entrada</label>
                    <input type="number" step="0.01" name="precio_entrada" value="{{ old('precio_entrada', $lugar->precio_entrada ?? '0') }}">
                </div>
            </div>

            <div class="form-group">
                <label>URL de Imagen</label>
                <input type="url" name="imagen" required value="{{ old('imagen', $lugar->imagen ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($lugar) ? 'Actualizar Lugar' : 'Guardar Lugar' }}
            </button>

            @isset($lugar)
                <a href="{{ route('admin.lugares.index') }}" class="btn btn-secondary">Cancelar</a>
            @endisset

            </form>
        </div>

        {{-- Tabla de lugares --}}
        <div class="admin-section">
            <h2>Lugares Registrados</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Ubicación</th>
                            <th>Precio Entrada</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lugares as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->nombre }}</td>
                                <td>{{ $l->categoria }}</td>
                                <td>{{ $l->ubicacion }}</td>
                                <td>${{ number_format($l->precio_entrada, 0) }}</td>
                                <td>
                                    <a href="{{ route('admin.lugares.edit', $l) }}" class="btn-small btn-edit">Editar</a>

                                    <form method="POST" action="{{ route('admin.lugares.destroy', $l) }}"
                                          style="display:inline"
                                          onsubmit="return confirm('¿Eliminar este lugar?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-small btn-delete">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No hay lugares registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>
