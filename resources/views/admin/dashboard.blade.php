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

{{-- Stat cards --}}
<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-icon-wrap"><i class="fa-solid fa-hotel"></i></div>
        <div class="stat-info">
            <h3>{{ $totalHoteles }}</h3>
            <p>Hoteles</p>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon-wrap"><i class="fa-solid fa-map-location-dot"></i></div>
        <div class="stat-info">
            <h3>{{ $totalLugares }}</h3>
            <p>Lugares</p>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon-wrap"><i class="fa-solid fa-calendar-days"></i></div>
        <div class="stat-info">
            <h3>{{ $totalEventos }}</h3>
            <p>Eventos</p>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon-wrap"><i class="fa-solid fa-clipboard-list"></i></div>
        <div class="stat-info">
            <h3>{{ $totalReservas }}</h3>
            <p>Reservas</p>
            @if($reservasPend > 0)
                <span class="stat-sub warn">{{ $reservasPend }} pendientes</span>
            @endif
        </div>
    </div>
    <div class="stat-card teal">
        <div class="stat-icon-wrap"><i class="fa-solid fa-building"></i></div>
        <div class="stat-info">
            <h3>{{ $totalEmpresas }}</h3>
            <p>Empresas</p>
            @if($empresasPend > 0)
                <span class="stat-sub warn">{{ $empresasPend }} por aprobar</span>
            @else
                <span class="stat-sub ok">Todas aprobadas</span>
            @endif
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon-wrap"><i class="fa-solid fa-utensils"></i></div>
        <div class="stat-info">
            <h3>{{ \App\Models\Gastronomia::count() }}</h3>
            <p>Gastronomía</p>
        </div>
    </div>
</div>

{{-- Estadísticas de interacción --}}
<div class="stats-grid" style="margin-bottom:1.5rem;">
    <div class="stat-card red">
        <div class="stat-icon-wrap"><i class="fa-solid fa-heart"></i></div>
        <div class="stat-info">
            <h3>{{ $totalFavoritos }}</h3>
            <p>Favoritos</p>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon-wrap"><i class="fa-solid fa-star"></i></div>
        <div class="stat-info">
            <h3>{{ $totalCalificaciones }}</h3>
            <p>Calificaciones</p>
            @if($promedioGeneral > 0)
                <span class="stat-sub ok">Promedio: {{ $promedioGeneral }}/5</span>
            @endif
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon-wrap"><i class="fa-solid fa-comments"></i></div>
        <div class="stat-info">
            <h3>{{ $totalComentarios }}</h3>
            <p>Comentarios</p>
        </div>
    </div>
</div>

{{-- Top calificados --}}
@if($topHoteles->isNotEmpty() || $topLugares->isNotEmpty())
<div class="charts-grid" style="margin-bottom:1.5rem;">
    @if($topHoteles->isNotEmpty())
    <div class="dash-card">
        <div class="dash-card-header">
            <h2><i class="fa-solid fa-hotel" style="color:var(--primary);margin-right:6px"></i>Hoteles mejor calificados</h2>
        </div>
        <table class="admin-table">
            <thead><tr><th>Hotel</th><th>Promedio</th><th>Reseñas</th></tr></thead>
            <tbody>
                @foreach($topHoteles as $h)
                <tr>
                    <td>{{ $h->nombre }}</td>
                    <td>
                        <span style="color:var(--gold-500);font-weight:700;">
                            <i class="fa-solid fa-star fa-xs"></i> {{ $h->promedio }}
                        </span>
                    </td>
                    <td>{{ $h->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @if($topLugares->isNotEmpty())
    <div class="dash-card">
        <div class="dash-card-header">
            <h2><i class="fa-solid fa-map-pin" style="color:var(--accent);margin-right:6px"></i>Lugares mejor calificados</h2>
        </div>
        <table class="admin-table">
            <thead><tr><th>Lugar</th><th>Promedio</th><th>Reseñas</th></tr></thead>
            <tbody>
                @foreach($topLugares as $l)
                <tr>
                    <td>{{ $l->nombre }}</td>
                    <td>
                        <span style="color:var(--gold-500);font-weight:700;">
                            <i class="fa-solid fa-star fa-xs"></i> {{ $l->promedio }}
                        </span>
                    </td>
                    <td>{{ $l->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif

<div class="card mt-4">
    <div class="card-header">
        <h3>Reservas por Mes</h3>
    </div>
    <div class="card-body">
        <canvas id="graficaReservasMes"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxMes = document.getElementById('graficaReservasMes');

    new Chart(ctxMes, {
        type: 'bar',
        data: {
            labels: [
                @foreach($mesLabels as $mes)
                    'Mes {{ $mes }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total de Reservas',
                data: [
                    @foreach($mesData as $total)
                        {{ $total }},
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>


{{-- Charts --}}
<div class="charts-grid">    <div class="dash-card">
        <div class="dash-card-header">
            <h2><i class="fa-solid fa-chart-bar" style="color:var(--primary);margin-right:6px"></i>Registros por módulo</h2>
        </div>
        <canvas id="chartModulos" height="110"></canvas>
    </div>
    <div class="dash-card">
        <div class="dash-card-header">
            <h2><i class="fa-solid fa-chart-pie" style="color:var(--accent);margin-right:6px"></i>Reservas por estado</h2>
        </div>
        @if($totalReservas > 0)
            <canvas id="chartEstados" height="110"></canvas>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-inbox"></i>
                Sin reservas aún
            </div>
        @endif
    </div>
</div>

{{-- Notificaciones empresas --}}
@if($notifCount > 0)
<div class="dash-card" style="border-left:4px solid var(--warning);margin-bottom:1.5rem;">
    <div class="dash-card-header">
        <h2>
            <i class="fa-solid fa-bell" style="color:var(--warning);margin-right:6px"></i>
            Solicitudes de empresas
            <span class="badge badge-warning" style="margin-left:.5rem;">{{ $notifCount }}</span>
        </h2>
        <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:var(--primary);cursor:pointer;font-size:.82rem;text-decoration:underline;font-family:inherit;">
                Marcar todas como leídas
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
                <tr>
                    <td><strong>{{ $notif->empresa->nombre ?? '—' }}</strong></td>
                    <td style="max-width:400px;white-space:pre-wrap;">{{ $notif->mensaje }}</td>
                    <td style="white-space:nowrap;">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
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

{{-- Últimas reservas --}}
<div class="dash-card">
    <div class="dash-card-header">
        <h2><i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);margin-right:6px"></i>Últimas reservas</h2>
        <a href="{{ route('admin.reservas.index') }}">Ver todas <i class="fa-solid fa-arrow-right fa-xs"></i></a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th><th>Usuario</th><th>Hotel</th>
                    <th>Entrada</th><th>Salida</th><th>Personas</th><th>Total</th><th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasReservas as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->usuario->name ?? '—' }}</td>
                        <td>{{ $r->hotel->nombre ?? '—' }}</td>
                        <td>{{ $r->fecha_entrada->format('d/m/Y') }}</td>
                        <td>{{ $r->fecha_salida->format('d/m/Y') }}</td>
                        <td>{{ $r->num_personas }}</td>
                        <td>${{ number_format($r->precio_total, 0) }}</td>
                        <td><span class="estado-badge estado-{{ $r->estado }}">{{ ucfirst($r->estado) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--gray);padding:2rem;">
                            <i class="fa-solid fa-inbox"></i> No hay reservas registradas aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartModulos'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Registros',
            data: @json($chartData),
            backgroundColor: ['#2d7a3e','#3b82f6','#f39c12','#8b5cf6','#06b6d4','#ef4444'],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, color: '#95a5a6' }, grid: { color: '#f0f0f0' } },
            x: { ticks: { color: '#95a5a6' }, grid: { display: false } }
        }
    }
});

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
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } } }
    }
});
@endif
</script>
@endpush
