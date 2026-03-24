@include('partials.header')

<section class="detalle-header" style="background-image: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ $hotel->imagen }}');">
    <div class="container">
        <h1>{{ $hotel->nombre }}</h1>
        <p class="precio-grande">${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche</p>
    </div>
</section>

<section class="container section">
    <div class="detalle-content">
        <div class="detalle-main">
            <div class="detalle-info">
                <h2>Descripción</h2>
                <p>{{ $hotel->descripcion }}</p>

                @if($hotel->servicios)
                    <h3 style="margin-top:1.5rem;">Servicios</h3>
                    <ul class="servicios-list">
                        @foreach(explode(',', $hotel->servicios) as $servicio)
                            <li>✓ {{ trim($servicio) }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="info-grid">
                    <div class="info-item"><strong>📍 Ubicación:</strong><p>{{ $hotel->ubicacion }}</p></div>
                    <div class="info-item"><strong>👥 Capacidad:</strong><p>{{ $hotel->capacidad }} personas</p></div>
                    @if($hotel->telefono)
                        <div class="info-item"><strong>📱 Teléfono:</strong><p>{{ $hotel->telefono }}</p></div>
                    @endif
                    @if($hotel->email)
                        <div class="info-item"><strong>📧 Email:</strong><p>{{ $hotel->email }}</p></div>
                    @endif
                </div>

                @if($hotel->latitud && $hotel->longitud)
                    <div class="mapa">
                        <h3>Ubicación en el mapa</h3>
                        <iframe width="100%" height="400" frameborder="0" style="border:0"
                            src="https://www.google.com/maps?q={{ $hotel->latitud }},{{ $hotel->longitud }}&output=embed"
                            allowfullscreen></iframe>
                    </div>
                @endif
            </div>
        </div>

        <div class="detalle-sidebar">
            <div class="reserva-box">
                <h3>Reservar ahora</h3>
                <p class="precio-destacado">${{ number_format($hotel->precio, 0, ',', '.') }} COP</p>
                <p style="font-size:.85rem;color:var(--gray);margin-bottom:1rem;">por noche</p>

                @auth
                    <a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}" class="btn btn-primary btn-block">🛒 Hacer Reserva</a>
                    <a href="{{ route('mis-reservas') }}" class="btn btn-secondary btn-block" style="margin-top:.5rem;text-align:center;">📋 Ver mis reservas</a>
                @else
                    <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:1rem;text-align:center;margin-bottom:1rem;">
                        <p style="font-size:.9rem;color:#795548;margin-bottom:.8rem;">🔒 Inicia sesión para reservar</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block">Iniciar Sesión</a>
                        <p style="font-size:.8rem;color:var(--gray);margin-top:.5rem;">
                            ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate gratis</a>
                        </p>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
