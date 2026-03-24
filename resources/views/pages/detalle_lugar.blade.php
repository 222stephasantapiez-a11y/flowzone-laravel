@include('partials.header')

<section class="detalle-header" style="background-image: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ $lugar->imagen }}');">
    <div class="container">
        <h1>{{ $lugar->nombre }}</h1>
        <p class="categoria">{{ $lugar->categoria }}</p>
    </div>
</section>

<section class="container section">
    <div class="detalle-content">
        <div class="detalle-main">
            <div class="detalle-info">
                <h2>Descripción</h2>
                <p>{{ $lugar->descripcion }}</p>

                <div class="info-grid">
                    <div class="info-item"><strong>📍 Ubicación:</strong><p>{{ $lugar->ubicacion }}</p></div>
                    @if($lugar->horario)
                        <div class="info-item"><strong>🕐 Horario:</strong><p>{{ $lugar->horario }}</p></div>
                    @endif
                    <div class="info-item">
                        <strong>💵 Entrada:</strong>
                        <p>{{ $lugar->precio_entrada > 0 ? '$' . number_format($lugar->precio_entrada, 0, ',', '.') . ' COP' : 'Gratuita' }}</p>
                    </div>
                </div>

                @if($lugar->latitud && $lugar->longitud)
                    <div class="mapa">
                        <h3>Ubicación en el mapa</h3>
                        <iframe width="100%" height="400" frameborder="0" style="border:0"
                            src="https://www.google.com/maps?q={{ $lugar->latitud }},{{ $lugar->longitud }}&output=embed"
                            allowfullscreen></iframe>
                    </div>
                @endif
            </div>
        </div>

        <div class="detalle-sidebar">
            @auth
                <button class="btn btn-favorito {{ $es_favorito ? 'active' : '' }}"
                        data-tipo="lugar" data-id="{{ $lugar->id }}">
                    {{ $es_favorito ? '❤️ En Favoritos' : '🤍 Agregar a Favoritos' }}
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-block">🔒 Inicia sesión</a>
            @endauth
        </div>
    </div>
</section>

@include('partials.footer')
