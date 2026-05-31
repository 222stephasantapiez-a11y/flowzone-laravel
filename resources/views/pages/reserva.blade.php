@extends('layouts.app')

@section('title', 'Reservar — ' . $hotel->nombre)
@section('body-class', 'no-hero')

@section('content')

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

<section class="container section">
    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;max-width:900px;margin:0 auto;align-items:start;">

        {{-- Info del hotel + habitaciones --}}
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
            </div>

            <div id="aviso-fechas" style="margin-top:1.25rem;padding:.85rem 1rem;background:#fef9c3;border:1px solid #fde047;border-radius:var(--radius-md);">
                <p style="font-size:.82rem;color:#854d0e;margin:0;"><i class="fa-solid fa-calendar fa-xs"></i> Selecciona las fechas para ver disponibilidad de habitaciones.</p>
            </div>

            <div id="habitaciones-container" style="margin-top:1.25rem;display:none;">
                <h4 style="font-size:.82rem;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem;">
                    <i class="fa-solid fa-bed fa-xs"></i> Habitaciones
                </h4>
                <div id="habitaciones-lista" style="display:flex;flex-direction:column;gap:.5rem;"></div>
            </div>

            <div id="precio-calculado" style="margin-top:1.25rem;padding:1rem;background:var(--green-50);border-radius:var(--radius-md);border-left:3px solid var(--green-600);display:none;">
                <p style="font-size:.8rem;color:var(--gray-600);margin-bottom:.2rem;">Total estimado</p>
                <p id="precio-texto" style="font-size:1.3rem;font-weight:800;color:var(--green-800);"></p>
                <p id="hab-seleccionada-texto" style="font-size:.78rem;color:var(--gray-500);margin-top:.2rem;"></p>
            </div>

            <div style="margin-top:1rem;padding:.85rem 1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--radius-md);">
                <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.4rem;">
                    <i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.1rem;"></i>
                    <span style="font-size:.82rem;color:#166534;font-weight:700;">Pago 100% seguro con Wompi</span>
                </div>
                <p style="font-size:.76rem;color:#166534;margin:0;">Tarjeta, Nequi, PSE o transferencia Bancolombia.</p>
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
                <input type="hidden" name="habitacion_id" id="campo-habitacion-id" value="{{ old('habitacion_id') }}">

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="fecha_entrada" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Fecha de Entrada *
                    </label>
                    <input type="date" id="fecha_entrada" name="fecha_entrada" required
                           min="{{ date('Y-m-d') }}" value="{{ old('fecha_entrada') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;">
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="fecha_salida" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Fecha de Salida *
                    </label>
                    <input type="date" id="fecha_salida" name="fecha_salida" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('fecha_salida') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;">
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        <i class="fa-solid fa-bed fa-xs" style="color:var(--green-600);"></i> Habitación seleccionada
                    </label>
                    <div id="hab-seleccionada-label" style="padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;color:var(--gray-400);background:#fff;">
                        Selecciona fechas y luego una habitación
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:1.75rem;">
                    <label for="num_personas" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Número de Personas *
                    </label>
                    <input type="number" id="num_personas" name="num_personas" required
                           min="1" max="{{ $hotel->capacidad }}" value="{{ old('num_personas', 1) }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;">
                    <p id="cap-hint" style="font-size:.78rem;color:var(--gray-400);margin-top:.3rem;">Máximo {{ $hotel->capacidad }} personas</p>
                </div>

                <div style="padding:1rem;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:var(--radius-md);margin-bottom:1.5rem;">
                    <div style="display:flex;align-items:flex-start;gap:.65rem;">
                        <i class="fa-solid fa-circle-info" style="color:#2563eb;margin-top:.1rem;"></i>
                        <p style="font-size:.78rem;color:#1e40af;line-height:1.5;margin:0;">
                            Al hacer clic en <strong>"Ir a pagar"</strong> serás redirigido al checkout seguro de Wompi.
                        </p>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Ir a pagar con Wompi
                </button>

                <p style="text-align:center;font-size:.75rem;color:var(--gray-400);margin-top:.75rem;">
                    <i class="fa-solid fa-shield-halved fa-xs"></i> Pago seguro por <strong>Wompi</strong>
                </p>
            </form>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
const HOTEL_ID         = {{ $hotel->id }};
const PRECIO_BASE      = {{ $hotel->precio ?? 0 }};
const URL_HABITACIONES = '{{ route("reservar.habitaciones") }}';
const PLAN_HAB_ID      = {{ request('plan_hab_id', 0) }};

let precioActual     = 0;
let habNombre        = '';
let habitacionesData = [];

const inputEntrada = document.getElementById('fecha_entrada');
const inputSalida  = document.getElementById('fecha_salida');
const precioBox    = document.getElementById('precio-calculado');
const precioTexto  = document.getElementById('precio-texto');
const habTexto     = document.getElementById('hab-seleccionada-texto');
const container    = document.getElementById('habitaciones-container');
const lista        = document.getElementById('habitaciones-lista');
const avisofechas  = document.getElementById('aviso-fechas');

function resetHabitacion() {
    document.getElementById('campo-habitacion-id').value = '';
    document.getElementById('hab-seleccionada-label').textContent = 'Selecciona una habitación';
    document.getElementById('hab-seleccionada-label').style.color = 'var(--gray-400)';
    document.getElementById('hab-seleccionada-label').style.fontWeight = 'normal';
    precioActual = 0;
    habNombre    = '';
    precioBox.style.display = 'none';
}

function cargarHabitaciones() {
    const entrada  = inputEntrada.value;
    const salida   = inputSalida.value;
    const personas = document.getElementById('num_personas').value || 1;
    if (!entrada || !salida || salida <= entrada) return;

    lista.innerHTML = '<p style="color:var(--gray-400);font-size:.82rem;padding:.5rem 0;"><i class="fa-solid fa-spinner fa-spin fa-xs"></i> Verificando disponibilidad...</p>';
    container.style.display = 'block';
    avisofechas.style.display = 'none';
    resetHabitacion();

    fetch(`${URL_HABITACIONES}?hotel_id=${HOTEL_ID}&fecha_entrada=${entrada}&fecha_salida=${salida}&num_personas=${personas}`)
        .then(r => r.json())
        .then(data => {
            // Si el hotel no es válido, redirigir a hoteles
            if (data.redirect) {
                window.location.href = data.redirect;
                return;
            }

            const habs = Array.isArray(data) ? data : [];
            habitacionesData = habs;
            lista.innerHTML  = '';

            if (!habs.length) {
                lista.innerHTML = '<p style="color:var(--gray-400);font-size:.82rem;">No hay habitaciones disponibles para esa cantidad de personas.</p>';
                return;
            }

            habs.forEach(hab => {
                const div = document.createElement('div');
                div.id = 'hab-card-' + hab.id;
                div.style.cssText = `background:${hab.ocupada ? '#fff5f5' : '#f8fafc'};border:1.5px solid ${hab.ocupada ? '#fecaca' : 'var(--gray-200)'};border-radius:var(--radius-md);padding:.75rem 1rem;transition:border-color .2s;${hab.ocupada ? 'opacity:.65;cursor:not-allowed;' : 'cursor:pointer;'}`;

                const amenidades = (hab.amenidades || []).slice(0, 3).map(a =>
                    `<span style="background:var(--green-50);color:var(--green-700);border-radius:2rem;padding:.1rem .45rem;font-size:.68rem;font-weight:600;">${a}</span>`
                ).join('');

                div.innerHTML = `
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-weight:700;font-size:.88rem;color:var(--gray-800);">
                                ${hab.nombre}
                                ${hab.ocupada ? '<span style="background:#fee2e2;color:#dc2626;border-radius:2rem;padding:.1rem .5rem;font-size:.68rem;font-weight:700;margin-left:.35rem;">Ocupada</span>' : ''}
                            </div>
                            <div style="font-size:.75rem;color:var(--gray-400);margin-top:.15rem;">
                                ${hab.tipo || ''} · ${hab.capacidad} personas${hab.tipo_cama ? ' · ' + hab.tipo_cama : ''}
                            </div>
                            ${amenidades ? `<div style="display:flex;flex-wrap:wrap;gap:.25rem;margin-top:.35rem;">${amenidades}</div>` : ''}
                        </div>
                        <div style="text-align:right;flex-shrink:0;margin-left:.75rem;">
                            <div style="font-weight:800;color:var(--green-700);font-size:.95rem;">$${hab.precio.toLocaleString('es-CO')}</div>
                            <div style="font-size:.7rem;color:var(--gray-400);">/ noche</div>
                        </div>
                    </div>`;

                if (!hab.ocupada) {
                    div.addEventListener('click', () => seleccionarHabitacion(hab.id, hab.precio, hab.nombre, hab.capacidad));
                }
                lista.appendChild(div);

                if (PLAN_HAB_ID && hab.id == PLAN_HAB_ID && !hab.ocupada) {
                    seleccionarHabitacion(hab.id, hab.precio, hab.nombre, hab.capacidad);
                }
            });
        })
        .catch(() => {
            lista.innerHTML = '<p style="color:var(--danger);font-size:.82rem;">Error al cargar habitaciones.</p>';
        });
}

function seleccionarHabitacion(id, precio, nombre, capacidad) {
    document.querySelectorAll('[id^="hab-card-"]').forEach(c => {
        const hab = habitacionesData.find(h => h.id == c.id.replace('hab-card-', ''));
        if (hab && !hab.ocupada) {
            c.style.borderColor = 'var(--gray-200)';
            c.style.background  = '#f8fafc';
        }
    });
    const card = document.getElementById('hab-card-' + id);
    if (card) {
        card.style.borderColor = 'var(--green-600)';
        card.style.background  = '#f0fdf4';
    }
    document.getElementById('campo-habitacion-id').value = id;
    document.getElementById('hab-seleccionada-label').textContent = nombre + ' — $' + precio.toLocaleString('es-CO') + '/noche';
    document.getElementById('hab-seleccionada-label').style.color = 'var(--gray-900)';
    document.getElementById('hab-seleccionada-label').style.fontWeight = '600';
    document.getElementById('num_personas').max = capacidad;
    document.getElementById('cap-hint').textContent = 'Máximo ' + capacidad + ' personas para esta habitación';
    precioActual = precio;
    habNombre    = nombre;
    calcular();
}

function calcular() {
    const entrada = inputEntrada.value;
    const salida  = inputSalida.value;
    const habId   = document.getElementById('campo-habitacion-id').value;

    if (entrada && salida && habId && precioActual > 0) {
        const dias = (new Date(salida) - new Date(entrada)) / 86400000;
        if (dias > 0) {
            const total = dias * precioActual;
            precioTexto.textContent = '$' + total.toLocaleString('es-CO') + ' COP (' + dias + ' noche' + (dias > 1 ? 's' : '') + ')';
            if (habNombre) habTexto.textContent = '🛏 ' + habNombre;
            precioBox.style.display = 'block';
        } else {
            precioBox.style.display = 'none';
        }
    } else {
        precioBox.style.display = 'none';
    }
}

inputEntrada.addEventListener('change', cargarHabitaciones);
inputSalida.addEventListener('change',  cargarHabitaciones);
document.getElementById('num_personas').addEventListener('change', cargarHabitaciones);
</script>
@endpush