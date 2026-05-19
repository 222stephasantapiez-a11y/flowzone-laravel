@extends('layouts.empresa')

@section('page-title', 'Reservas')
@section('page-subtitle')Reservas de los hoteles de {{ $empresa->nombre }}@endsection

@section('content')
@php
    $estadoColors = [
        'pendiente'  => ['bg'=>'#fef9c3','color'=>'#854d0e','label'=>'Pendiente'],
        'confirmada' => ['bg'=>'#dcfce7','color'=>'#166534','label'=>'Confirmada'],
        'cancelada'  => ['bg'=>'#fee2e2','color'=>'#991b1b','label'=>'Cancelada'],
    ];
@endphp

{{-- ══ CARDS RESUMEN ══ --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div style="background:#fff;border-radius:var(--radius-lg);padding:1.25rem 1.5rem;border:1px solid var(--gray-200);box-shadow:0 2px 8px rgba(0,0,0,.05);">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--gray-400);letter-spacing:.06em;margin-bottom:.4rem;">Total reservas</div>
        <div style="font-size:2rem;font-weight:900;color:var(--gray-900);">{{ $totalReservas }}</div>
    </div>
    <div style="background:#fff;border-radius:var(--radius-lg);padding:1.25rem 1.5rem;border:1px solid var(--gray-200);box-shadow:0 2px 8px rgba(0,0,0,.05);">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--gray-400);letter-spacing:.06em;margin-bottom:.4rem;">Ingresos confirmados</div>
        <div style="font-size:1.6rem;font-weight:900;color:var(--green-700);">${{ number_format($ingresos, 0) }}</div>
        <div style="font-size:.72rem;color:var(--gray-400);">COP</div>
    </div>
    <div style="background:#fff;border-radius:var(--radius-lg);padding:1.25rem 1.5rem;border:1px solid var(--gray-200);box-shadow:0 2px 8px rgba(0,0,0,.05);">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--gray-400);letter-spacing:.06em;margin-bottom:.4rem;">Pendientes</div>
        <div style="font-size:2rem;font-weight:900;color:#d97706;">{{ $pendientes }}</div>
    </div>
    <div style="background:#fff;border-radius:var(--radius-lg);padding:1.25rem 1.5rem;border:1px solid var(--gray-200);box-shadow:0 2px 8px rgba(0,0,0,.05);">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;color:var(--gray-400);letter-spacing:.06em;margin-bottom:.4rem;">Esta página</div>
        <div style="font-size:2rem;font-weight:900;color:var(--gray-900);">{{ $reservas->count() }}</div>
        <div style="font-size:.72rem;color:var(--gray-400);">de {{ $reservas->total() }}</div>
    </div>
</div>

{{-- ══ FILTROS ══ --}}
<div class="admin-section" style="margin-bottom:1.25rem;padding:.85rem 1.25rem;">
    <form method="GET" action="{{ route('empresa.reservas.index') }}"
          style="display:flex;align-items:flex-end;gap:.75rem;flex-wrap:wrap;">
        <div>
            <label style="display:block;font-size:.72rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;margin-bottom:.3rem;">Estado</label>
            <select name="estado" style="padding:.45rem .85rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
                <option value="">Todos</option>
                @foreach(['pendiente'=>'Pendiente','confirmada'=>'Confirmada','cancelada'=>'Cancelada'] as $v => $l)
                <option value="{{ $v }}" {{ request('estado') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block;font-size:.72rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;margin-bottom:.3rem;">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                   style="padding:.45rem .85rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
        </div>
        <div>
            <label style="display:block;font-size:.72rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;margin-bottom:.3rem;">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                   style="padding:.45rem .85rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-magnifying-glass fa-xs"></i> Filtrar
        </button>
        @if(request()->hasAny(['estado','fecha_inicio','fecha_fin']))
        <a href="{{ route('empresa.reservas.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-xmark fa-xs"></i> Limpiar
        </a>
        @endif
    </form>
</div>

{{-- ══ TABLA RESERVAS ══ --}}
<div class="admin-section" style="margin-bottom:1.5rem;">
    <h2 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-calendar-check" style="color:var(--green-600);"></i> Reservas
        <span class="badge badge-info">{{ $reservas->total() }}</span>
    </h2>

    @if($reservas->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-calendar-xmark"></i>
        <p>No hay reservas que coincidan con los filtros.</p>
    </div>
    @else
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
                    <th>Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservas as $r)
                @php $ec = $estadoColors[$r->estado] ?? $estadoColors['pendiente']; @endphp
                <tr>
                    <td style="font-size:.8rem;color:var(--gray-400);">{{ $r->id }}</td>
                    <td>
                        <div style="font-weight:600;font-size:.88rem;">{{ $r->usuario?->name ?? '—' }}</div>
                        <div style="font-size:.75rem;color:var(--gray-400);">{{ $r->usuario?->email }}</div>
                    </td>
                    <td style="font-size:.88rem;">{{ $r->hotel?->nombre ?? '—' }}</td>
                    <td style="white-space:nowrap;font-size:.88rem;">{{ $r->fecha_entrada?->format('d/m/Y') }}</td>
                    <td style="white-space:nowrap;font-size:.88rem;">{{ $r->fecha_salida?->format('d/m/Y') }}</td>
                    <td style="text-align:center;">{{ $r->num_personas }}</td>
                    <td style="white-space:nowrap;font-weight:700;color:var(--green-700);">
                        ${{ number_format($r->precio_total, 0) }}
                    </td>
                    <td style="font-size:.82rem;">
                        {{ $r->metodo_pago_label ?? ucfirst(str_replace('_',' ',$r->metodo_pago ?? '—')) }}
                        @if($r->referencia_pago)
                        <div style="font-size:.72rem;color:var(--gray-400);">{{ $r->referencia_pago }}</div>
                        @endif
                    </td>
                    <td>
                        <span style="background:{{ $ec['bg'] }};color:{{ $ec['color'] }};border-radius:2rem;padding:.2rem .65rem;font-size:.78rem;font-weight:700;white-space:nowrap;">
                            {{ $ec['label'] }}
                        </span>
                    </td>
                    <td style="white-space:nowrap;">
                        @foreach(['pendiente','confirmada','cancelada'] as $est)
                        @if($r->estado !== $est)
                        <form method="POST" action="{{ route('empresa.reservas.estado', $r) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="estado" value="{{ $est }}">
                            <button type="submit" class="btn-small {{ $est === 'confirmada' ? 'btn-success' : ($est === 'cancelada' ? 'btn-delete' : 'btn-warning') }}"
                                    title="{{ ucfirst($est) }}"
                                    onclick="return confirm('¿Cambiar estado a {{ $est }}?')">
                                <i class="fa-solid fa-{{ $est === 'confirmada' ? 'check' : ($est === 'cancelada' ? 'ban' : 'clock') }} fa-xs"></i>
                            </button>
                        </form>
                        @endif
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($reservas->hasPages())
    <div style="margin-top:1rem;">
        {{ $reservas->links() }}
    </div>
    @endif
    @endif
</div>

{{-- ══ RESEÑAS Y CALIFICACIONES ══ --}}
<div class="admin-section">
    <h2 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-star" style="color:#fbbf24;"></i> Reseñas de tus hoteles
        <span class="badge badge-info">{{ $calificaciones->count() }}</span>
    </h2>

    @if($calificaciones->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-star-half-stroke"></i>
        <p>Aún no hay reseñas para tus hoteles.</p>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:.75rem;">
        @foreach($calificaciones as $cal)
        <div style="background:#f8fafc;border-radius:var(--radius-md);padding:1rem 1.25rem;border:1px solid var(--gray-100);">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-bottom:.4rem;">
                <div style="display:flex;align-items:center;gap:.6rem;">
                    <i class="fa-solid fa-circle-user" style="font-size:1.2rem;color:var(--green-600);"></i>
                    <div>
                        <div style="font-weight:700;font-size:.88rem;">{{ $cal->usuario?->name ?? 'Usuario' }}</div>
                        <div style="font-size:.75rem;color:var(--gray-400);">{{ $cal->created_at?->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div style="display:flex;gap:.15rem;">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fa-{{ $i <= $cal->calificacion ? 'solid' : 'regular' }} fa-star"
                       style="color:#fbbf24;font-size:.85rem;"></i>
                    @endfor
                    <span style="font-size:.82rem;font-weight:700;color:var(--gray-700);margin-left:.3rem;">{{ $cal->calificacion }}/5</span>
                </div>
            </div>
            @if($cal->comentario)
            <p style="font-size:.85rem;color:var(--gray-600);margin:0;line-height:1.5;">{{ $cal->comentario }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
