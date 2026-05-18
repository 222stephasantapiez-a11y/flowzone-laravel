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

            {{-- Badge pago seguro --}}
            <div style="margin-top:1.5rem;padding:.75rem 1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--radius-md);display:flex;align-items:center;gap:.6rem;">
                <i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.1rem;"></i>
                <span style="font-size:.82rem;color:#166534;font-weight:600;">Pago seguro · Entorno de pruebas</span>
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

                {{-- ══ MÉTODOS DE PAGO ══ --}}
                <div style="margin-bottom:1.75rem;">
                    <p style="font-size:.85rem;font-weight:700;color:var(--gray-900);margin-bottom:.75rem;">
                        <i class="fa-solid fa-credit-card" style="color:var(--green-600);margin-right:.4rem;"></i>
                        Método de Pago
                    </p>

                    <div style="display:flex;flex-direction:column;gap:.6rem;">

                        {{-- Nequi --}}
                        <label for="pago_nequi" class="metodo-label" data-target="form_nequi"
                               style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border:2px solid var(--gray-200);border-radius:var(--radius-md);cursor:pointer;transition:border-color .2s;">
                            <input type="radio" id="pago_nequi" name="metodo_pago" value="nequi"
                                   {{ old('metodo_pago') === 'nequi' ? 'checked' : '' }}
                                   style="accent-color:#7c3aed;" class="metodo-radio">
                            <span style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:32px;height:32px;background:linear-gradient(135deg,#7c3aed,#6d28d9);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-mobile-screen-button" style="color:#fff;font-size:.85rem;"></i>
                                </span>
                                <span style="font-size:.9rem;font-weight:600;color:var(--gray-900);">Nequi</span>
                            </span>
                        </label>

                        {{-- Bancolombia PSE --}}
                        <label for="pago_bancolombia" class="metodo-label" data-target="form_bancolombia"
                               style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border:2px solid var(--gray-200);border-radius:var(--radius-md);cursor:pointer;transition:border-color .2s;">
                            <input type="radio" id="pago_bancolombia" name="metodo_pago" value="bancolombia_pse"
                                   {{ old('metodo_pago') === 'bancolombia_pse' ? 'checked' : '' }}
                                   style="accent-color:#fdba12;" class="metodo-radio">
                            <span style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:32px;height:32px;background:linear-gradient(135deg,#fdba12,#f59e0b);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-building-columns" style="color:#1a1a1a;font-size:.85rem;"></i>
                                </span>
                                <span style="font-size:.9rem;font-weight:600;color:var(--gray-900);">Bancolombia PSE</span>
                            </span>
                        </label>

                        {{-- Tarjeta --}}
                        <label for="pago_tarjeta" class="metodo-label" data-target="form_tarjeta"
                               style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border:2px solid var(--gray-200);border-radius:var(--radius-md);cursor:pointer;transition:border-color .2s;">
                            <input type="radio" id="pago_tarjeta" name="metodo_pago" value="tarjeta"
                                   {{ old('metodo_pago') === 'tarjeta' ? 'checked' : '' }}
                                   style="accent-color:#0ea5e9;" class="metodo-radio">
                            <span style="display:flex;align-items:center;gap:.5rem;">
                                <span style="width:32px;height:32px;background:linear-gradient(135deg,#0ea5e9,#2563eb);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-credit-card" style="color:#fff;font-size:.85rem;"></i>
                                </span>
                                <span style="font-size:.9rem;font-weight:600;color:var(--gray-900);">Tarjeta Crédito / Débito</span>
                            </span>
                        </label>
                    </div>

                    {{-- Formulario Nequi --}}
                    <div id="form_nequi" class="metodo-form"
                         style="display:none;margin-top:1rem;padding:1rem;background:#faf5ff;border:1.5px solid #ddd6fe;border-radius:var(--radius-md);">
                        <p style="font-size:.78rem;color:#6d28d9;font-weight:700;margin-bottom:.75rem;">
                            <i class="fa-solid fa-circle-info fa-xs"></i> Ingresa tu número de celular registrado en Nequi
                        </p>
                        <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">Número celular (10 dígitos)</label>
                        <input type="text" name="nequi_numero" maxlength="10" placeholder="3001234567"
                               value="{{ old('nequi_numero') }}"
                               style="width:100%;padding:.65rem .9rem;border:1.5px solid #ddd6fe;border-radius:var(--radius-md);font-size:.93rem;font-family:var(--font-body);background:#fff;">
                        <p style="font-size:.75rem;color:#7c3aed;margin-top:.4rem;">
                            🧪 <strong>Prueba:</strong> Usa <code>0000000000</code> para simular un pago fallido.
                        </p>
                    </div>

                    {{-- Formulario Bancolombia PSE --}}
                    <div id="form_bancolombia" class="metodo-form"
                         style="display:none;margin-top:1rem;padding:1rem;background:#fffbeb;border:1.5px solid #fde68a;border-radius:var(--radius-md);">
                        <p style="font-size:.78rem;color:#92400e;font-weight:700;margin-bottom:.75rem;">
                            <i class="fa-solid fa-circle-info fa-xs"></i> Serás redirigido al portal de Bancolombia (simulado)
                        </p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                            <div>
                                <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">Tipo de persona</label>
                                <select name="pse_tipo_persona"
                                        style="width:100%;padding:.65rem .9rem;border:1.5px solid #fde68a;border-radius:var(--radius-md);font-size:.88rem;font-family:var(--font-body);background:#fff;">
                                    <option value="natural">Natural</option>
                                    <option value="juridica">Jurídica</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">Tipo de cuenta</label>
                                <select name="pse_tipo_cuenta"
                                        style="width:100%;padding:.65rem .9rem;border:1.5px solid #fde68a;border-radius:var(--radius-md);font-size:.88rem;font-family:var(--font-body);background:#fff;">
                                    <option value="ahorros">Ahorros</option>
                                    <option value="corriente">Corriente</option>
                                </select>
                            </div>
                        </div>
                        <p style="font-size:.75rem;color:#b45309;margin-top:.75rem;">
                            🧪 <strong>Prueba:</strong> Cualquier selección aprobará el pago automáticamente.
                        </p>
                    </div>

                    {{-- Formulario Tarjeta --}}
                    <div id="form_tarjeta" class="metodo-form"
                         style="display:none;margin-top:1rem;padding:1rem;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:var(--radius-md);">
                        <p style="font-size:.78rem;color:#1d4ed8;font-weight:700;margin-bottom:.75rem;">
                            <i class="fa-solid fa-lock fa-xs"></i> Datos de tarjeta (entorno de pruebas)
                        </p>
                        <div style="display:flex;flex-direction:column;gap:.65rem;">
                            <div>
                                <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">Número de tarjeta</label>
                                <input type="text" name="tarjeta_numero" id="tarjeta_numero_input" maxlength="19"
                                       placeholder="4111 1111 1111 1111" value="{{ old('tarjeta_numero') }}"
                                       style="width:100%;padding:.65rem .9rem;border:1.5px solid #bfdbfe;border-radius:var(--radius-md);font-size:.93rem;font-family:var(--font-body);background:#fff;">
                            </div>
                            <div>
                                <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">Nombre del titular</label>
                                <input type="text" name="tarjeta_nombre" placeholder="Como aparece en la tarjeta"
                                       value="{{ old('tarjeta_nombre') }}"
                                       style="width:100%;padding:.65rem .9rem;border:1.5px solid #bfdbfe;border-radius:var(--radius-md);font-size:.93rem;font-family:var(--font-body);background:#fff;">
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;">
                                <div>
                                    <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">Vencimiento (MM/AA)</label>
                                    <input type="text" name="tarjeta_expiry" id="tarjeta_expiry_input" maxlength="5"
                                           placeholder="12/27" value="{{ old('tarjeta_expiry') }}"
                                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #bfdbfe;border-radius:var(--radius-md);font-size:.93rem;font-family:var(--font-body);background:#fff;">
                                </div>
                                <div>
                                    <label style="font-size:.82rem;font-weight:600;color:var(--gray-700);display:block;margin-bottom:.3rem;">CVV</label>
                                    <input type="password" name="tarjeta_cvv" maxlength="3" placeholder="•••"
                                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #bfdbfe;border-radius:var(--radius-md);font-size:.93rem;font-family:var(--font-body);background:#fff;">
                                </div>
                            </div>
                        </div>
                        <p style="font-size:.75rem;color:#1d4ed8;margin-top:.75rem;">
                            🧪 <strong>Prueba:</strong> Usa <code>4111 1111 1111 1111</code> · Fecha futura · CVV <code>123</code>
                        </p>
                    </div>
                </div>
                {{-- ══ FIN MÉTODOS DE PAGO ══ --}}

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fa-solid fa-lock"></i> Confirmar y Pagar
                </button>

                <p style="text-align:center;font-size:.75rem;color:var(--gray-400);margin-top:.75rem;">
                    <i class="fa-solid fa-shield-halved fa-xs"></i> Pago simulado · Solo con fines de demostración
                </p>
            </form>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Cálculo de precio (igual que antes) ──────────────────────────────
const precioPorNoche = {{ $hotel->precio }};
const inputEntrada   = document.getElementById('fecha_entrada');
const inputSalida    = document.getElementById('fecha_salida');
const precioBox      = document.getElementById('precio-calculado');
const precioTexto    = document.getElementById('precio-texto');

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

// ── Mostrar/ocultar formularios de pago ──────────────────────────────
const radios  = document.querySelectorAll('.metodo-radio');
const labels  = document.querySelectorAll('.metodo-label');
const forms   = document.querySelectorAll('.metodo-form');
const COLORES = { nequi: '#ddd6fe', bancolombia_pse: '#fde68a', tarjeta: '#bfdbfe' };

function togglePago() {
    const sel = document.querySelector('.metodo-radio:checked')?.value;
    forms.forEach(f  => f.style.display = 'none');
    labels.forEach(l => l.style.borderColor = 'var(--gray-200)');
    if (!sel) return;
    const key    = sel === 'bancolombia_pse' ? 'bancolombia' : sel;
    const target = document.getElementById('form_' + key);
    const active = document.querySelector(`[data-target="form_${key}"]`);
    if (target) target.style.display = 'block';
    if (active) active.style.borderColor = COLORES[sel] || 'var(--green-600)';
}

radios.forEach(r => r.addEventListener('change', togglePago));
if (document.querySelector('.metodo-radio:checked')) togglePago();

// ── Formateo automático número de tarjeta ────────────────────────────
const numInput = document.getElementById('tarjeta_numero_input');
if (numInput) {
    numInput.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').substring(0, 16);
        this.value = v.match(/.{1,4}/g)?.join(' ') || v;
    });
}

// ── Formateo automático vencimiento MM/AA ────────────────────────────
const expiryInput = document.getElementById('tarjeta_expiry_input');
if (expiryInput) {
    expiryInput.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').substring(0, 4);
        if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
        this.value = v;
    });
}
</script>
@endpush