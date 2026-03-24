<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="admin-layout">

    {{-- ── Sidebar ── --}}
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <h2>🌄 FlowZone</h2>
            <span>Panel de Administración</span>
        </div>

        <nav class="admin-nav">
            <div class="nav-section-label">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="active">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section-label">Gestión</div>
            <a href="{{ route('admin.hoteles.index') }}">
                <i class="fa-solid fa-hotel"></i> Hoteles
            </a>
            <a href="{{ route('admin.lugares.index') }}">
                <i class="fa-solid fa-map-location-dot"></i> Lugares
            </a>
            <a href="{{ route('admin.eventos.index') }}">
                <i class="fa-solid fa-calendar-days"></i> Eventos
            </a>
            <a href="{{ route('admin.reservas.index') }}">
                <i class="fa-solid fa-clipboard-list"></i> Reservas
                @if($reservasPend > 0)
                    <span class="admin-notif-badge">{{ $reservasPend }}</span>
                @endif
            </a>
            <a href="{{ route('admin.empresas.index') }}">
                <i class="fa-solid fa-building"></i> Empresas
                @if($empresasPend > 0 || $notifCount > 0)
                    <span class="admin-notif-badge">{{ $empresasPend + $notifCount }}</span>
                @endif
            </a>

            <div class="nav-section-label" style="margin-top:auto;padding-top:1.5rem;">Sesión</div>
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-globe"></i> Ver sitio
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>

    {{-- ── Contenido principal ── --}}
    <main class="admin-main">

        {{-- Topbar --}}
        <div class="admin-topbar">
            <div class="topbar-title">
                <h1>Dashboard</h1>
                <p>Resumen general del sistema · {{ now()->format('d/m/Y') }}</p>
            </div>
            <div class="topbar-actions">
                @if($empresasPend > 0)
                    <a href="{{ route('admin.empresas.index') }}" class="topbar-badge">
                        <i class="fa-solid fa-bell"></i>
                        {{ $empresasPend }} empresa(s) pendiente(s)
                    </a>
                @endif
            </div>
        </div>

        <div class="admin-main-inner">

            {{-- ── Stat cards ── --}}
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
                    <div class="stat-icon-wrap"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-info">
                        <h3>{{ $totalUsuarios }}</h3>
                        <p>Usuarios</p>
                    </div>
                </div>
            </div>

            {{-- ── Gráficas ── --}}
            <div class="charts-grid">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2><i class="fa-solid fa-chart-bar" style="color:var(--primary);margin-right:6px"></i>Registros por módulo</h2>
                    </div>
                    <canvas id="chartModulos" height="110"></canvas>
                </div>

                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2><i class="fa-solid fa-chart-donut" style="color:var(--accent);margin-right:6px"></i>Reservas por estado</h2>
                    </div>
                    @if($totalReservas > 0)
                        <canvas id="chartEstados" height="110"></canvas>
                    @else
                        <div style="text-align:center;padding:3rem;color:var(--gray)">
                            <i class="fa-solid fa-inbox" style="font-size:2rem;margin-bottom:0.5rem;display:block"></i>
                            Sin reservas aún
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Notificaciones de empresas ── --}}
            @if($notifCount > 0)
            <div class="dash-card" style="border-left:4px solid #f59e0b;">
                <div class="dash-card-header">
                    <h2><i class="fa-solid fa-bell" style="color:#f59e0b;margin-right:6px"></i>
                        Solicitudes de empresas
                        <span style="background:#f59e0b;color:#fff;border-radius:20px;padding:2px 10px;font-size:.8rem;margin-left:8px;">{{ $notifCount }}</span>
                    </h2>
                    <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}" style="display:inline">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:var(--primary);cursor:pointer;font-size:.85rem;text-decoration:underline;">
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
                                        <button type="submit" class="btn-small btn-success">✓ Leída</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- ── Últimas reservas ── --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h2><i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);margin-right:6px"></i>Últimas reservas</h2>
                    <a href="{{ route('admin.reservas.index') }}">Ver todas <i class="fa-solid fa-arrow-right"></i></a>
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
                                    <td>
                                        <span class="estado-badge estado-{{ $r->estado }}">
                                            {{ ucfirst($r->estado) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center;color:var(--gray);padding:2rem">
                                        <i class="fa-solid fa-inbox"></i> No hay reservas registradas aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- /admin-main-inner --}}
    </main>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>

    const modulos = {
        labels: @json($chartLabels ),
        data:   @json($chartData ),
        colors: ['#2d7a3e','#3b82f6','#f39c12','#8b5cf6','#06b6d4','#ef4444']
    };

    // Gráfica de barras — registros por módulo
    new Chart(document.getElementById('chartModulos'), {
        type: 'bar',
        data: {
            labels: modulos.labels,
            datasets: [{
                label: 'Registros',
                data: modulos.data,
                backgroundColor: modulos.colors,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} registros`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#95a5a6' },
                    grid: { color: '#f0f0f0' }
                },
                x: {
                    ticks: { color: '#95a5a6' },
                    grid: { display: false }
                }
            }
        }
    });

    // Gráfica de dona — reservas por estado (solo si hay reservas)
    @if($totalReservas > 0)
    new Chart(document.getElementById('chartEstados'), {
        type: 'doughnut',
        data: {
            labels: @json($reservasPorEstado->keys()->map(fn($l) => ucfirst($l))),
            datasets: [{
                data: @json($reservasPorEstado->values()),
                backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, font: { size: 12 } }
                }
            }
        }
    });
    @endif
</script>
</body>
</html>
