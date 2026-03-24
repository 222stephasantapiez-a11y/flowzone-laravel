<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas - FlowZone Admin</title>
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
            <a href="{{ route('admin.empresas.index') }}">🏢 Empresas</a>
            <a href="{{ route('admin.reservas.index') }}" class="active">📋 Reservas</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-header"><h1>Gestión de Reservas</h1></div>

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

        {{-- Formulario crear / editar --}}
        <div class="admin-section">
            @isset($reserva)
                <h2>Editar Reserva #{{ $reserva->id }}</h2>
                <form method="POST" action="{{ route('admin.reservas.update', $reserva) }}" class="admin-form">
                    @method('PUT')
            @else
                <h2>Nueva Reserva</h2>
                <form method="POST" action="{{ route('admin.reservas.store') }}" class="admin-form">
            @endisset

            @csrf

            <div class="form-row">
                {{-- Select de usuario --}}
                <div class="form-group">
                    <label>Usuario</label>
                    <select name="usuario_id" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}"
                                {{ old('usuario_id', $reserva->usuario_id ?? '') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Select de hotel (relación hotel_id) --}}
                <div class="form-group">
                    <label>Hotel</label>
                    <select name="hotel_id" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($hoteles as $h)
                            <option value="{{ $h->id }}"
                                {{ old('hotel_id', $reserva->hotel_id ?? '') == $h->id ? 'selected' : '' }}>
                                {{ $h->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fecha Entrada</label>
                    <input type="date" name="fecha_entrada" required
                        value="{{ old('fecha_entrada', isset($reserva) ? $reserva->fecha_entrada->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label>Fecha Salida</label>
                    <input type="date" name="fecha_salida" required
                        value="{{ old('fecha_salida', isset($reserva) ? $reserva->fecha_salida->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label>Nº Personas</label>
                    <input type="number" name="num_personas" min="1" required
                        value="{{ old('num_personas', $reserva->num_personas ?? '1') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Precio Total</label>
                    <input type="number" step="0.01" name="precio_total" min="0" required
                        value="{{ old('precio_total', $reserva->precio_total ?? '0') }}">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" required>
                        @foreach(['pendiente', 'confirmada', 'cancelada'] as $estado)
                            <option value="{{ $estado }}"
                                {{ old('estado', $reserva->estado ?? 'pendiente') === $estado ? 'selected' : '' }}>
                                {{ ucfirst($estado) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($reserva) ? 'Actualizar Reserva' : 'Guardar Reserva' }}
            </button>

            @isset($reserva)
                <a href="{{ route('admin.reservas.index') }}" class="btn btn-secondary">Cancelar</a>
            @endisset

            </form>
        </div>

        {{-- Tabla de reservas --}}
        <div class="admin-section">
            <h2>Todas las Reservas</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Hotel</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Personas</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->usuario->name ?? '—' }}</td>
                            <td>{{ $r->hotel->nombre ?? '—' }}</td>
                            <td>{{ $r->fecha_entrada->format('d/m/Y') }}</td>
                            <td>{{ $r->fecha_salida->format('d/m/Y') }}</td>
                            <td>{{ $r->num_personas }}</td>
                            <td>${{ number_format($r->precio_total, 0) }}</td>
                            <td>
                                @php
                                    $colores = ['pendiente' => 'orange', 'confirmada' => 'green', 'cancelada' => 'red'];
                                @endphp
                                <span style="color: {{ $colores[$r->estado] ?? 'gray' }}; font-weight:bold;">
                                    {{ ucfirst($r->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.reservas.edit', $r) }}" class="btn-small btn-edit">Editar</a>

                                <form method="POST" action="{{ route('admin.reservas.destroy', $r) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('¿Eliminar esta reserva?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9">No hay reservas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</div>
</body>
</html>
