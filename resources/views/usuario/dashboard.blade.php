@extends('layouts.app')

@section('title', 'Mi Cuenta')
@section('body-class', 'no-hero')

@push('styles')
<style>
/* ── Variables locales ── */
.uc-hero {
    background: linear-gradient(135deg, var(--green-900) 0%, var(--green-700) 60%, #1a6b50 100%);
    padding: 3.5rem 0 2.5rem;
    margin-top: var(--navbar-height);
    position: relative; overflow: hidden;
}
.uc-hero::before {
    content:''; position:absolute; inset:0; pointer-events:none;
    background: radial-gradient(circle at 80% 50%, rgba(255,255,255,.06) 0%, transparent 60%),
                radial-gradient(circle at 20% 80%, rgba(82,183,136,.15) 0%, transparent 50%);
}
.uc-hero-inner { display:flex; align-items:center; gap:1.75rem; position:relative; z-index:1; flex-wrap:wrap; }
.uc-avatar {
    width:88px; height:88px; border-radius:50%; flex-shrink:0;
    border:3px solid rgba(255,255,255,.35); object-fit:cover;
    box-shadow:0 0 0 6px rgba(255,255,255,.08);
}
.uc-avatar-init {
    width:88px; height:88px; border-radius:50%; flex-shrink:0;
    border:3px solid rgba(255,255,255,.35);
    background:linear-gradient(135deg,var(--green-600),var(--green-800));
    display:flex; align-items:center; justify-content:center;
    font-size:2.2rem; font-weight:900; color:#fff;
    box-shadow:0 0 0 6px rgba(255,255,255,.08);
}
.uc-hero-info h1 { font-family:var(--font-display); font-size:clamp(1.5rem,4vw,2.2rem); font-weight:900; color:#fff; margin-bottom:.2rem; }
.uc-hero-info p  { color:rgba(255,255,255,.65); font-size:.95rem; }
.uc-hero-meta { display:flex; gap:.6rem; flex-wrap:wrap; margin-top:.6rem; }
.uc-badge {
    display:inline-flex; align-items:center; gap:.35rem;
    background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2);
    color:var(--green-200); font-size:.78rem; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase;
    padding:.3rem .85rem; border-radius:var(--radius-full);
}
/* ── Layout ── */
.uc-wrap { padding:2rem 0 4rem; }
/* ── Tabs ── */
.uc-tabs {
    display:flex; gap:.25rem; border-bottom:2px solid var(--gray-100);
    margin-bottom:2rem; overflow-x:auto; padding-bottom:0;
}
.uc-tab {
    display:inline-flex; align-items:center; gap:.45rem;
    padding:.7rem 1.25rem; font-size:.92rem; font-weight:600;
    color:var(--gray-400); background:none; border:none; cursor:pointer;
    border-bottom:2px solid transparent; margin-bottom:-2px;
    white-space:nowrap; transition:color .15s, border-color .15s;
}
.uc-tab:hover { color:var(--gray-700); }
.uc-tab.active { color:var(--green-700); border-bottom-color:var(--green-700); }
.uc-tab-count {
    background:var(--gray-100); color:var(--gray-500);
    font-size:.72rem; font-weight:700; padding:.1rem .5rem;
    border-radius:var(--radius-full);
}
.uc-tab.active .uc-tab-count { background:var(--green-100); color:var(--green-800); }
.uc-pane { display:none; }
.uc-pane.active { display:block; }
/* ── Cards reserva ── */
.res-card {
    background:#fff; border-radius:var(--radius-lg); box-shadow:var(--shadow-card);
    overflow:hidden; display:grid; grid-template-columns:140px 1fr auto;
    margin-bottom:1rem; transition:box-shadow .2s, transform .2s;
}
.res-card:hover { box-shadow:var(--shadow-lg); transform:translateY(-2px); }
@media(max-width:640px){ .res-card { grid-template-columns:1fr; } }
.res-img { width:100%; height:100%; min-height:120px; object-fit:cover; }
.res-img-ph { width:100%; min-height:120px; background:linear-gradient(135deg,var(--green-900),var(--green-600)); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.3); font-size:2rem; }
.res-body { padding:1.1rem 1.3rem; display:flex; flex-direction:column; justify-content:center; gap:.3rem; }
.res-hotel { font-size:1.05rem; font-weight:700; color:var(--gray-900); }
.res-meta  { font-size:.85rem; color:var(--gray-500); display:flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
.res-aside { padding:1.1rem 1.3rem; display:flex; flex-direction:column; align-items:flex-end; justify-content:space-between; gap:.6rem; min-width:150px; border-left:1px solid var(--gray-100); }
@media(max-width:640px){ .res-aside { border-left:none; border-top:1px solid var(--gray-100); flex-direction:row; align-items:center; } }
.res-price { font-family:var(--font-display); font-size:1.4rem; font-weight:800; color:var(--green-800); line-height:1; }
/* ── Badges ── */
.badge-pend { background:#fef3c7; color:#92400e; }
.badge-conf { background:#d1fae5; color:#065f46; }
.badge-canc { background:#fee2e2; color:#991b1b; }
.badge-pago { background:#dbeafe; color:#1e40af; }
.badge-nopago { background:var(--gray-100); color:var(--gray-500); }
/* ── Reseñas ── */
.resena-card { background:#fff; border-radius:var(--radius-lg); box-shadow:var(--shadow-card); padding:1.25rem 1.5rem; margin-bottom:1rem; display:flex; gap:1rem; align-items:flex-start; }
.resena-stars { display:flex; gap:.15rem; }
/* ── Favoritos ── */
.fav-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1.25rem; }
.fav-card { background:#fff; border-radius:var(--radius-lg); box-shadow:var(--shadow-card); overflow:hidden; transition:box-shadow .2s, transform .2s; }
.fav-card:hover { box-shadow:var(--shadow-lg); transform:translateY(-2px); }
.fav-img { width:100%; height:140px; object-fit:cover; }
.fav-img-ph { width:100%; height:140px; background:linear-gradient(135deg,var(--green-900),var(--green-600)); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.3); font-size:2.5rem; }
.fav-body { padding:1rem; }
/* ── Actividad ── */
.act-item { display:flex; gap:1rem; align-items:flex-start; padding:.85rem 0; border-bottom:1px solid var(--gray-100); }
.act-item:last-child { border-bottom:none; }
.act-icon { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.85rem; }
/* ── Perfil form ── */
.pf-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:600px){ .pf-grid { grid-template-columns:1fr; } }
.pf-field label { display:block; font-size:.82rem; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.45rem; }
.pf-field input, .pf-field select {
    width:100%; padding:.75rem 1rem; border:1.5px solid var(--gray-200);
    border-radius:var(--radius-md); font-size:.95rem; color:var(--gray-900);
    background:var(--gray-50); outline:none; transition:border-color .2s, box-shadow .2s;
}
.pf-field input:focus { border-color:var(--green-600); background:#fff; box-shadow:0 0 0 3px rgba(82,183,136,.15); }
.pf-field input[readonly] { background:var(--gray-100); color:var(--gray-400); cursor:default; }
.pf-divider { border:none; border-top:1.5px solid var(--gray-100); margin:1.75rem 0; }
.pf-section-title { font-size:.82rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--gray-400); margin-bottom:1rem; display:flex; align-items:center; gap:.4rem; }
/* ── Empty state ── */
.uc-empty { text-align:center; padding:3rem 2rem; background:#fff; border-radius:var(--radius-lg); box-shadow:var(--shadow-card); }
.uc-empty i { font-size:3rem; color:var(--gray-200); margin-bottom:.85rem; display:block; }
.uc-empty h3 { color:var(--gray-400); font-weight:600; margin-bottom:.35rem; }
.uc-empty p  { color:var(--gray-400); font-size:.95rem; margin-bottom:1.25rem; }
</style>
@endpush

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $avatarSrc = $user->avatar
        ? (str_starts_with($user->avatar,'http') ? $user->avatar : Storage::disk('public')->url($user->avatar))
        : null;
    $inicial = strtoupper(substr($user->name, 0, 1));
@endphp

{{-- ══ HERO ══ --}}
<section class="uc-hero">
    <div class="container">
        <div class="uc-hero-inner">
            @if($avatarSrc)
                <img src="{{ $avatarSrc }}" alt="{{ $user->name }}" class="uc-avatar">
            @else
                <div class="uc-avatar-init">{{ $inicial }}</div>
            @endif
            <div class="uc-hero-info">
                <h1>Hola, {{ explode(' ', $user->name)[0] }} 👋</h1>
                <p>{{ $user->email }}</p>
                <div class="uc-hero-meta">
                    <span class="uc-badge"><i class="fa-solid fa-circle-user fa-xs"></i> {{ ucfirst($user->rol ?? 'usuario') }}</span>
                    <span class="uc-badge"><i class="fa-solid fa-calendar fa-xs"></i> Desde {{ $user->created_at?->format('M Y') }}</span>
                    @if($user->telefono)
                    <span class="uc-badge"><i class="fa-solid fa-phone fa-xs"></i> {{ $user->telefono }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container uc-wrap">

    {{-- Alertas --}}
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.25rem;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1.25rem;">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.25rem;">
        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
    </div>
    @endif

    {{-- ══ TABS ══ --}}
    <div class="uc-tabs" id="ucTabs">
        <button class="uc-tab" data-tab="reservas">
            <i class="fa-solid fa-calendar-check fa-xs"></i> Reservas
            <span class="uc-tab-count">{{ $reservas->count() }}</span>
        </button>
        <button class="uc-tab" data-tab="resenas">
            <i class="fa-solid fa-star fa-xs"></i> Reseñas
            <span class="uc-tab-count">{{ $calificaciones->count() }}</span>
        </button>
        <button class="uc-tab" data-tab="favoritos">
            <i class="fa-solid fa-heart fa-xs"></i> Favoritos
            <span class="uc-tab-count">{{ $favoritos->count() }}</span>
        </button>
        <button class="uc-tab" data-tab="actividad">
            <i class="fa-solid fa-clock-rotate-left fa-xs"></i> Actividad
        </button>
        <button class="uc-tab" data-tab="perfil">
            <i class="fa-solid fa-user-pen fa-xs"></i> Mi Perfil
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════
         TAB: RESERVAS
    ══════════════════════════════════════════════════════ --}}
    <div class="uc-pane" id="pane-reservas">

        {{-- Filtros --}}
        <form method="GET" action="{{ route('usuario.dashboard') }}"
              style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:flex-end;margin-bottom:1.5rem;background:#fff;padding:1rem 1.25rem;border-radius:var(--radius-lg);box-shadow:var(--shadow-card);">
            <input type="hidden" name="tab" value="reservas">
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;margin-bottom:.3rem;">Estado</label>
                <select name="estado_reserva" style="padding:.5rem .85rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
                    <option value="">Todos</option>
                    @foreach(['pendiente'=>'Pendiente','confirmada'=>'Confirmada','cancelada'=>'Cancelada'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('estado_reserva')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;margin-bottom:.3rem;">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                       style="padding:.5rem .85rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;margin-bottom:.3rem;">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                       style="padding:.5rem .85rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;outline:none;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass fa-xs"></i> Filtrar</button>
            @if(request()->hasAny(['estado_reserva','fecha_desde','fecha_hasta']))
            <a href="{{ route('usuario.dashboard',['tab'=>'reservas']) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-xmark fa-xs"></i> Limpiar</a>
            @endif
        </form>

        @if($reservas->isEmpty())
        <div class="uc-empty">
            <i class="fa-solid fa-calendar-xmark"></i>
            <h3>Sin reservas aún</h3>
            <p>Explora nuestros hoteles y haz tu primera reserva.</p>
            <a href="{{ route('hoteles') }}" class="btn btn-primary"><i class="fa-solid fa-hotel fa-xs"></i> Ver hoteles</a>
        </div>
        @else
        @foreach($reservas as $r)
        @php
            $dias = $r->fecha_entrada->diffInDays($r->fecha_salida);
            $hImg = $r->hotel?->imagen
                ? (str_starts_with($r->hotel->imagen,'http') ? $r->hotel->imagen : Storage::disk('public')->url($r->hotel->imagen))
                : null;
            $badgeEstado = match($r->estado) {
                'confirmada' => 'badge-conf',
                'cancelada'  => 'badge-canc',
                default      => 'badge-pend',
            };
            $badgePago = ($r->estado_pago ?? '') === 'pagado' ? 'badge-pago' : 'badge-nopago';
        @endphp
        <div class="res-card">
            @if($hImg)
                <img src="{{ $hImg }}" alt="{{ $r->hotel?->nombre }}" class="res-img">
            @else
                <div class="res-img-ph"><i class="fa-solid fa-hotel"></i></div>
            @endif
            <div class="res-body">
                <div class="res-hotel">{{ $r->hotel?->nombre ?? '—' }}</div>
                @if($r->hotel?->ubicacion)
                <div class="res-meta"><i class="fa-solid fa-location-dot fa-xs" style="color:var(--green-600);"></i> {{ $r->hotel->ubicacion }}</div>
                @endif
                <div class="res-meta">
                    <i class="fa-solid fa-calendar fa-xs" style="color:var(--green-600);"></i>
                    {{ $r->fecha_entrada->format('d/m/Y') }} → {{ $r->fecha_salida->format('d/m/Y') }}
                    &nbsp;·&nbsp; {{ $dias }} noche{{ $dias!=1?'s':'' }}
                    &nbsp;·&nbsp; {{ $r->num_personas }} persona{{ $r->num_personas!=1?'s':'' }}
                </div>
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.35rem;">
                    <span class="badge {{ $badgeEstado }}" style="font-size:.75rem;">
                        {{ ucfirst($r->estado) }}
                    </span>
                    <span class="badge {{ $badgePago }}" style="font-size:.75rem;">
                        {{ ucfirst($r->estado_pago ?? 'pendiente') }}
                    </span>
                    @if($r->metodo_pago)
                    <span class="badge badge-nopago" style="font-size:.75rem;">
                        <i class="fa-solid fa-credit-card fa-xs"></i> {{ $r->metodo_pago_label }}
                    </span>
                    @endif
                </div>
                @if($r->referencia_pago)
                <div style="font-size:.75rem;color:var(--gray-400);margin-top:.2rem;">Ref: {{ $r->referencia_pago }}</div>
                @endif
            </div>
            <div class="res-aside">
                <div>
                    <div class="res-price">${{ number_format($r->precio_total,0,',','.') }}</div>
                    <div style="font-size:.78rem;color:var(--gray-400);">COP total</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:.4rem;align-items:flex-end;">
                    @if($r->hotel)
                    <a href="{{ route('hoteles.detalle',$r->hotel) }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-eye fa-xs"></i> Ver hotel
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         TAB: RESEÑAS
    ══════════════════════════════════════════════════════ --}}
    <div class="uc-pane" id="pane-resenas">
        @if($calificaciones->isEmpty())
        <div class="uc-empty">
            <i class="fa-solid fa-star-half-stroke"></i>
            <h3>Sin reseñas aún</h3>
            <p>Visita un hotel y deja tu opinión.</p>
            <a href="{{ route('hoteles') }}" class="btn btn-primary"><i class="fa-solid fa-hotel fa-xs"></i> Explorar hoteles</a>
        </div>
        @else
        @foreach($calificaciones as $cal)
        <div class="resena-card">
            <div style="flex-shrink:0;width:42px;height:42px;border-radius:50%;background:var(--green-50);display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-{{ $cal->tipo==='hotel'?'hotel':'utensils' }}" style="color:var(--green-600);font-size:.9rem;"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;flex-wrap:wrap;">
                    <div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--gray-900);">
                            @if($cal->item_url)
                            <a href="{{ $cal->item_url }}" style="color:inherit;text-decoration:none;">{{ $cal->item_nombre }}</a>
                            @else
                            {{ $cal->item_nombre }}
                            @endif
                        </div>
                        <div style="font-size:.78rem;color:var(--gray-400);margin-top:.1rem;">
                            {{ ucfirst($cal->tipo) }} · {{ $cal->created_at?->format('d/m/Y') }}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        <div class="resena-stars">
                            @for($i=1;$i<=5;$i++)
                            <i class="fa-{{ $i<=$cal->calificacion?'solid':'regular' }} fa-star"
                               style="color:#fbbf24;font-size:.9rem;"></i>
                            @endfor
                            <span style="font-size:.82rem;font-weight:700;color:var(--gray-600);margin-left:.3rem;">{{ $cal->calificacion }}/5</span>
                        </div>
                        <form method="POST" action="{{ route('usuario.resenas.destroy',$cal) }}"
                              onsubmit="return confirm('¿Eliminar esta reseña?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:.85rem;" title="Eliminar">
                                <i class="fa-solid fa-trash fa-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @if($cal->comentario)
                <p style="font-size:.88rem;color:var(--gray-600);margin:.5rem 0 0;line-height:1.55;">{{ $cal->comentario }}</p>
                @endif
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         TAB: FAVORITOS
    ══════════════════════════════════════════════════════ --}}
    <div class="uc-pane" id="pane-favoritos">
        @if($favoritos->isEmpty())
        <div class="uc-empty">
            <i class="fa-solid fa-heart-crack"></i>
            <h3>Sin favoritos aún</h3>
            <p>Guarda hoteles y platos que te gusten.</p>
            <a href="{{ route('hoteles') }}" class="btn btn-primary"><i class="fa-solid fa-hotel fa-xs"></i> Explorar</a>
        </div>
        @else
        <div class="fav-grid">
            @foreach($favoritos as $fav)
            @php
                $fImg = $fav->imagen
                    ? (str_starts_with($fav->imagen,'http') ? $fav->imagen : Storage::disk('public')->url($fav->imagen))
                    : null;
            @endphp
            <div class="fav-card">
                @if($fImg)
                <img src="{{ $fImg }}" alt="{{ $fav->nombre }}" class="fav-img">
                @else
                <div class="fav-img-ph">
                    <i class="fa-solid fa-{{ $fav->tipo==='hotel'?'hotel':'utensils' }}"></i>
                </div>
                @endif
                <div class="fav-body">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;margin-bottom:.4rem;">
                        <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--green-700);background:var(--green-50);padding:.15rem .55rem;border-radius:var(--radius-full);">
                            {{ ucfirst($fav->tipo) }}
                        </span>
                        <form method="POST" action="{{ route('favoritos.toggle') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $fav->tipo }}">
                            <input type="hidden" name="item_id" value="{{ $fav->item_id }}">
                            <button type="submit" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:1rem;" title="Quitar de favoritos">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </form>
                    </div>
                    <div style="font-weight:700;font-size:.95rem;color:var(--gray-900);margin-bottom:.25rem;">{{ $fav->nombre }}</div>
                    @if($fav->precio)
                    <div style="font-size:.85rem;color:var(--green-700);font-weight:700;margin-bottom:.6rem;">
                        ${{ number_format($fav->precio,0,',','.') }} COP
                    </div>
                    @endif
                    @if($fav->url)
                    <a href="{{ $fav->url }}" class="btn btn-outline btn-sm" style="width:100%;text-align:center;">
                        <i class="fa-solid fa-eye fa-xs"></i> Ver detalle
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         TAB: ACTIVIDAD
    ══════════════════════════════════════════════════════ --}}
    <div class="uc-pane" id="pane-actividad">
        <div style="background:#fff;border-radius:var(--radius-lg);box-shadow:var(--shadow-card);padding:1.5rem;">
            <h3 style="font-size:.88rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:1.25rem;">
                <i class="fa-solid fa-clock-rotate-left fa-xs"></i> Últimas 10 acciones
            </h3>
            @if($actividad->isEmpty())
            <div style="text-align:center;padding:2rem;color:var(--gray-400);">
                <i class="fa-solid fa-inbox" style="font-size:2rem;display:block;margin-bottom:.75rem;opacity:.4;"></i>
                Sin actividad registrada aún.
            </div>
            @else
            @foreach($actividad as $act)
            <div class="act-item">
                <div class="act-icon" style="background:{{ $act->color }}1a;">
                    <i class="fa-solid {{ $act->icono }}" style="color:{{ $act->color }};"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:.92rem;color:var(--gray-900);">{{ $act->texto }}</div>
                    @if($act->detalle)
                    <div style="font-size:.82rem;color:var(--gray-400);margin-top:.1rem;">{{ $act->detalle }}</div>
                    @endif
                </div>
                <div style="font-size:.78rem;color:var(--gray-400);white-space:nowrap;flex-shrink:0;">
                    {{ $act->fecha?->diffForHumans() }}
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         TAB: PERFIL
    ══════════════════════════════════════════════════════ --}}
    <div class="uc-pane" id="pane-perfil">
        <div style="background:#fff;border-radius:var(--radius-lg);box-shadow:var(--shadow-card);padding:2rem;">

            <form method="POST" action="{{ route('usuario.perfil.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Avatar --}}
                <div class="pf-section-title"><i class="fa-solid fa-image fa-xs"></i> Foto de perfil</div>
                <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:1.5rem;flex-wrap:wrap;">
                    @if($avatarSrc)
                    <img src="{{ $avatarSrc }}" alt="Avatar" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--gray-200);">
                    @else
                    <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--green-600),var(--green-800));display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:900;color:#fff;">{{ $inicial }}</div>
                    @endif
                    <div style="flex:1;min-width:200px;">
                        <div class="pf-field" style="margin-bottom:.6rem;">
                            <label>Subir imagen</label>
                            <input type="file" name="avatar_file" accept="image/*" style="background:#fff;">
                        </div>
                        <div class="pf-field">
                            <label>O pegar URL</label>
                            <input type="url" name="avatar_url" placeholder="https://..." value="{{ old('avatar_url') }}">
                        </div>
                    </div>
                </div>

                <hr class="pf-divider">

                {{-- Datos personales --}}
                <div class="pf-section-title"><i class="fa-solid fa-user fa-xs"></i> Datos personales</div>
                <div class="pf-grid" style="margin-bottom:1.5rem;">
                    <div class="pf-field">
                        <label>Nombre completo *</label>
                        <input type="text" name="name" required value="{{ old('name',$user->name) }}">
                        @error('name')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                    </div>
                    <div class="pf-field">
                        <label>Correo electrónico *</label>
                        <input type="email" name="email" required value="{{ old('email',$user->email) }}">
                        @error('email')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                    </div>
                    <div class="pf-field">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" placeholder="+57 300 000 0000" value="{{ old('telefono',$user->telefono) }}">
                    </div>
                    <div class="pf-field">
                        <label>Rol</label>
                        <input type="text" value="{{ ucfirst($user->rol ?? 'usuario') }}" readonly>
                    </div>
                    <div class="pf-field">
                        <label>Miembro desde</label>
                        <input type="text" value="{{ $user->created_at?->format('d/m/Y') }}" readonly>
                    </div>
                </div>

                <hr class="pf-divider">

                {{-- Contraseña --}}
                <div class="pf-section-title">
                    <i class="fa-solid fa-lock fa-xs"></i> Cambiar contraseña
                    <span style="font-size:.78rem;font-weight:400;text-transform:none;letter-spacing:0;color:var(--gray-400);">(dejar en blanco para no cambiar)</span>
                </div>
                <div class="pf-grid">
                    <div class="pf-field">
                        <label>Contraseña actual</label>
                        <input type="password" name="password_actual" autocomplete="current-password" placeholder="Tu contraseña actual">
                        @error('password_actual')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                    </div>
                    <div></div>
                    <div class="pf-field">
                        <label>Nueva contraseña</label>
                        <input type="password" name="password" autocomplete="new-password" placeholder="Mín. 8 caracteres">
                        @error('password')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                    </div>
                    <div class="pf-field">
                        <label>Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repite la contraseña">
                    </div>
                </div>

                <hr class="pf-divider">

                <div style="display:flex;justify-content:flex-end;gap:.75rem;">
                    <button type="button" onclick="switchTab('reservas')" class="btn btn-outline">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const TABS = ['reservas','resenas','favoritos','actividad','perfil'];

    function switchTab(name) {
        TABS.forEach(t => {
            document.getElementById('pane-' + t)?.classList.remove('active');
        });
        document.querySelectorAll('.uc-tab').forEach(b => b.classList.remove('active'));

        document.getElementById('pane-' + name)?.classList.add('active');
        document.querySelectorAll('.uc-tab[data-tab="' + name + '"]').forEach(b => b.classList.add('active'));

        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', name);
        history.replaceState(null, '', url);
    }

    // Expose globally for inline onclick
    window.switchTab = switchTab;

    // Wire tab buttons
    document.querySelectorAll('.uc-tab').forEach(btn => {
        btn.addEventListener('click', () => switchTab(btn.dataset.tab));
    });

    // Activate from URL param or server-side $tab
    const initTab = new URLSearchParams(window.location.search).get('tab') || '{{ $tab }}';
    switchTab(TABS.includes(initTab) ? initTab : 'reservas');

    // Auto-open perfil tab on validation errors
    @if($errors->any())
        switchTab('perfil');
    @endif
})();
</script>
@endpush
