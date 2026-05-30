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

{{-- ══ MODAL CONFIRMAR ══ --}}
<div id="modal-confirmar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:9999;overflow-y:auto;padding:2rem 1rem;">
    <div style="background:#fff;border-radius:1rem;max-width:460px;margin:0 auto;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;">
        <div style="background:linear-gradient(135deg,#166534,#16a34a);padding:1.25rem 1.75rem;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="color:#fff;font-size:1rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-circle-check"></i> Confirmar reserva
            </h3>
            <button onclick="cerrarModal('modal-confirmar')" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div style="padding:1.75rem;">
            <p style="color:var(--gray-700);margin-bottom:1.25rem;font-size:.95rem;">
                ¿Confirmas esta reserva? El usuario recibirá su reserva como <strong>confirmada</strong>.
            </p>
            <label style="display:flex;align-items:flex-start;gap:.6rem;cursor:pointer;margin-bottom:1.5rem;font-size:.88rem;color:var(--gray-700);">
                <input type="checkbox" id="check-confirmar" style="margin-top:.15rem;accent-color:#16a34a;width:16px;height:16px;flex-shrink:0;">
                Entiendo que esta acción notificará al usuario
            </label>
            <form id="form-confirmar" method="POST" style="display:flex;gap:.75rem;">
                @csrf @method('PATCH')
                <input type="hidden" name="estado" value="confirmada">
                <button type="button" onclick="cerrarModal('modal-confirmar')" class="btn btn-outline" style="flex:1;">Cancelar</button>
                <button type="submit" id="btn-confirmar" disabled class="btn btn-primary" style="flex:1;opacity:.5;cursor:not-allowed;">
                    <i class="fa-solid fa-check fa-xs"></i> Confirmar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══ MODAL CANCELAR ══ --}}
<div id="modal-cancelar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:9999;overflow-y:auto;padding:2rem 1rem;">
    <div style="background:#fff;border-radius:1rem;max-width:460px;margin:0 auto;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;">
        <div style="background:linear-gradient(135deg,#991b1b,#ef4444);padding:1.25rem 1.75rem;display:flex;align-items:center;justify-content:space-between;">
            <h3 style="color:#fff;font-size:1rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-ban"></i> Cancelar reserva
            </h3>
            <button onclick="cerrarModal('modal-cancelar')" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div style="padding:1.75rem;">
            <p style="color:var(--gray-700);margin-bottom:1.25rem;font-size:.95rem;">
                ¿Cancelar esta reserva? Esta acción cambiará el estado a <strong>cancelada</strong>.
            </p>
            <label style="display:flex;align-items:flex-start;gap:.6rem;cursor:pointer;margin-bottom:1.5rem;font-size:.88rem;color:var(--gray-700);">
                <input type="checkbox" id="check-cancelar" style="margin-top:.15rem;accent-color:#ef4444;width:16px;height:16px;flex-shrink:0;">
                Entiendo que esta acción no se puede deshacer fácilmente
            </label>
            <form id="form-cancelar" method="POST" style="display:flex;gap:.75rem;">
                @csrf @method('PATCH')
                <input type="hidden" name="estado" value="cancelada">
                <button type="button" onclick="cerrarModal('modal-cancelar')" class="btn btn-outline" style="flex:1;">Volver</button>
                <button type="submit" id="btn-cancelar" disabled class="btn btn-danger" style="flex:1;opacity:.5;cursor:not-allowed;">
                    <i class="fa-solid fa-ban fa-xs"></i> Cancelar reserva
                </button>
            </form>
        </div>
    </div>
</div>

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
                    <td style="white-space:nowrap;display:flex;flex-direction:column;gap:.3rem;">
                        @if($r->estado === 'pendiente')
                            <button type="button"
                                    onclick="abrirConfirmar('{{ route('empresa.reservas.estado', $r) }}')"
                                    class="btn btn-sm" style="background:#16a34a;color:#fff;font-size:.78rem;padding:.3rem .75rem;border:none;border-radius:var(--radius-md);cursor:pointer;display:flex;align-items:center;gap:.35rem;">
                                <i class="fa-solid fa-check fa-xs"></i> Confirmar
                            </button>
                            <button type="button"
                                    onclick="abrirCancelar('{{ route('empresa.reservas.estado', $r) }}')"
                                    class="btn btn-sm" style="background:#ef4444;color:#fff;font-size:.78rem;padding:.3rem .75rem;border:none;border-radius:var(--radius-md);cursor:pointer;display:flex;align-items:center;gap:.35rem;">
                                <i class="fa-solid fa-ban fa-xs"></i> Cancelar
                            </button>
                        @elseif($r->estado === 'confirmada')
                            <button type="button"
                                    onclick="abrirCancelar('{{ route('empresa.reservas.estado', $r) }}')"
                                    class="btn btn-sm" style="background:#ef4444;color:#fff;font-size:.78rem;padding:.3rem .75rem;border:none;border-radius:var(--radius-md);cursor:pointer;display:flex;align-items:center;gap:.35rem;">
                                <i class="fa-solid fa-ban fa-xs"></i> Cancelar
                            </button>
                        @else
                            <span style="font-size:.75rem;color:var(--gray-400);">—</span>
                        @endif
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

@push('scripts')
<script>
function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}

function abrirConfirmar(url) {
    document.getElementById('form-confirmar').action = url;
    document.getElementById('check-confirmar').checked = false;
    document.getElementById('btn-confirmar').disabled = true;
    document.getElementById('btn-confirmar').style.opacity = '.5';
    document.getElementById('btn-confirmar').style.cursor = 'not-allowed';
    document.getElementById('modal-confirmar').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function abrirCancelar(url) {
    document.getElementById('form-cancelar').action = url;
    document.getElementById('check-cancelar').checked = false;
    document.getElementById('btn-cancelar').disabled = true;
    document.getElementById('btn-cancelar').style.opacity = '.5';
    document.getElementById('btn-cancelar').style.cursor = 'not-allowed';
    document.getElementById('modal-cancelar').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

document.getElementById('check-confirmar').addEventListener('change', function() {
    const btn = document.getElementById('btn-confirmar');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
    btn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
});

document.getElementById('check-cancelar').addEventListener('change', function() {
    const btn = document.getElementById('btn-cancelar');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
    btn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
});

document.getElementById('modal-confirmar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal('modal-confirmar');
});
document.getElementById('modal-cancelar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal('modal-cancelar');
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal('modal-confirmar');
        cerrarModal('modal-cancelar');
    }
});
</script>
@endpush