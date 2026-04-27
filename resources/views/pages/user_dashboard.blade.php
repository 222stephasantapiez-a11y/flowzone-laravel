@extends('layouts.app')
 
@section('title', 'Mi Panel')
@section('body-class', 'no-hero')
 
@push('styles')
<style>
.ud-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, #1a6b50 100%);
    padding: 4.5rem 0 3rem;
    margin-top: var(--navbar-height);
    position: relative;
    overflow: hidden;
}
.ud-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 80% 50%, rgba(255,255,255,.06) 0%, transparent 60%),
                      radial-gradient(circle at 20% 80%, rgba(82,183,136,.15) 0%, transparent 50%);
    pointer-events: none;
}
.ud-hero-inner {
    display: flex; align-items: center; gap: 2rem;
    position: relative; z-index: 1; flex-wrap: wrap;
}
.ud-avatar-ring {
    width: 92px; height: 92px; border-radius: 50%;
    border: 3px solid rgba(255,255,255,.35);
    background: linear-gradient(135deg, var(--green-600), var(--green-800));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display); font-size: 2.3rem; font-weight: 800; color: white;
    flex-shrink: 0; box-shadow: 0 0 0 6px rgba(255,255,255,.08);
}
.ud-hero-text h1 {
    font-family: var(--font-display);
    font-size: clamp(1.6rem,4vw,2.5rem); font-weight: 900; color: #fff; margin-bottom: .2rem;
}
.ud-hero-text p { color: rgba(255,255,255,.65); font-size: 1rem; }
.ud-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    color: var(--green-200); font-size: .82rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    padding: .35rem .95rem; border-radius: var(--radius-full); margin-top: .6rem;
}
.ud-layout {
    display: grid; grid-template-columns: 320px 1fr;
    gap: 2rem; align-items: start; padding: 2.5rem 0 4rem;
}
@media (max-width: 900px) { .ud-layout { grid-template-columns: 1fr; } }
.ud-sidebar {
    position: sticky; top: calc(var(--navbar-height) + 1.5rem);
    display: flex; flex-direction: column; gap: 1.25rem;
}
.ud-panel { background: #fff; border-radius: var(--radius-lg); box-shadow: var(--shadow-card); overflow: hidden; }
.ud-panel-header {
    background: linear-gradient(135deg, var(--green-900), var(--green-700));
    padding: 1.4rem 1.6rem; color: white;
}
.ud-panel-header h3 { font-size: .88rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: .15rem; }
.ud-panel-header p { font-family: var(--font-display); font-size: 1.25rem; font-weight: 800; color: white; }
.ud-stat-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: var(--gray-100); }
.ud-stat-cell { background: #fff; padding: 1.15rem 1.25rem; text-align: center; }
.ud-stat-cell .num { font-family: var(--font-display); font-size: 1.85rem; font-weight: 900; color: var(--green-800); line-height: 1; }
.ud-stat-cell .lbl { font-size: .82rem; color: var(--gray-400); margin-top: .25rem; text-transform: uppercase; letter-spacing: .05em; }
.ud-nav { list-style: none; padding: .5rem; }
.ud-nav li a {
    display: flex; align-items: center; gap: .75rem; padding: .85rem 1.1rem;
    border-radius: var(--radius-md); font-size: 1rem; font-weight: 500;
    color: var(--gray-600); text-decoration: none; transition: var(--transition);
}
.ud-nav li a:hover, .ud-nav li a.active { background: var(--green-50); color: var(--green-800); }
.ud-nav li a i { width: 20px; text-align: center; color: var(--green-600); font-size: .95rem; }
.ud-upcoming {
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    border-radius: var(--radius-lg); padding: 1.4rem 1.6rem; color: white;
}
.ud-upcoming .label { font-size: .8rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: .5rem; }
.ud-upcoming .hotel-name { font-family: var(--font-display); font-size: 1.18rem; font-weight: 800; color: white; margin-bottom: .3rem; }
.ud-upcoming .dates { font-size: .92rem; color: rgba(255,255,255,.75); display: flex; align-items: center; gap: .4rem; }
.ud-upcoming .countdown { margin-top: .75rem; background: rgba(255,255,255,.15); border-radius: var(--radius-sm); padding: .45rem .85rem; font-size: .88rem; font-weight: 600; color: var(--green-200); display: inline-block; }
.ud-tabs { display: flex; gap: .5rem; margin-bottom: 1.75rem; border-bottom: 2px solid var(--gray-100); padding-bottom: .75rem; flex-wrap: wrap; }
.ud-tab-btn { background: none; border: none; padding: .6rem 1.25rem; border-radius: var(--radius-md); font-size: 1rem; font-weight: 600; color: var(--gray-400); cursor: pointer; transition: var(--transition); display: flex; align-items: center; gap: .4rem; }
.ud-tab-btn:hover { background: var(--gray-100); color: var(--gray-900); }
.ud-tab-btn.active { background: var(--green-50); color: var(--green-800); }
.ud-tab-count { background: var(--gray-200); color: var(--gray-600); font-size: .78rem; font-weight: 700; padding: .15rem .55rem; border-radius: var(--radius-full); }
.ud-tab-btn.active .ud-tab-count { background: var(--green-200); color: var(--green-900); }
.ud-tab-pane { display: none; }
.ud-tab-pane.active { display: block; }
.res-card { background: #fff; border-radius: var(--radius-lg); box-shadow: var(--shadow-card); overflow: hidden; display: grid; grid-template-columns: 148px 1fr auto; transition: var(--transition); margin-bottom: 1rem; }
.res-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
@media (max-width: 640px) { .res-card { grid-template-columns: 1fr; } }
.res-img { width: 100%; height: 100%; min-height: 125px; object-fit: cover; }
.res-img-placeholder { width: 100%; min-height: 125px; background: linear-gradient(135deg, var(--green-900), var(--green-600)); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.3); font-size: 2rem; }
.res-body { padding: 1.25rem 1.4rem; display: flex; flex-direction: column; justify-content: center; gap: .35rem; }
.res-hotel { font-size: 1.12rem; font-weight: 700; color: var(--gray-900); }
.res-location { font-size: .92rem; color: var(--gray-400); display: flex; align-items: center; gap: .3rem; }
.res-dates { font-size: .94rem; color: var(--gray-600); display: flex; align-items: center; gap: .3rem; flex-wrap: wrap; }
.res-dates span { color: var(--gray-400); }
.res-aside { padding: 1.25rem 1.4rem; display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; gap: .75rem; min-width: 155px; border-left: 1px solid var(--gray-100); }
@media (max-width: 640px) { .res-aside { border-left: none; border-top: 1px solid var(--gray-100); flex-direction: row; align-items: center; } }
.res-price { font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; color: var(--green-800); line-height: 1; }
.res-price-label { font-size: .82rem; color: var(--gray-400); margin-top: .15rem; }
.res-actions { display: flex; flex-direction: column; gap: .4rem; align-items: flex-end; }
.badge { display: inline-flex; align-items: center; gap: .35rem; font-size: .8rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; padding: .3rem .8rem; border-radius: var(--radius-full); }
.estado-pendiente  { background: #fef3c7; color: #92400e; }
.estado-confirmada { background: #d1fae5; color: #065f46; }
.estado-cancelada  { background: #fee2e2; color: #991b1b; }
.ud-empty { text-align: center; padding: 3.5rem 2rem; background: #fff; border-radius: var(--radius-lg); box-shadow: var(--shadow-card); }
.ud-empty i { font-size: 3.2rem; color: var(--gray-200); margin-bottom: 1rem; display: block; }
.ud-empty h3 { color: var(--gray-400); font-weight: 600; margin-bottom: .4rem; font-size: 1.15rem; }
.ud-empty p  { color: var(--gray-400); font-size: 1rem; margin-bottom: 1.25rem; }
.ud-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
@media (max-width: 600px) { .ud-form-grid { grid-template-columns: 1fr; } }
.ud-field label { display: block; font-size: .88rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; letter-spacing: .06em; margin-bottom: .5rem; }
.ud-field input { width: 100%; padding: .8rem 1.1rem; border: 1.5px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-body); font-size: 1rem; color: var(--gray-900); background: var(--gray-50); transition: border-color .2s ease, box-shadow .2s ease; outline: none; }
.ud-field input:focus { border-color: var(--green-600); background: #fff; box-shadow: 0 0 0 3px rgba(82,183,136,.15); }
.ud-field input[readonly] { background: var(--gray-100); cursor: default; color: var(--gray-400); }
.ud-divider { border: none; border-top: 1.5px solid var(--gray-100); margin: 1.75rem 0; }
.ud-section-title { font-size: .88rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--gray-400); margin-bottom: 1.1rem; }
</style>
@endpush
 
@section('content')
 
<section class="ud-hero">
    <div class="container">
        <div class="ud-hero-inner">
            <div class="ud-avatar-ring">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="ud-hero-text">
                <h1>Hola, {{ explode(' ', $user->name)[0] }} 👋</h1>
                <p>{{ $user->email }}</p>
                <span class="ud-badge">
                    <i class="fa-solid fa-circle-user fa-xs"></i>
                    {{ ucfirst($user->rol ?? 'usuario') }}
                </span>
            </div>
        </div>
    </div>
</section>
 
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" style="margin-top:1.5rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-top:1.5rem;">{{ session('error') }}</div>
    @endif
 
    <div class="ud-layout">
        <aside class="ud-sidebar">
 
            <div class="ud-panel">
                <div class="ud-panel-header">
                    <h3>Resumen</h3><p>Tu actividad</p>
                </div>
                <div class="ud-stat-row">
                    <div class="ud-stat-cell"><div class="num">{{ $reservas->count() }}</div><div class="lbl">Total</div></div>
                    <div class="ud-stat-cell"><div class="num">{{ $pendientes->count() }}</div><div class="lbl">Pendientes</div></div>
                    <div class="ud-stat-cell"><div class="num">{{ $confirmadas->count() }}</div><div class="lbl">Confirmadas</div></div>
                    <div class="ud-stat-cell"><div class="num" style="font-size:1.25rem;">${{ number_format($total_gastado/1000,0) }}K</div><div class="lbl">COP gastado</div></div>
                </div>
            </div>
 
            @if($proxima)
            @php $diasParaLlegada = now()->diffInDays($proxima->fecha_entrada, false); @endphp
            <div class="ud-upcoming">
                <div class="label"><i class="fa-solid fa-calendar-star fa-xs"></i> Próxima estancia</div>
                <div class="hotel-name">{{ $proxima->hotel->nombre }}</div>
                <div class="dates"><i class="fa-solid fa-calendar fa-xs"></i> {{ $proxima->fecha_entrada->format('d M') }} → {{ $proxima->fecha_salida->format('d M Y') }}</div>
                <div class="countdown">
                    @if($diasParaLlegada > 0) <i class="fa-solid fa-hourglass-half fa-xs"></i> Faltan {{ $diasParaLlegada }} días
                    @elseif($diasParaLlegada === 0) <i class="fa-solid fa-party-horn fa-xs"></i> ¡Hoy es tu llegada!
                    @else <i class="fa-solid fa-check fa-xs"></i> En curso
                    @endif
                </div>
            </div>
            @endif
 
            <div class="ud-panel">
                <ul class="ud-nav">
                    <li><a href="#" class="ud-nav-link active" data-tab="reservas"><i class="fa-solid fa-calendar-check"></i> Mis Reservas</a></li>
                    <li><a href="#" class="ud-nav-link" data-tab="perfil"><i class="fa-solid fa-user-pen"></i> Editar Perfil</a></li>
                    <li><a href="{{ route('hoteles') }}"><i class="fa-solid fa-hotel"></i> Explorar Hoteles</a></li>
                    <li><a href="{{ route('favoritos') }}"><i class="fa-solid fa-heart"></i> Mis Favoritos</a></li>
                    <li><a href="{{ route('hoteles') }}"><i class="fa-solid fa-plus"></i> Nueva Reserva</a></li>
                </ul>
            </div>
 
        </aside>
 
        <main>
            {{-- TAB RESERVAS --}}
            <div class="ud-tab-pane active" id="tab-reservas">
                <div class="ud-tabs">
                    <button class="ud-tab-btn active" data-filter="todas"><i class="fa-solid fa-list fa-xs"></i> Todas <span class="ud-tab-count">{{ $reservas->count() }}</span></button>
                    <button class="ud-tab-btn" data-filter="pendiente"><i class="fa-solid fa-clock fa-xs"></i> Pendientes <span class="ud-tab-count">{{ $pendientes->count() }}</span></button>
                    <button class="ud-tab-btn" data-filter="confirmada"><i class="fa-solid fa-circle-check fa-xs"></i> Confirmadas <span class="ud-tab-count">{{ $confirmadas->count() }}</span></button>
                    <button class="ud-tab-btn" data-filter="cancelada"><i class="fa-solid fa-circle-xmark fa-xs"></i> Canceladas <span class="ud-tab-count">{{ $canceladas->count() }}</span></button>
                </div>
                <div id="reservas-list">
                    @if($reservas->isEmpty())
                        <div class="ud-empty">
                            <i class="fa-solid fa-calendar-xmark"></i>
                            <h3>Aún no tienes reservas</h3>
                            <p>Explora nuestros hoteles y haz tu primera reserva</p>
                            <a href="{{ route('hoteles') }}" class="btn btn-primary"><i class="fa-solid fa-hotel fa-xs"></i> Ver Hoteles</a>
                        </div>
                    @else
                        @foreach($reservas as $r)
                        @php
                            $dias = $r->fecha_entrada->diffInDays($r->fecha_salida);
                            $hotelImg = $r->hotel->imagen
                                ? (str_starts_with($r->hotel->imagen, 'http') ? $r->hotel->imagen : \Storage::disk('public')->url($r->hotel->imagen))
                                : null;
                        @endphp
                        <div class="res-card" data-estado="{{ $r->estado }}">
                            @if($hotelImg)
                                <img src="{{ $hotelImg }}" alt="{{ $r->hotel->nombre }}" class="res-img">
                            @else
                                <div class="res-img-placeholder"><i class="fa-solid fa-hotel"></i></div>
                            @endif
                            <div class="res-body">
                                <div class="res-hotel">{{ $r->hotel->nombre }}</div>
                                <div class="res-location"><i class="fa-solid fa-location-dot fa-xs"></i> {{ $r->hotel->ubicacion }}</div>
                                <div class="res-dates">
                                    <i class="fa-solid fa-calendar fa-xs" style="color:var(--green-600);"></i>
                                    {{ $r->fecha_entrada->format('d/m/Y') }} <span>→</span> {{ $r->fecha_salida->format('d/m/Y') }}
                                    &nbsp;·&nbsp; {{ $dias }} noche{{ $dias !== 1 ? 's' : '' }}
                                    &nbsp;·&nbsp; {{ $r->num_personas }} persona{{ $r->num_personas !== 1 ? 's' : '' }}
                                </div>
                                <div style="margin-top:.4rem;">
                                    <span class="badge estado-{{ $r->estado }}">
                                        @if($r->estado==='pendiente') <i class="fa-solid fa-clock fa-xs"></i>
                                        @elseif($r->estado==='confirmada') <i class="fa-solid fa-circle-check fa-xs"></i>
                                        @else <i class="fa-solid fa-circle-xmark fa-xs"></i>
                                        @endif
                                        {{ ucfirst($r->estado) }}
                                    </span>
                                    <span style="font-size:.85rem;color:var(--gray-400);margin-left:.75rem;">#{{ $r->id }} · {{ $r->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="res-aside">
                                <div>
                                    <div class="res-price">${{ number_format($r->precio_total, 0, ',', '.') }}</div>
                                    <div class="res-price-label">COP total</div>
                                </div>
                                <div class="res-actions">
                                    <a href="{{ route('hoteles.detalle', $r->hotel) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-eye fa-xs"></i> Ver hotel</a>
                                    @if($r->estado === 'pendiente')
                                        <form method="POST" action="{{ route('dashboard.cancelar', $r->id) }}" onsubmit="return confirm('¿Cancelar esta reserva?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" style="width:100%;"><i class="fa-solid fa-xmark fa-xs"></i> Cancelar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div style="text-align:center;margin-top:1.5rem;">
                            <a href="{{ route('hoteles') }}" class="btn btn-primary"><i class="fa-solid fa-plus fa-xs"></i> Nueva Reserva</a>
                        </div>
                    @endif
                </div>
            </div>
 
            {{-- TAB PERFIL --}}
            <div class="ud-tab-pane" id="tab-perfil">
                <div class="ud-panel" style="padding:2rem;">
                    <p class="ud-section-title"><i class="fa-solid fa-user fa-xs"></i> Información personal</p>
                    <form method="POST" action="{{ route('dashboard.profile') }}">
                        @csrf
                        @method('PUT')
                        <div class="ud-form-grid">
                            <div class="ud-field">
                                <label>Nombre completo</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                            </div>
                            <div class="ud-field">
                                <label>Correo electrónico</label>
                                <input type="email" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="ud-field">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" placeholder="+57 300 000 0000">
                            </div>
                            <div class="ud-field">
                                <label>Rol</label>
                                <input type="text" value="{{ ucfirst($user->rol ?? 'usuario') }}" readonly>
                            </div>
                        </div>
                        <hr class="ud-divider">
                        <p class="ud-section-title"><i class="fa-solid fa-lock fa-xs"></i> Cambiar contraseña <span style="font-size:.8rem;color:var(--gray-400);font-weight:400;text-transform:none;letter-spacing:0;">(dejar en blanco para no cambiar)</span></p>
                        <div class="ud-form-grid">
                            <div class="ud-field">
                                <label>Nueva contraseña</label>
                                <input type="password" name="password" placeholder="Mín. 8 caracteres" autocomplete="new-password">
                                @error('password') <small style="color:var(--danger);">{{ $message }}</small> @enderror
                            </div>
                            <div class="ud-field">
                                <label>Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" placeholder="Repite la contraseña">
                            </div>
                        </div>
                        <hr class="ud-divider">
                        <div style="display:flex;justify-content:flex-end;gap:.75rem;">
                            <a href="#" class="btn btn-outline ud-nav-link" data-tab="reservas">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
 
@push('scripts')
<script>
(function () {
    const navLinks = document.querySelectorAll('.ud-nav-link[data-tab]');
    const panes = { reservas: document.getElementById('tab-reservas'), perfil: document.getElementById('tab-perfil') };
 
    function switchTab(tab) {
        Object.values(panes).forEach(p => p && p.classList.remove('active'));
        navLinks.forEach(l => l.classList.remove('active'));
        if (panes[tab]) panes[tab].classList.add('active');
        navLinks.forEach(l => { if (l.dataset.tab === tab) l.classList.add('active'); });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
 
    navLinks.forEach(link => {
        link.addEventListener('click', e => { e.preventDefault(); switchTab(link.dataset.tab); });
    });
 
    const filterBtns = document.querySelectorAll('.ud-tab-btn');
    const cards = document.querySelectorAll('.res-card');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            cards.forEach(card => {
                card.style.display = (filter === 'todas' || card.dataset.estado === filter) ? '' : 'none';
            });
        });
    });
 
    @if($errors->has('name') || $errors->has('password') || $errors->has('telefono'))
        switchTab('perfil');
    @endif
})();
</script>
@endpush