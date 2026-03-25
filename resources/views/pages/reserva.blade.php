@extends('layouts.app')

@section('title', 'Reservar — ' . $hotel->nombre)
@section('body-class', 'no-hero')

@section('content')

{{-- Page hero mini --}}
<section style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);padding:5rem 0 3rem;margin-top:var(--navbar-height);">
    <div class="container" style="text-align:center;">
        <span style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:var(--green-200);font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.4rem 1rem;border-radius:var(--radius-full);margin-bottom:1rem;">
            <i class="fa-solid fa-calendar-check fa-xs"></i> Reserva
        </span>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.6rem,4vw,2.5rem);font-weight:900;color:#fff;margin-bottom:.5rem;">
            Reservar en {{ $hotel->nombre }}
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.95rem;">
            <i class="fa-solid fa-location-dot fa-xs" style="margin-right:.3rem;"></i>{{ $hotel->ubicacion }}
        </p>
    </div>
</section>

{{-- Formulario --}}
<section class="container section">
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;max-width:900px;margin:0 auto;align-items:start;">

        {{-- Info del hotel --}}
        <div class="admin-section" style="border-top:4px solid var(--green-700);">
            @php
                $hotelImg = $hotel->imagen
                    ? (str_starts_with($hotel->imagen, 'http') ? $hotel->imagen : Storage::disk('public')->url($hotel->imagen))
                    : null;
            @endphp
            @if($hotelImg)
                <img src="{{ $hotelImg }}" alt="{{ $hotel->nombre }}"
                     style="width:100%;height:180px;object-fit:cover;border-radius:var(--radius-md);margin-bottom:1.25rem;">
            @else
                <div style="width:100%;height:180px;background:linear-gradient(135deg,var(--green-800),var(--green-600));border-radius:var(--radius-md);margin-bottom:1.25rem;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-hotel" style="font-size:3rem;color:rgba(255,255,255,.4);"></i>
                </div>
            @endif
            <h3 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:.5rem;">{{ $hotel->nombre }}</h3>
            <div style="display:flex;flex-direction:column;gap:.6rem;margin-top:.75rem;">
                <div style="display:flex;align-items:center;gap:.6rem;font-size:.88rem;color:var(--gray-600);">
                    <i class="fa-solid fa-location-dot" style="color:var(--green-600);width:16px;text-align:center;"></i>
                    {{ $hotel->ubicacion }}
                </div>
                <div style="display:flex;align-items:center;gap:.6rem;font-size:.88rem;color:var(--gray-600);">
                    <i class="fa-solid fa-users" style="color:var(--green-600);width:16px;text-align:center;"></i>
                    Capacidad: {{ $hotel->capacidad }} personas
                </div>
                <div style="display:flex;align-items:center;gap:.6rem;font-size:1rem;font-weight:700;color:var(--green-800);margin-top:.25rem;">
                    <i class="fa-solid fa-tag" style="color:var(--gold-500);width:16px;text-align:center;"></i>
                    ${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche
                </div>
            </div>

            <div id="precio-calculado" style="margin-top:1.25rem;padding:1rem;background:var(--green-50);border-radius:var(--radius-md);border-left:3px solid var(--green-600);display:none;">
                <p style="font-size:.8rem;color:var(--gray-600);margin-bottom:.2rem;">Total estimado</p>
                <p id="precio-texto" style="font-size:1.3rem;font-weight:800;color:var(--green-800);"></p>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="admin-section">
            <h2 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:1.5rem;">
                <i class="fa-solid fa-pen-to-square fa-xs" style="color:var(--green-600);margin-right:.4rem;"></i>
                Datos de la Reserva
            </h2>

            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom:1.25rem;">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('reservar.store') }}">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="fecha_entrada" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Fecha de Entrada
                    </label>
                    <input type="date" id="fecha_entrada" name="fecha_entrada" required
                           min="{{ date('Y-m-d') }}" value="{{ old('fecha_entrada') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;transition:border-color .2s;">
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="fecha_salida" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Fecha de Salida
                    </label>
                    <input type="date" id="fecha_salida" name="fecha_salida" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('fecha_salida') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;transition:border-color .2s;">
                </div>

                <div class="form-group" style="margin-bottom:1.75rem;">
                    <label for="num_personas" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Número de Personas
                    </label>
                    <input type="number" id="num_personas" name="num_personas" required
                           min="1" max="{{ $hotel->capacidad }}" value="{{ old('num_personas', 1) }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;transition:border-color .2s;">
                    <p style="font-size:.78rem;color:var(--gray-400);margin-top:.3rem;">Máximo {{ $hotel->capacidad }} personas</p>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fa-solid fa-calendar-check"></i> Confirmar Reserva
                </button>
            </form>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
const precioPorNoche = {{ $hotel->precio }};
const inputEntrada = document.getElementById('fecha_entrada');
const inputSalida  = document.getElementById('fecha_salida');
const precioBox    = document.getElementById('precio-calculado');
const precioTexto  = document.getElementById('precio-texto');

function calcular() {
    const entrada = inputEntrada.value;
    const salida  = inputSalida.value;
    if (entrada && salida) {
        const dias = (new Date(salida) - new Date(entrada)) / 86400000;
        if (dias > 0) {
            const total = dias * precioPorNoche;
            precioTexto.textContent = '$' + total.toLocaleString('es-CO') + ' COP (' + dias + ' noche' + (dias > 1 ? 's' : '') + ')';
            precioBox.style.display = 'block';
        } else {
            precioBox.style.display = 'none';
        }
    }
}

inputEntrada.addEventListener('change', calcular);
inputSalida.addEventListener('change', calcular);
</script>
@endpush
