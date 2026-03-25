@extends('layouts.app')

@section('title', 'Mis Reservas')
@section('body-class', 'no-hero')

@section('content')

{{-- Page hero mini --}}
<section style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);padding:5rem 0 3rem;margin-top:var(--navbar-height);">
    <div class="container" style="text-align:center;">
        <span style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:var(--green-200);font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.4rem 1rem;border-radius:var(--radius-full);margin-bottom:1rem;">
            <i class="fa-solid fa-list-check fa-xs"></i> Mi cuenta
        </span>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.6rem,4vw,2.5rem);font-weight:900;color:#fff;margin-bottom:.5rem;">
            Mis Reservas
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.95rem;">Gestiona tus reservas de alojamiento</p>
    </div>
</section>

<section class="container section">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1.25rem;margin-bottom:2.5rem;">
        <div class="stat-card green">
            <div class="stat-icon-wrap"><i class="fa-solid fa-list-check"></i></div>
            <div class="stat-info"><h3>{{ $reservas->count() }}</h3><p>Total reservas</p></div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon-wrap" style="color:var(--warning);"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-info"><h3>{{ $pendientes->count() }}</h3><p>Pendientes</p></div>
        </div>
        <div class="stat-card" style="border-left-color:var(--success);">
            <div class="stat-icon-wrap" style="color:var(--success);"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-info"><h3>{{ $confirmadas->count() }}</h3><p>Confirmadas</p></div>
        </div>
        <div class="stat-card" style="border-left-color:var(--gold-500);">
            <div class="stat-icon-wrap" style="color:var(--gold-500);"><i class="fa-solid fa-coins"></i></div>
            <div class="stat-info"><h3>${{ number_format($total_gastado, 0, ',', '.') }}</h3><p>Total COP</p></div>
        </div>
    </div>

    @if($reservas->isEmpty())
        <div class="admin-section" style="text-align:center;padding:4rem 2rem;">
            <i class="fa-solid fa-calendar-xmark" style="font-size:3rem;color:var(--gray-200);margin-bottom:1rem;display:block;"></i>
            <h3 style="color:var(--gray-400);font-weight:600;margin-bottom:.5rem;">Aun no tienes reservas</h3>
            <p style="color:var(--gray-400);font-size:.9rem;margin-bottom:1.5rem;">Explora nuestros hoteles y haz tu primera reserva</p>
            <a href="{{ route('hoteles') }}" class="btn btn-primary">
                <i class="fa-solid fa-hotel fa-xs"></i> Ver Hoteles
            </a>
        </div>
    @else
        @foreach([
            ['label' => 'Pendientes',  'items' => $pendientes,  'badge' => 'estado-pendiente',  'icon' => 'clock'],
            ['label' => 'Confirmadas', 'items' => $confirmadas, 'badge' => 'estado-confirmada', 'icon' => 'circle-check'],
            ['label' => 'Canceladas',  'items' => $canceladas,  'badge' => 'estado-cancelada',  'icon' => 'circle-xmark'],
        ] as $grupo)
            @if($grupo['items']->count())
                <div style="margin-bottom:2.5rem;">
                    <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.6rem;">
                        <i class="fa-solid fa-{{ $grupo['icon'] }}" style="color:var(--green-600);"></i>
                        {{ $grupo['label'] }}
                        <span style="background:var(--gray-100);color:var(--gray-600);font-size:.75rem;padding:.2rem .6rem;border-radius:var(--radius-full);">{{ $grupo['items']->count() }}</span>
                    </h2>
                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        @foreach($grupo['items'] as $r)
                        @php
                            $dias = $r->fecha_entrada->diffInDays($r->fecha_salida);
                            $hotelImg = $r->hotel->imagen
                                ? (str_starts_with($r->hotel->imagen, 'http') ? $r->hotel->imagen : Storage::disk('public')->url($r->hotel->imagen))
                                : null;
                        @endphp
                        <div class="admin-section" style="display:flex;gap:1.5rem;padding:1.25rem;flex-wrap:wrap;align-items:center;">
                            @if($hotelImg)
                                <img src="{{ $hotelImg }}" alt="{{ $r->hotel->nombre }}"
                                     style="width:120px;height:88px;object-fit:cover;border-radius:var(--radius-md);flex-shrink:0;">
                            @else
                                <div style="width:120px;height:88px;background:linear-gradient(135deg,var(--green-800),var(--green-600));border-radius:var(--radius-md);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-hotel" style="color:rgba(255,255,255,.4);font-size:1.5rem;"></i>
                                </div>
                            @endif
                            <div style="flex:1;min-width:200px;">
                                <h3 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:.3rem;">{{ $r->hotel->nombre }}</h3>
                                <p style="font-size:.85rem;color:var(--gray-400);margin-bottom:.4rem;">
                                    <i class="fa-solid fa-location-dot fa-xs" style="margin-right:.3rem;"></i>{{ $r->hotel->ubicacion }}
                                </p>
                                <p style="font-size:.85rem;color:var(--gray-600);">
                                    <i class="fa-solid fa-calendar fa-xs" style="margin-right:.3rem;color:var(--green-600);"></i>
                                    {{ $r->fecha_entrada->format('d/m/Y') }} - {{ $r->fecha_salida->format('d/m/Y') }}
                                    &nbsp;·&nbsp; {{ $dias }} noche{{ $dias > 1 ? 's' : '' }}
                                    &nbsp;·&nbsp; {{ $r->num_personas }} persona{{ $r->num_personas > 1 ? 's' : '' }}
                                </p>
                                <div style="margin-top:.6rem;">
                                    <span class="badge {{ $grupo['badge'] }}">{{ ucfirst($r->estado) }}</span>
                                </div>
                            </div>
                            <div style="text-align:right;min-width:130px;flex-shrink:0;">
                                <p style="font-family:var(--font-display);font-size:1.4rem;font-weight:800;color:var(--green-800);line-height:1;">
                                    ${{ number_format($r->precio_total, 0, ',', '.') }}
                                </p>
                                <p style="font-size:.75rem;color:var(--gray-400);margin-bottom:.75rem;">COP total</p>
                                <div style="display:flex;flex-direction:column;gap:.4rem;">
                                    <a href="{{ route('hoteles.detalle', $r->hotel) }}" class="btn btn-outline btn-sm">Ver hotel</a>
                                    @if($r->estado === 'pendiente')
                                        <a href="{{ route('mis-reservas', ['cancelar' => $r->id]) }}"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Cancelar esta reserva?')">Cancelar</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div style="text-align:center;margin-top:1rem;">
            <a href="{{ route('hoteles') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus fa-xs"></i> Hacer otra reserva
            </a>
        </div>
    @endif

</section>

@endsection
