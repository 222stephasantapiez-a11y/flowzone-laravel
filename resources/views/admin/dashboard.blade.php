@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general del sistema · ' . now()->format('d/m/Y'))

@section('topbar-actions')
    @if($empresasPend > 0)
        <a href="{{ route('admin.empresas.index') }}" class="topbar-badge">
            <i class="fa-solid fa-bell"></i>
            {{ $empresasPend }} empresa(s) pendiente(s)
        </a>
    @endif
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     SECCIÓN 1 · TARJETAS PRINCIPALES
═══════════════════════════════════════════ --}}
<div class="db-section-label">
    <i class="fa-solid fa-layer-group"></i> Módulos del sistema
</div>

<div class="db-stats-grid">

    <a href="{{ route('admin.hoteles.index') }}" class="db-card db-card--green">
        <div class="db-card__icon"><i class="fa-solid fa-hotel"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalHoteles }}</span>
            <span class="db-card__label">Hoteles</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.lugares.index') }}" class="db-card db-card--blue">
        <div class="db-card__icon"><i class="fa-solid fa-map-location-dot"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalLugares }}</span>
            <span class="db-card__label">Lugares</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.eventos.index') }}" class="db-card db-card--orange">
        <div class="db-card__icon"><i class="fa-solid fa-calendar-days"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalEventos }}</span>
            <span class="db-card__label">Eventos</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.reservas.index') }}" class="db-card db-card--purple">
        <div class="db-card__icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalReservas }}</span>
            <span class="db-card__label">Reservas</span>
            @if($reservasPend > 0)
                <span class="db-card__badge db-card__badge--warn">{{ $reservasPend }} pendientes</span>
            @endif
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.empresas.index') }}" class="db-card db-card--teal">
        <div class="db-card__icon"><i class="fa-solid fa-building"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalEmpresas }}</span>
            <span class="db-card__label">Empresas</span>
            @if($empresasPend > 0)
                <span class="db-card__badge db-card__badge--warn">{{ $empresasPend }} por aprobar</span>
            @else
                <span class="db-card__badge db-card__badge--ok">Todas aprobadas</span>
            @endif
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.gastronomia.index') }}" class="db-card db-card--red">
        <div class="db-card__icon"><i class="fa-solid fa-utensils"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ \App\Models\Gastronomia::count() }}</span>
            <span class="db-card__label">Gastronomía</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.blog.index') }}" class="db-card db-card--indigo">
        <div class="db-card__icon"><i class="fa-solid fa-newspaper"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ \App\Models\BlogPost::count() }}</span>
            <span class="db-card__label">Blog</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('admin.usuarios.index') }}" class="db-card db-card--gray">
        <div class="db-card__icon"><i class="fa-solid fa-users"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalUsuarios }}</span>
            <span class="db-card__label">Usuarios</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

    <a href="{{ route('favoritos') }}" class="db-card db-card--rose">
        <div class="db-card__icon"><i class="fa-solid fa-heart"></i></div>
        <div class="db-card__body">
            <span class="db-card__num">{{ $totalFavoritos }}</span>
            <span class="db-card__label">Favoritos</span>
        </div>
        <i class="fa-solid fa-arrow-right db-card__arrow"></i>
    </a>

</div>

{{-- ═══════════════════════════════════════════
     SECCIÓN 2 · GRÁFICOS
═══════════════════════════════════════════ --}}
<div class="db-section-label">
    <i class="fa-solid fa-chart-column"></i> Estadísticas
</div>

<div class="db-charts-grid">

    {{-- Reservas por mes --}}
    <div class="db-panel">
        <div class="db-panel__header">
            <span class="db-panel__title">
                <i class="fa-solid fa-calendar-check" style="color:var(--primary)"></i>
                Reservas por mes
            </span>
        </div>
        <div class="db-panel__body">
            @if($mesData->sum() > 0)
                <canvas id="graficaReservasMes" height="120"></canvas>
            @else
                <div class="db-empty">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>Sin reservas registradas aún</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Reservas por estado --}}
    <div class="db-panel">
        <div class="db-panel__header">
            <span class="db-panel__title">
                <i class="fa-solid fa-chart-pie" style="color:#8b5cf6"></i>
                Reservas por estado
            </span>
        </div>
        <div class="db-panel__body db-panel__body--center">
            @if($totalReservas > 0)
                <canvas id="chartEstados" height="180"></canvas>
            @else
                <div class="db-empty">
                    <i class="fa-solid fa-inbox"></i>
                    <p>Sin reservas aún</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Registros por módulo --}}
    <div class="db-panel db-panel--wide">
        <div class="db-panel__header">
            <span class="db-panel__title">
                <i class="fa-solid fa-chart-bar" style="color:#06b6d4"></i>
                Registros por módulo
            </span>
        </div>
        <div class="db-panel__body">
            <canvas id="chartModulos" height="90"></canvas>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     SECCIÓN 4 · TOP CALIFICADOS
═══════════════════════════════════════════ --}}
@if($topHoteles->isNotEmpty() || $topLugares->isNotEmpty())
<div class="db-section-label">
    <i class="fa-solid fa-star"></i> Mejor calificados
</div>

<div class="db-charts-grid">
    @if($topHoteles->isNotEmpty())
    <div class="db-panel">
        <div class="db-panel__header">
            <span class="db-panel__title">
                <i class="fa-solid fa-hotel" style="color:var(--primary)"></i>
                Hoteles
            </span>
        </div>
        <div class="db-panel__body db-panel__body--flush">
            <table class="db-table">
                <thead><tr><th>Hotel</th><th>Promedio</th><th>Reseñas</th></tr></thead>
                <tbody>
                    @foreach($topHoteles as $h)
                    <tr>
                        <td>{{ $h->nombre }}</td>
                        <td>
                            <span class="db-rating">
                                <i class="fa-solid fa-star fa-xs"></i> {{ $h->promedio }}
                            </span>
                        </td>
                        <td><span class="db-count">{{ $h->total }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($topLugares->isNotEmpty())
    <div class="db-panel">
        <div class="db-panel__header">
            <span class="db-panel__title">
                <i class="fa-solid fa-map-pin" style="color:#f59e0b"></i>
                Lugares
            </span>
        </div>
        <div class="db-panel__body db-panel__body--flush">
            <table class="db-table">
                <thead><tr><th>Lugar</th><th>Promedio</th><th>Reseñas</th></tr></thead>
                <tbody>
                    @foreach($topLugares as $l)
                    <tr>
                        <td>{{ $l->nombre }}</td>
                        <td>
                            <span class="db-rating">
                                <i class="fa-solid fa-star fa-xs"></i> {{ $l->promedio }}
                            </span>
                        </td>
                        <td><span class="db-count">{{ $l->total }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endif

{{-- ═══════════════════════════════════════════
     SECCIÓN 5 · NOTIFICACIONES
═══════════════════════════════════════════ --}}
@if($notifCount > 0)
<div class="db-section-label">
    <i class="fa-solid fa-bell"></i> Solicitudes pendientes
    <span class="db-section-badge">{{ $notifCount }}</span>
</div>

<div class="db-panel db-panel--alert">
    <div class="db-panel__header">
        <span class="db-panel__title">
            <i class="fa-solid fa-building" style="color:var(--warning)"></i>
            Solicitudes de empresas
        </span>
        <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}">
            @csrf
            <button type="submit" class="db-link-btn">
                <i class="fa-solid fa-check-double fa-xs"></i> Marcar todas leídas
            </button>
        </form>
    </div>
    <div class="db-panel__body db-panel__body--flush">
        <table class="db-table">
            <thead>
                <tr><th>Empresa</th><th>Solicitud</th><th>Fecha</th><th>Acción</th></tr>
            </thead>
            <tbody>
                @foreach($notificaciones as $notif)
                <tr>
                    <td><strong>{{ $notif->empresa->nombre ?? '—' }}</strong></td>
                    <td style="max-width:360px;white-space:pre-wrap;font-size:.83rem;">{{ $notif->mensaje }}</td>
                    <td style="white-space:nowrap;font-size:.83rem;">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.notificaciones.leer', $notif) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-small btn-success">
                                <i class="fa-solid fa-check"></i> Leída
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

{{-- ═══════════════════════════════════════════
     SECCIÓN 6 · ÚLTIMAS RESERVAS
═══════════════════════════════════════════ --}}
<div class="db-section-label">
    <i class="fa-solid fa-clock-rotate-left"></i> Actividad reciente
</div>

<div class="db-panel">
    <div class="db-panel__header">
        <span class="db-panel__title">
            <i class="fa-solid fa-calendar-check" style="color:var(--primary)"></i>
            Últimas reservas
        </span>
        <a href="{{ route('admin.reservas.index') }}" class="db-panel__link">
            Ver todas <i class="fa-solid fa-arrow-right fa-xs"></i>
        </a>
    </div>
    <div class="db-panel__body db-panel__body--flush">
        <div class="table-responsive">
            <table class="db-table db-table--hover">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasReservas as $r)
                    <tr>
                        <td style="color:var(--gray-400);font-size:.8rem;">{{ $r->id }}</td>
                        <td>
                            <span style="font-weight:500;">{{ $r->usuario->name ?? '—' }}</span>
                        </td>
                        <td>{{ $r->hotel->nombre ?? '—' }}</td>
                        <td style="white-space:nowrap;">{{ $r->fecha_entrada->format('d/m/Y') }}</td>
                        <td style="white-space:nowrap;">{{ $r->fecha_salida->format('d/m/Y') }}</td>
                        <td style="text-align:center;">{{ $r->num_personas }}</td>
                        <td style="font-weight:600;">${{ number_format($r->precio_total, 0) }}</td>
                        <td>
                            <span class="db-estado db-estado--{{ $r->estado }}">
                                {{ ucfirst($r->estado) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="db-empty db-empty--sm">
                                <i class="fa-solid fa-inbox"></i>
                                <p>No hay reservas registradas aún</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Paleta compartida ──────────────────────────────────────
const DB_COLORS = ['#2d7a3e','#3b82f6','#f59e0b','#8b5cf6','#06b6d4','#ef4444','#6366f1','#64748b'];

const chartDefaults = {
    font: { family: 'Inter, sans-serif', size: 12 },
    color: '#94a3b8',
};
Chart.defaults.font.family = chartDefaults.font.family;
Chart.defaults.font.size   = chartDefaults.font.size;
Chart.defaults.color       = chartDefaults.color;

// ── Reservas por mes ──────────────────────────────────────
@if($mesData->sum() > 0)
new Chart(document.getElementById('graficaReservasMes'), {
    type: 'bar',
    data: {
        labels: @json($mesLabels),
        datasets: [{
            label: 'Reservas',
            data: @json($mesData),
            backgroundColor: 'rgba(45,122,62,.15)',
            borderColor: '#2d7a3e',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                grid: { color: '#f1f5f9' }
            },
            x: { grid: { display: false } }
        }
    }
});
@endif

// ── Registros por módulo ──────────────────────────────────
new Chart(document.getElementById('chartModulos'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Registros',
            data: @json($chartData),
            backgroundColor: DB_COLORS,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, precision: 0 },
                grid: { color: '#f1f5f9' }
            },
            x: { grid: { display: false } }
        }
    }
});

// ── Reservas por estado ───────────────────────────────────
@if($totalReservas > 0)
new Chart(document.getElementById('chartEstados'), {
    type: 'doughnut',
    data: {
        labels: @json($reservasPorEstado->keys()->map(fn($l) => ucfirst($l))),
        datasets: [{
            data: @json($reservasPorEstado->values()),
            backgroundColor: ['#f59e0b','#10b981','#ef4444'],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 8,
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 18, usePointStyle: true, pointStyleWidth: 10 }
            }
        }
    }
});
@endif
</script>
@endpush
