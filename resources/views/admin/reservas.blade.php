@extends('layouts.admin')

@section('title', 'Reservas')
@section('page-title', 'Reservas')
@section('page-subtitle', 'Administra todas las reservas del sistema')

@section('content')

{{-- Formulario --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($reserva) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($reserva) ? 'Editar Reserva #' . $reserva->id : 'Reservas' }}
        </h2>
        @unless(isset($reserva))
            <a href="{{ route('admin.reservas.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nueva Reserva
            </a>
        @endunless
    </div>

    @isset($reserva)
        <form method="POST" action="{{ route('admin.reservas.update', $reserva) }}" class="admin-form">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.reservas.store') }}" class="admin-form">
    @endisset
    @csrf

    <div class="form-row">
        <div class="form-group">
            <label>Usuario *</label>
            <select name="usuario_id" required>
                <option value="">— Seleccionar —</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}"
                        {{ old('usuario_id', $reserva->usuario_id ?? '') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Hotel *</label>
            <select name="hotel_id" required>
                <option value="">— Seleccionar —</option>
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
            <label>Fecha Entrada *</label>
            <input type="date" name="fecha_entrada" required
                   value="{{ old('fecha_entrada', isset($reserva) ? $reserva->fecha_entrada->format('Y-m-d') : '') }}">
        </div>
        <div class="form-group">
            <label>Fecha Salida *</label>
            <input type="date" name="fecha_salida" required
                   value="{{ old('fecha_salida', isset($reserva) ? $reserva->fecha_salida->format('Y-m-d') : '') }}">
        </div>
        <div class="form-group">
            <label>Nº Personas *</label>
            <input type="number" name="num_personas" min="1" required
                   value="{{ old('num_personas', $reserva->num_personas ?? '1') }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Precio Total (COP) *</label>
            <input type="number" step="0.01" name="precio_total" min="0" required
                   value="{{ old('precio_total', $reserva->precio_total ?? '0') }}">
        </div>
        <div class="form-group">
            <label>Estado *</label>
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

    <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-{{ isset($reserva) ? 'floppy-disk' : 'plus' }}"></i>
            {{ isset($reserva) ? 'Actualizar Reserva' : 'Guardar Reserva' }}
        </button>
        @isset($reserva)
            <a href="{{ route('admin.reservas.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        @endisset
    </div>

    </form>
</div>

{{-- Tabla --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Todas las Reservas</h2>
        <span class="badge badge-info">{{ $reservas->count() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
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
                        <td style="color:var(--gray);font-size:.8rem;">{{ $r->id }}</td>
                        <td>{{ $r->usuario->name ?? '—' }}</td>
                        <td>{{ $r->hotel->nombre ?? '—' }}</td>
                        <td style="white-space:nowrap;">{{ $r->fecha_entrada->format('d/m/Y') }}</td>
                        <td style="white-space:nowrap;">{{ $r->fecha_salida->format('d/m/Y') }}</td>
                        <td>{{ $r->num_personas }}</td>
                        <td>${{ number_format($r->precio_total, 0) }}</td>
                        <td>
                            <span class="estado-badge estado-{{ $r->estado }}">{{ ucfirst($r->estado) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.reservas.edit', $r) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.reservas.destroy', $r) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar esta reserva?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay reservas registradas aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
