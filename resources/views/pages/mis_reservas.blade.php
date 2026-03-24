@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>🛒 Mis Reservas</h1>
        <p>Gestiona tus reservas de alojamiento</p>
    </div>
</section>

<section class="container section">
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
    @endif

    <div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;">
        <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-info"><h3>{{ $reservas->count() }}</h3><p>Total reservas</p></div></div>
        <div class="stat-card"><div class="stat-icon">⏳</div><div class="stat-info"><h3>{{ $pendientes->count() }}</h3><p>Pendientes</p></div></div>
        <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-info"><h3>{{ $confirmadas->count() }}</h3><p>Confirmadas</p></div></div>
        <div class="stat-card"><div class="stat-icon">💰</div><div class="stat-info"><h3>${{ number_format($total_gastado, 0, ',', '.') }}</h3><p>Total COP</p></div></div>
    </div>

    @if($reservas->isEmpty())
        <div class="empty-state">
            <p>Aún no tienes reservas.</p>
            <a href="{{ route('hoteles') }}" class="btn btn-primary" style="margin-top:1rem;">Ver Hoteles</a>
        </div>
    @else
        @foreach([['label' => '⏳ Pendientes', 'items' => $pendientes, 'badge' => 'badge-pendiente'],
                  ['label' => '✅ Confirmadas', 'items' => $confirmadas, 'badge' => 'badge-confirmada'],
                  ['label' => '❌ Canceladas',  'items' => $canceladas,  'badge' => 'badge-cancelada']] as $grupo)
            @if($grupo['items']->count())
                <h2 style="margin:2rem 0 1rem;">{{ $grupo['label'] }}</h2>
                @foreach($grupo['items'] as $r)
                    @php $dias = $r->fecha_entrada->diffInDays($r->fecha_salida); @endphp
                    <div class="card" style="display:flex;gap:1.5rem;margin-bottom:1rem;padding:1.5rem;flex-wrap:wrap;">
                        <img src="{{ $r->hotel->imagen }}" alt="{{ $r->hotel->nombre }}"
                             style="width:140px;height:100px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                        <div style="flex:1;min-width:200px;">
                            <h3>{{ $r->hotel->nombre }}</h3>
                            <p class="ubicacion">📍 {{ $r->hotel->ubicacion }}</p>
                            <p>📅 {{ $r->fecha_entrada->format('d/m/Y') }} → {{ $r->fecha_salida->format('d/m/Y') }}
                               &nbsp;·&nbsp; {{ $dias }} noche{{ $dias > 1 ? 's' : '' }}
                               &nbsp;·&nbsp; 👥 {{ $r->num_personas }} persona{{ $r->num_personas > 1 ? 's' : '' }}</p>
                            <p style="margin-top:.5rem;"><span class="badge {{ $grupo['badge'] }}">{{ $r->estado }}</span></p>
                        </div>
                        <div style="text-align:right;min-width:140px;">
                            <p class="precio-destacado" style="font-size:1.4rem;">${{ number_format($r->precio_total, 0, ',', '.') }}</p>
                            <p style="font-size:.8rem;color:var(--gray);">COP total</p>
                            <div style="margin-top:.8rem;display:flex;flex-direction:column;gap:.4rem;">
                                <a href="{{ route('hoteles.detalle', $r->hotel) }}" class="btn btn-secondary btn-small">Ver hotel</a>
                                @if($r->estado === 'pendiente')
                                    <a href="{{ route('mis-reservas', ['cancelar' => $r->id]) }}"
                                       class="btn btn-small btn-delete"
                                       onclick="return confirm('¿Cancelar esta reserva?')">Cancelar</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach

        <div style="text-align:center;margin-top:2rem;">
            <a href="{{ route('hoteles') }}" class="btn btn-primary">➕ Hacer otra reserva</a>
        </div>
    @endif
</section>

@include('partials.footer')
