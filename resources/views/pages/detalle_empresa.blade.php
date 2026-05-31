@extends('layouts.app')

@section('title', $empresa->nombre)

@php
    $logoSrc = $empresa->logo
        ? (str_starts_with($empresa->logo, 'http') ? $empresa->logo : asset('storage/' . $empresa->logo))
        : null;
    $heroImgs = $empresa->heroImagenes;
    $tipoLabel = [
        'hotel'           => 'Hotel / Hospedaje',
        'restaurante'     => 'Restaurante',
        'agencia_turismo' => 'Agencia de Turismo',
        'transporte'      => 'Transporte',
        'artesanias'      => 'Artesanías',
        'otro'            => 'Empresa',
    ][$empresa->tipo_empresa ?? 'otro'] ?? 'Empresa';
    $catIconos = [
        'piscina'     => 'fa-water',
        'habitacion'  => 'fa-bed',
        'restaurante' => 'fa-utensils',
        'salon'       => 'fa-champagne-glasses',
        'exterior'    => 'fa-tree',
        'spa'         => 'fa-spa',
        'parqueadero' => 'fa-car',
        'general'     => 'fa-image',
        'otro'        => 'fa-image',
    ];
@endphp

@section('content')

{{-- ── HERO ── --}}
<section id="emp-hero-section"
    style="min-height:60vh;display:flex;align-items:flex-end;position:relative;overflow:hidden;
    @if($heroImgs->count() > 0)
        background:transparent;
    @elseif($logoSrc)
        background:url('{{ $logoSrc }}') center/cover no-repeat;
    @else
        background:linear-gradient(135deg,var(--green-900),var(--green-700));
    @endif
    ">

    {{-- Carousel slides --}}
    @if($heroImgs->count() > 0)
    <div id="emp-hero-carousel" style="position:absolute;inset:0;z-index:0;">
        @foreach($heroImgs as $i => $img)
        @php
            // Respetar tipo: 'upload' = ruta relativa en storage, cualquier otra cosa = URL directa
            $imgUrl = ($img->tipo === 'upload' || !str_starts_with($img->url, 'http'))
                ? asset('storage/' . $img->url)
                : $img->url;
        @endphp
        <div class="emp-hero-slide" style="position:absolute;inset:0;background-image:url('{{ $imgUrl }}');background-size:cover;background-position:center;opacity:{{ $i === 0 ? '1' : '0' }};transition:opacity 1.2s ease-in-out;"></div>
        @endforeach
    </div>
    @endif

    {{-- Overlay --}}
    <div style="position:absolute;inset:0;z-index:1;background:linear-gradient(to top,rgba(10,40,20,.88) 0%,rgba(10,40,20,.45) 55%,transparent 100%);"></div>

    {{-- Dots --}}
    @if($heroImgs->count() > 1)
    <div id="emp-hero-dots" style="position:absolute;bottom:1.5rem;left:50%;transform:translateX(-50%);z-index:10;display:flex;gap:.5rem;">
        @foreach($heroImgs as $i => $img)
        <button onclick="empGoToSlide({{ $i }})" class="emp-hero-dot"
            style="width:{{ $i === 0 ? '28px' : '10px' }};height:10px;border-radius:5px;border:none;
                   background:{{ $i === 0 ? '#fff' : 'rgba(255,255,255,.45)' }};cursor:pointer;transition:all .3s;padding:0;"
            aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif

    {{-- Hero content --}}
    <div class="container" style="position:relative;z-index:2;padding-bottom:3rem;padding-top:calc(var(--navbar-height) + 2rem);">
        <div style="display:flex;align-items:flex-end;gap:1.5rem;flex-wrap:wrap;">
            @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo {{ $empresa->nombre }}"
                 style="width:100px;height:100px;border-radius:var(--radius-lg);border:3px solid rgba(255,255,255,.3);object-fit:cover;flex-shrink:0;">
            @endif
            <div>
                <div style="display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:.75rem;align-items:center;">
                    <span class="hero-eyebrow">
                        <i class="fa-solid fa-building fa-xs"></i> {{ $tipoLabel }}
                    </span>
                </div>
                <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,5vw,3.2rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:.5rem;">
                    {{ $empresa->nombre }}
                </h1>
                @if($empresa->direccion)
                <p style="color:rgba(255,255,255,.75);font-size:.95rem;">
                    <i class="fa-solid fa-location-dot fa-xs" style="margin-right:.35rem;"></i>{{ $empresa->direccion }}
                </p>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── BODY ── --}}
<section class="container section">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:2.5rem;align-items:start;">

        {{-- Columna izquierda --}}
        <div>

            {{-- Descripción --}}
            @if($empresa->descripcion)
            <div class="admin-section">
                <h2 style="font-family:var(--font-display);color:var(--green-800);margin-bottom:1rem;font-size:1.4rem;">
                    <i class="fa-solid fa-circle-info fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Sobre nosotros
                </h2>
                <p style="line-height:1.8;color:var(--gray-600);">{{ $empresa->descripcion }}</p>
            </div>
            @endif

            {{-- Servicios --}}
            @if(!empty($empresa->servicios))
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-star fa-xs" style="color:var(--gold-500);margin-right:.5rem;"></i>Servicios
                </h3>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                    @foreach($empresa->servicios as $srv)
                    <span style="display:inline-flex;align-items:center;gap:.4rem;background:var(--green-50);border:1.5px solid var(--green-200);border-radius:2rem;padding:.35rem .85rem;font-size:.85rem;color:var(--green-800);">
                        <i class="fa-solid fa-check" style="font-size:.7rem;color:var(--green-700);"></i>{{ $srv }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Galería --}}
            @if($empresa->imagenesActivas->count() > 0)
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1.25rem;font-size:1.1rem;">
                    <i class="fa-solid fa-images fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Galería
                </h3>
                @foreach($empresa->imagenesActivas->groupBy('categoria') as $cat => $imgs)
                <div style="margin-bottom:1.5rem;">
                    <p style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:.75rem;display:flex;align-items:center;gap:.4rem;">
                        <i class="fa-solid {{ $catIconos[$cat] ?? 'fa-image' }}" style="color:var(--green-600);"></i>
                        {{ ucfirst($cat ?? 'General') }}
                    </p>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.75rem;">
                        @foreach($imgs as $img)
                        @php $src = str_starts_with($img->ruta, 'http') ? $img->ruta : asset('storage/' . $img->ruta); @endphp
                        <div class="emp-gallery-thumb"
                             data-src="{{ $src }}"
                             data-titulo="{{ $img->titulo ?? '' }}"
                             style="aspect-ratio:4/3;overflow:hidden;border-radius:var(--radius-md);cursor:pointer;background:var(--gray-100);">
                            <img src="{{ $src }}" alt="{{ $img->titulo ?? $empresa->nombre }}" loading="lazy"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform .3s;"
                                 onerror="this.parentElement.style.background='var(--gray-200)';this.style.display='none'">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Alojamiento --}}
            @if($empresa->hoteles->count() > 0)
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-hotel fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Alojamiento
                </h3>
                <div style="display:flex;flex-direction:column;gap:.75rem;">
                    @foreach($empresa->hoteles as $hotel)
                    @php $hImg = $hotel->imagen ? (str_starts_with($hotel->imagen,'http') ? $hotel->imagen : asset('storage/'.$hotel->imagen)) : null; @endphp
                    <div style="display:flex;gap:1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);overflow:hidden;background:#fff;">
                        @if($hImg)
                        <img src="{{ $hImg }}" alt="{{ $hotel->nombre }}"
                             style="width:140px;height:110px;object-fit:cover;flex-shrink:0;"
                             onerror="this.style.display='none'">
                        @else
                        <div style="width:140px;height:110px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-hotel" style="color:var(--gray-300);font-size:1.5rem;"></i>
                        </div>
                        @endif
                        <div style="padding:.85rem;flex:1;display:flex;flex-direction:column;justify-content:space-between;">
                            <div>
                                <p style="font-weight:700;color:var(--gray-900);margin-bottom:.25rem;">{{ $hotel->nombre }}</p>
                                @if($hotel->precio)
                                <p style="font-size:.85rem;color:var(--green-700);font-weight:600;margin-bottom:.25rem;">
                                    ${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche
                                </p>
                                @endif
                                @php $habDisp = $hotel->habitaciones->where('disponible', true)->count(); @endphp
                                <p style="font-size:.78rem;color:var(--gray-400);">
                                    <i class="fa-solid fa-door-open fa-xs"></i>
                                    {{ $habDisp }} habitación{{ $habDisp !== 1 ? 'es' : '' }} disponible{{ $habDisp !== 1 ? 's' : '' }}
                                </p>
                            </div>
                            <a href="{{ route('hoteles.detalle', $hotel) }}" class="btn btn-outline btn-sm" style="align-self:flex-start;margin-top:.5rem;">
                                Ver hotel <i class="fa-solid fa-arrow-right fa-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Carta / Gastronomía --}}
            @php $gastros = $empresa->gastronomias; @endphp
            @if($gastros->count() > 0)
            <div class="admin-section">
                <h3 style="color:var(--gray-900);margin-bottom:1rem;font-size:1.1rem;">
                    <i class="fa-solid fa-utensils fa-xs" style="color:var(--green-600);margin-right:.5rem;"></i>Carta
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
                    @foreach($gastros as $plato)
                    @php $pImg = $plato->imagen ? (str_starts_with($plato->imagen,'http') ? $plato->imagen : asset('storage/'.$plato->imagen)) : null; @endphp
                    <div style="border:1.5px solid var(--gray-200);border-radius:var(--radius-md);overflow:hidden;background:#fff;">
                        @if($pImg)
                        <img src="{{ $pImg }}" alt="{{ $plato->nombre }}"
                             style="width:100%;height:150px;object-fit:cover;display:block;"
                             onerror="this.style.display='none'">
                        @else
                        <div style="height:150px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-utensils" style="color:var(--gray-300);font-size:1.5rem;"></i>
                        </div>
                        @endif
                        <div style="padding:.75rem;">
                            <p style="font-weight:700;color:var(--gray-900);margin-bottom:.2rem;">{{ $plato->nombre }}</p>
                            @if($plato->descripcion)
                            <p style="font-size:.8rem;color:var(--gray-600);margin-bottom:.4rem;">{{ Str::limit($plato->descripcion, 60) }}</p>
                            @endif
                            @if($plato->precio_promedio)
                            <p style="font-size:.85rem;color:var(--green-700);font-weight:700;">
                                ${{ number_format($plato->precio_promedio, 0, ',', '.') }} COP
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div style="position:sticky;top:calc(var(--navbar-height) + 1.5rem);">
            <div class="admin-section" style="border-top:4px solid var(--green-700);">
                <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem;">Contacto</p>

                @if($empresa->telefono)
                <div style="display:flex;gap:.75rem;align-items:flex-start;margin-bottom:1rem;">
                    <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-phone" style="color:var(--green-700);"></i>
                    </div>
                    <div>
                        <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Teléfono</p>
                        <p style="font-size:.9rem;color:var(--gray-900);">{{ $empresa->telefono }}</p>
                    </div>
                </div>
                @endif

                @if($empresa->direccion)
                <div style="display:flex;gap:.75rem;align-items:flex-start;margin-bottom:1rem;">
                    <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-location-dot" style="color:var(--green-700);"></i>
                    </div>
                    <div>
                        <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Dirección</p>
                        <p style="font-size:.9rem;color:var(--gray-900);">{{ $empresa->direccion }}</p>
                    </div>
                </div>
                @endif

                @if($empresa->sitio_web)
                <div style="display:flex;gap:.75rem;align-items:flex-start;margin-bottom:1rem;">
                    <div style="width:36px;height:36px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-globe" style="color:var(--green-700);"></i>
                    </div>
                    <div>
                        <p style="font-size:.75rem;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Sitio web</p>
                        <a href="{{ $empresa->sitio_web }}" target="_blank" rel="noopener"
                           style="font-size:.9rem;color:var(--green-700);word-break:break-all;">
                            {{ parse_url($empresa->sitio_web, PHP_URL_HOST) ?? $empresa->sitio_web }}
                        </a>
                    </div>
                </div>
                @endif

                {{-- Redes sociales --}}
                @if($empresa->instagram || $empresa->facebook)
                <hr style="border:none;border-top:1px solid var(--gray-200);margin:1rem 0;">
                <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
                    @if($empresa->instagram)
                    @php
                        $igHandle = ltrim($empresa->instagram, '@');
                        $igUrl = str_starts_with($empresa->instagram, 'http') ? $empresa->instagram : 'https://instagram.com/' . $igHandle;
                    @endphp
                    <a href="{{ $igUrl }}" target="_blank" rel="noopener" aria-label="Instagram"
                       style="width:40px;height:40px;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;
                              background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;font-size:1.1rem;text-decoration:none;">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    @endif
                    @if($empresa->facebook)
                    @php $fbUrl = str_starts_with($empresa->facebook, 'http') ? $empresa->facebook : 'https://facebook.com/' . ltrim($empresa->facebook, '/'); @endphp
                    <a href="{{ $fbUrl }}" target="_blank" rel="noopener" aria-label="Facebook"
                       style="width:40px;height:40px;border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;
                              background:#1877f2;color:#fff;font-size:1.1rem;text-decoration:none;">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

    </div>
</section>

{{-- ── LIGHTBOX ── --}}
<div id="emp-lightbox"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.88);align-items:center;justify-content:center;"
     onclick="empCerrarModal()" role="dialog" aria-modal="true" aria-label="Visor de imagen">
    <button onclick="empCerrarModal()" aria-label="Cerrar"
            style="position:absolute;top:1rem;right:1.25rem;background:rgba(255,255,255,.15);border:none;color:#fff;font-size:1.5rem;width:44px;height:44px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;">
        ✕
    </button>
    <img id="emp-lightbox-img" src="" alt=""
         style="max-width:90vw;max-height:80vh;object-fit:contain;border-radius:var(--radius-md);box-shadow:0 8px 40px rgba(0,0,0,.6);"
         onclick="event.stopPropagation()">
</div>

@endsection

@push('scripts')
<script>
// ── Lightbox ──
function empAbrirModal(src, titulo) {
    const lb = document.getElementById('emp-lightbox');
    document.getElementById('emp-lightbox-img').src = src;
    document.getElementById('emp-lightbox-img').alt = titulo || '';
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function empCerrarModal() {
    document.getElementById('emp-lightbox').style.display = 'none';
    document.getElementById('emp-lightbox-img').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') empCerrarModal(); });

// Delegación de eventos para thumbnails de galería
document.addEventListener('click', function(e) {
    const thumb = e.target.closest('.emp-gallery-thumb');
    if (thumb) {
        empAbrirModal(thumb.dataset.src, thumb.dataset.titulo);
    }
});
// Hover effect en thumbnails
document.addEventListener('mouseover', function(e) {
    const thumb = e.target.closest('.emp-gallery-thumb');
    if (thumb) { const img = thumb.querySelector('img'); if (img) img.style.transform = 'scale(1.05)'; }
});
document.addEventListener('mouseout', function(e) {
    const thumb = e.target.closest('.emp-gallery-thumb');
    if (thumb) { const img = thumb.querySelector('img'); if (img) img.style.transform = 'scale(1)'; }
});

// ── Carousel ──
@if(isset($heroImgs) && $heroImgs->count() > 1)
const empSlides = document.querySelectorAll('.emp-hero-slide');
const empDots   = document.querySelectorAll('.emp-hero-dot');
let empCurrent = 0, empTimer = null;
function empGoToSlide(idx) {
    empSlides[empCurrent].style.opacity = '0';
    empDots[empCurrent].style.width = '10px';
    empDots[empCurrent].style.background = 'rgba(255,255,255,.45)';
    empCurrent = idx;
    empSlides[empCurrent].style.opacity = '1';
    empDots[empCurrent].style.width = '28px';
    empDots[empCurrent].style.background = '#fff';
}
function empNextSlide() { empGoToSlide((empCurrent + 1) % empSlides.length); }
empTimer = setInterval(empNextSlide, 5000);
const empHeroSection = document.getElementById('emp-hero-section');
empHeroSection.addEventListener('mouseenter', () => clearInterval(empTimer));
empHeroSection.addEventListener('mouseleave', () => { empTimer = setInterval(empNextSlide, 5000); });
@endif
</script>
@endpush
