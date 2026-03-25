@extends('layouts.app')

@section('title', 'Contacto')
@section('body-class', 'no-hero')

@section('content')

{{-- Page hero mini --}}
<section style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);padding:5rem 0 3rem;margin-top:var(--navbar-height);">
    <div class="container" style="text-align:center;">
        <span style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:var(--green-200);font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.4rem 1rem;border-radius:var(--radius-full);margin-bottom:1rem;">
            <i class="fa-solid fa-envelope fa-xs"></i> Contacto
        </span>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.6rem,4vw,2.5rem);font-weight:900;color:#fff;margin-bottom:.5rem;">
            Hablemos
        </h1>
        <p style="color:rgba(255,255,255,.7);font-size:.95rem;">Estamos en Ortega, Tolima. Con gusto te ayudamos.</p>
    </div>
</section>

<section class="container section">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.5rem;max-width:1000px;margin:0 auto;align-items:start;">

        {{-- Info de contacto --}}
        <div>
            <div class="admin-section" style="margin-bottom:1.5rem;">
                <h2 style="font-family:var(--font-display);font-size:1.3rem;font-weight:800;color:var(--green-800);margin-bottom:1.25rem;">
                    Informacion de contacto
                </h2>
                <div style="display:flex;flex-direction:column;gap:1.25rem;">
                    <div style="display:flex;gap:.85rem;align-items:flex-start;">
                        <div style="width:40px;height:40px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-location-dot" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.8rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Direccion</p>
                            <p style="font-size:.95rem;color:var(--gray-900);">Ortega, Tolima, Colombia</p>
                        </div>
                    </div>
                    <div style="display:flex;gap:.85rem;align-items:flex-start;">
                        <div style="width:40px;height:40px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-phone" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.8rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Telefono</p>
                            <p style="font-size:.95rem;color:var(--gray-900);">+57 (8) 000-0000</p>
                        </div>
                    </div>
                    <div style="display:flex;gap:.85rem;align-items:flex-start;">
                        <div style="width:40px;height:40px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-envelope" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.8rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Email</p>
                            <p style="font-size:.95rem;color:var(--gray-900);">info@flowzone.co</p>
                        </div>
                    </div>
                    <div style="display:flex;gap:.85rem;align-items:flex-start;">
                        <div style="width:40px;height:40px;background:var(--green-50);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid fa-clock" style="color:var(--green-700);"></i>
                        </div>
                        <div>
                            <p style="font-size:.8rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Horario de atencion</p>
                            <p style="font-size:.95rem;color:var(--gray-900);">Lun – Vie: 8:00 am – 6:00 pm</p>
                            <p style="font-size:.88rem;color:var(--gray-400);">Sab: 9:00 am – 2:00 pm</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mapa --}}
            <div class="admin-section">
                <h3 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:1rem;">
                    <i class="fa-solid fa-map fa-xs" style="color:var(--green-600);margin-right:.4rem;"></i>Ubicacion
                </h3>
                <div style="border-radius:var(--radius-md);overflow:hidden;">
                    <iframe
                        src="https://www.google.com/maps?q=4.1833,-75.2167&output=embed"
                        width="100%" height="240" frameborder="0" style="border:0;display:block;"
                        allowfullscreen loading="lazy" title="Mapa Ortega Tolima"></iframe>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <div class="admin-section" style="border-top:4px solid var(--green-700);">
            <h2 style="font-size:1.15rem;font-weight:700;color:var(--gray-900);margin-bottom:1.5rem;">
                <i class="fa-solid fa-paper-plane fa-xs" style="color:var(--green-600);margin-right:.4rem;"></i>
                Envianos un mensaje
            </h2>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1.25rem;">{{ session('success') }}</div>
            @endif

            <form method="POST" action="#">
                @csrf
                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="nombre" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Nombre completo
                    </label>
                    <input type="text" id="nombre" name="nombre" required
                           placeholder="Tu nombre"
                           value="{{ old('nombre') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;transition:border-color .2s;">
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="email" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Correo electronico
                    </label>
                    <input type="email" id="email" name="email" required
                           placeholder="tu@email.com"
                           value="{{ old('email') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;transition:border-color .2s;">
                </div>

                <div class="form-group" style="margin-bottom:1.25rem;">
                    <label for="asunto" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Asunto
                    </label>
                    <input type="text" id="asunto" name="asunto"
                           placeholder="Motivo de tu mensaje"
                           value="{{ old('asunto') }}"
                           style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;transition:border-color .2s;">
                </div>

                <div class="form-group" style="margin-bottom:1.75rem;">
                    <label for="mensaje" style="display:block;font-size:.85rem;font-weight:600;color:var(--gray-900);margin-bottom:.4rem;">
                        Mensaje
                    </label>
                    <textarea id="mensaje" name="mensaje" required rows="5"
                              placeholder="Escribe tu mensaje aqui..."
                              style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.95rem;font-family:var(--font-body);color:var(--gray-900);background:#fff;resize:vertical;transition:border-color .2s;">{{ old('mensaje') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fa-solid fa-paper-plane fa-xs"></i> Enviar mensaje
                </button>
            </form>
        </div>

    </div>
</section>

@endsection
