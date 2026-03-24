@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>🏢 Panel de Empresa</h1>
        <p>Gestiona tu información y envía solicitudes al administrador</p>
    </div>
</section>

<div class="container section">
    <div style="max-width:860px;margin:0 auto;">

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ $errors->first() }}</div>
        @endif

        @if(!$empresa)
            <div class="alert alert-error">
                No se encontraron datos de empresa asociados a tu cuenta.
                Contacta al administrador en <a href="{{ route('contacto') }}">esta página</a>.
            </div>
        @else

        {{-- Info empresa --}}
        <div style="background:#fff;border-radius:12px;padding:2rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,.07);">
            <h2 style="color:var(--primary);margin-bottom:1.2rem;font-size:1.3rem;">📋 Información registrada</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                <div style="background:#f8f8f6;border-radius:8px;padding:.9rem 1rem;">
                    <div style="font-size:.72rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Empresa</div>
                    <div style="font-weight:600;color:#1a1a1a;">{{ $empresa->nombre }}</div>
                </div>
                <div style="background:#f8f8f6;border-radius:8px;padding:.9rem 1rem;">
                    <div style="font-size:.72rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Teléfono</div>
                    <div style="font-weight:600;color:#1a1a1a;">{{ $empresa->telefono ?? '—' }}</div>
                </div>
                <div style="background:#f8f8f6;border-radius:8px;padding:.9rem 1rem;">
                    <div style="font-size:.72rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Dirección</div>
                    <div style="font-weight:600;color:#1a1a1a;">{{ $empresa->direccion ?? '—' }}</div>
                </div>
                <div style="background:#f8f8f6;border-radius:8px;padding:.9rem 1rem;">
                    <div style="font-size:.72rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.2rem;">Estado</div>
                    @if($empresa->aprobado)
                        <span style="background:#d4edda;color:#155724;padding:.3rem .8rem;border-radius:20px;font-size:.82rem;font-weight:600;">✓ Aprobada</span>
                    @else
                        <span style="background:#fff3cd;color:#856404;padding:.3rem .8rem;border-radius:20px;font-size:.82rem;font-weight:600;">⏳ Pendiente</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Formulario de solicitud --}}
        <div style="background:#fff;border-radius:12px;padding:2rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,.07);">
            <h2 style="color:var(--primary);margin-bottom:.5rem;font-size:1.3rem;">📨 Enviar solicitud al administrador</h2>
            <p style="color:#666;font-size:.9rem;margin-bottom:1.5rem;">
                Solicita agregar un nuevo servicio (hotel, restaurante), actualiza tus datos o reporta una novedad.
            </p>

            <form method="POST" action="{{ route('empresa.solicitud') }}">
                @csrf
                <div class="form-group">
                    <label for="tipo">Tipo de solicitud *</label>
                    <select id="tipo" name="tipo" required
                            style="width:100%;padding:.8rem;border:1px solid #ddd;border-radius:8px;font-size:.95rem;background:#fff;">
                        <option value="">— Selecciona —</option>
                        <option value="hotel"         {{ old('tipo') === 'hotel'         ? 'selected' : '' }}>🏨 Solicitar nuevo hotel</option>
                        <option value="restaurante"   {{ old('tipo') === 'restaurante'   ? 'selected' : '' }}>🍽️ Solicitar nuevo restaurante</option>
                        <option value="actualizacion" {{ old('tipo') === 'actualizacion' ? 'selected' : '' }}>✏️ Actualización de datos</option>
                        <option value="novedad"       {{ old('tipo') === 'novedad'       ? 'selected' : '' }}>📢 Novedad / Reporte</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" rows="5" required maxlength="1000"
                              style="width:100%;padding:.8rem;border:1px solid #ddd;border-radius:8px;font-family:inherit;font-size:.95rem;resize:vertical;"
                              placeholder="Describe detalladamente tu solicitud. Ej: Nombre del hotel, precio aproximado, ubicación, servicios...">{{ old('descripcion') }}</textarea>
                    <small style="color:#888;">Máximo 1000 caracteres.</small>
                </div>

                <button type="submit" class="btn btn-primary">Enviar solicitud</button>
            </form>
        </div>

        {{-- Historial --}}
        <div style="background:#fff;border-radius:12px;padding:2rem;box-shadow:0 2px 12px rgba(0,0,0,.07);">
            <h2 style="color:var(--primary);margin-bottom:1.2rem;font-size:1.3rem;">📂 Historial de solicitudes</h2>

            @if($historial->isEmpty())
                <p style="color:#888;text-align:center;padding:2rem;">No has enviado solicitudes aún.</p>
            @else
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Solicitud</th>
                                <th style="white-space:nowrap;">Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historial as $notif)
                            <tr>
                                <td style="max-width:500px;white-space:pre-wrap;font-size:.9rem;">{{ $notif->mensaje }}</td>
                                <td style="white-space:nowrap;color:#666;">{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($notif->leido)
                                        <span style="background:#d4edda;color:#155724;padding:.3rem .7rem;border-radius:20px;font-size:.8rem;">✓ Revisada</span>
                                    @else
                                        <span style="background:#fff3cd;color:#856404;padding:.3rem .7rem;border-radius:20px;font-size:.8rem;">⏳ Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @endif {{-- fin @if($empresa) --}}
    </div>
</div>

@include('partials.footer')
