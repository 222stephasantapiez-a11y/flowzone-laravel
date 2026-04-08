@extends('layouts.app')

@section('title', 'Mis Favoritos')
@section('body-class', 'no-hero')

@section('content')

{{-- Hero --}}
<section style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);padding:5rem 0 3rem;margin-top:var(--navbar-height);">
    <div class="container" style="text-align:center;">
        <span class="page-hero-eyebrow" style="margin:0 auto 1rem;">
            <i class="fa-solid fa-heart fa-xs"></i> Mi cuenta
        </span>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:#fff;margin-bottom:.5rem;">
            Mis Favoritos
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.95rem;">
            {{ $hoteles->count() + $lugares->count() }} elemento(s) guardado(s)
        </p>
    </div>
</section>

<section class="container section">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
    @endif

    @if($hoteles->isEmpty() && $lugares->isEmpty())

        {{-- Estado vacío --}}
        <div style="background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius-lg);text-align:center;padding:5rem 2rem;box-shadow:var(--shadow-sm);">
            <div style="width:80px;height:80px;background:var(--gray-100);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="fa-solid fa-heart-crack" style="font-size:2rem;color:var(--gray-400);"></i>
            </div>
            <h3 style="font-size:1.2rem;font-weight:700;color:var(--gray-900);margin-bottom:.5rem;">Aún no tienes favoritos</h3>
            <p style="color:var(--gray-400);font-size:.9rem;max-width:380px;margin:0 auto 2rem;">
                Explora lugares y hoteles, y guarda los que más te gusten tocando el botón de corazón.
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="{{ route('lugares') }}" class="btn btn-primary">
                    <i class="fa-solid fa-map-pin fa-xs"></i> Explorar Lugares
                </a>
                <a href="{{ route('hoteles') }}" class="btn btn-outline">
                    <i class="fa-solid fa-hotel fa-xs"></i> Ver Hoteles
                </a>
            </div>
        </div>

    @else

        {{-- ── Hoteles favoritos ── --}}
        @if($hoteles->isNotEmpty())
        <div style="margin-bottom:3rem;">
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--gray-200);">
                <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-hotel" style="color:var(--green-700);"></i>
                </div>
                <h2 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin:0;">Hoteles guardados</h2>
                <span style="background:var(--green-50);color:var(--green-800);font-size:.75rem;font-weight:700;padding:.2rem .65rem;border-radius:var(--radius-full);">
                    {{ $hoteles->count() }}
                </span>
            </div>

            <div class="favs-grid">
                @foreach($hoteles as $hotel)
                @php
                    $img = $hotel->imagen
                        ? (str_starts_with($hotel->imagen, 'http') ? $hotel->imagen : asset('storage/' . $hotel->imagen))
                        : null;
                    $stats = \App\Models\Calificacion::stats('hotel', $hotel->id);
                @endphp
                <div class="fav-card" data-card-id="hotel-{{ $hotel->id }}">
                    <div class="fav-card-img" style="{{ $img ? 'background-image:url(\''.$img.'\')' : '' }}">
                        @if(!$img)
                            <i class="fa-solid fa-hotel" style="font-size:2.5rem;color:rgba(255,255,255,.4);"></i>
                        @endif
                        <button class="fav-remove-btn btn-favorito-remove"
                                data-tipo="hotel" data-id="{{ $hotel->id }}"
                                title="Quitar de favoritos"
                                aria-label="Quitar de favoritos">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                        @if($hotel->disponibilidad)
                            <span class="fav-badge fav-badge-green">Disponible</span>
                        @endif
                    </div>
                    <div class="fav-card-body">
                        <h3 class="fav-card-title">{{ $hotel->nombre }}</h3>
                        @if($hotel->ubicacion)
                            <p class="fav-card-meta">
                                <i class="fa-solid fa-location-dot fa-xs"></i> {{ $hotel->ubicacion }}
                            </p>
                        @endif
                        <div class="fav-card-footer">
                            <div>
                                <span class="fav-price">${{ number_format($hotel->precio, 0, ',', '.') }}</span>
                                <span style="font-size:.75rem;color:var(--gray-400);"> COP/noche</span>
                            </div>
                            @if($stats['total'] > 0)
                                <span class="fav-rating">
                                    <i class="fa-solid fa-star" style="color:var(--gold-500);font-size:.75rem;"></i>
                                    {{ $stats['promedio'] }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('hoteles.detalle', $hotel) }}" class="btn btn-outline btn-block" style="margin-top:.85rem;font-size:.85rem;">
                            Ver hotel <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Lugares favoritos ── --}}
        @if($lugares->isNotEmpty())
        <div>
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--gray-200);">
                <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-map-pin" style="color:var(--green-700);"></i>
                </div>
                <h2 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin:0;">Lugares guardados</h2>
                <span style="background:var(--green-50);color:var(--green-800);font-size:.75rem;font-weight:700;padding:.2rem .65rem;border-radius:var(--radius-full);">
                    {{ $lugares->count() }}
                </span>
            </div>

            <div class="favs-grid">
                @foreach($lugares as $lugar)
                @php
                    $img = $lugar->imagen
                        ? (str_starts_with($lugar->imagen, 'http') ? $lugar->imagen : asset('storage/' . $lugar->imagen))
                        : null;
                    $stats = \App\Models\Calificacion::stats('lugar', $lugar->id);
                @endphp
                <div class="fav-card" data-card-id="lugar-{{ $lugar->id }}">
                    <div class="fav-card-img" style="{{ $img ? 'background-image:url(\''.$img.'\')' : '' }}">
                        @if(!$img)
                            <i style="font-size:2.5rem;color:rgba(255,255,255,.4);"></i>
                        @endif
                        <button class="fav-remove-btn btn-favorito-remove"
                                data-tipo="lugar" data-id="{{ $lugar->id }}"
                                title="Quitar de favoritos"
                                aria-label="Quitar de favoritos">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                        @if($lugar->categoria)
                            <span class="fav-badge fav-badge-teal">{{ $lugar->categoria }}</span>
                        @endif
                    </div>
                    <div class="fav-card-body">
                        <h3 class="fav-card-title">{{ $lugar->nombre }}</h3>
                        @if($lugar->ubicacion)
                            <p class="fav-card-meta">
                                <i class="fa-solid fa-location-dot fa-xs"></i> {{ $lugar->ubicacion }}
                            </p>
                        @endif
                        <div class="fav-card-footer">
                            <div>
                                @if($lugar->precio_entrada > 0)
                                    <span class="fav-price">${{ number_format($lugar->precio_entrada, 0, ',', '.') }}</span>
                                    <span style="font-size:.75rem;color:var(--gray-400);"> COP entrada</span>
                                @else
                                    <span style="font-size:.82rem;color:var(--success);font-weight:600;">
                                        <i class="fa-solid fa-circle-check fa-xs"></i> Entrada gratuita
                                    </span>
                                @endif
                            </div>
                            @if($stats['total'] > 0)
                                <span class="fav-rating">
                                    <i class="fa-solid fa-star" style="color:var(--gold-500);font-size:.75rem;"></i>
                                    {{ $stats['promedio'] }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('lugares.detalle', $lugar) }}" class="btn btn-outline btn-block" style="margin-top:.85rem;font-size:.85rem;">
                            Ver lugar <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    @endif
</section>

<style>
.favs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.5rem;
}

.fav-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform .25s ease, box-shadow .25s ease;
}

.fav-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.fav-card-img {
    height: 180px;
    background: linear-gradient(135deg, var(--green-800), var(--green-600));
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fav-remove-btn {
    position: absolute;
    top: .65rem;
    right: .65rem;
    width: 34px;
    height: 34px;
    background: rgba(255,255,255,.92);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--danger);
    font-size: .85rem;
    transition: background .2s, transform .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}

.fav-remove-btn:hover {
    background: #fff;
    transform: scale(1.1);
}

.fav-badge {
    position: absolute;
    bottom: .65rem;
    left: .65rem;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    padding: .25rem .65rem;
    border-radius: var(--radius-full);
    color: #fff;
}

.fav-badge-green  { background: var(--green-700); }
.fav-badge-teal   { background: #0891b2; }

.fav-card-body {
    padding: 1.1rem 1.1rem 1.25rem;
}

.fav-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: .35rem;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fav-card-meta {
    font-size: .8rem;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: .3rem;
    margin-bottom: .75rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.fav-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
}

.fav-price {
    font-weight: 700;
    color: var(--green-800);
    font-size: .95rem;
}

.fav-rating {
    display: flex;
    align-items: center;
    gap: .25rem;
    font-size: .82rem;
    font-weight: 600;
    color: var(--gray-600);
    white-space: nowrap;
}

@media (max-width: 640px) {
    .favs-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.querySelectorAll('.btn-favorito-remove').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var tipo = this.dataset.tipo;
        var id   = this.dataset.id;
        var card = this.closest('.fav-card');

        fetch('{{ route('favoritos.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ tipo: tipo, item_id: parseInt(id) }),
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.agregado && card) {
                card.style.transition = 'opacity .3s, transform .3s';
                card.style.opacity = '0';
                card.style.transform = 'scale(.95)';
                setTimeout(function() {
                    card.remove();
                    // Si no quedan cards en la sección, recargar para mostrar estado vacío
                    var grids = document.querySelectorAll('.favs-grid');
                    var total = 0;
                    grids.forEach(function(g) { total += g.querySelectorAll('.fav-card').length; });
                    if (total === 0) window.location.reload();
                }, 320);
            }
        })
        .catch(function(err) { console.error(err); });
    });
});
</script>

@endsection
