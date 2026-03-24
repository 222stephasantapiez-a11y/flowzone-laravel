@include('partials.header')

<section class="hero">
    <div class="hero-content">
        <h1 class="fade-in">Descubre Ortega, Tolima</h1>
        <p class="fade-in">Un paraíso natural en el corazón de Colombia</p>
        <a href="{{ route('lugares') }}" class="btn btn-primary fade-in">Explorar Lugares</a>
    </div>
</section>

<section class="container section">
    <h2 class="section-title">Lugares Destacados</h2>
    <div class="grid">
        @forelse($lugares_destacados as $lugar)
            <div class="card animate-on-scroll">
                <img src="{{ $lugar->imagen }}" alt="{{ $lugar->nombre }}">
                <div class="card-content">
                    <h3>{{ $lugar->nombre }}</h3>
                    <p class="categoria">{{ $lugar->categoria }}</p>
                    <p>{{ Str::limit($lugar->descripcion, 100) }}</p>
                    <a href="{{ route('lugares.detalle', $lugar) }}" class="btn btn-secondary">Ver Detalles</a>
                </div>
            </div>
        @empty
            <p class="no-results">No hay lugares disponibles.</p>
        @endforelse
    </div>
</section>

<section class="container section bg-light">
    <h2 class="section-title">Hoteles Recomendados</h2>
    <div class="grid">
        @forelse($hoteles_destacados as $hotel)
            <div class="card animate-on-scroll">
                <img src="{{ $hotel->imagen }}" alt="{{ $hotel->nombre }}">
                <div class="card-content">
                    <h3>{{ $hotel->nombre }}</h3>
                    <p class="precio">${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche</p>
                    <p>{{ Str::limit($hotel->descripcion, 100) }}</p>
                    <div class="card-actions">
                        <a href="{{ route('hoteles.detalle', $hotel) }}" class="btn btn-secondary">Ver Detalles</a>
                        @auth
                            <a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}" class="btn btn-primary">🛒 Reservar</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">🔒 Reservar</a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <p class="no-results">No hay hoteles disponibles.</p>
        @endforelse
    </div>
</section>

<section class="container section">
    <h2 class="section-title">Próximos Eventos</h2>
    <div class="grid">
        @forelse($eventos_proximos as $evento)
            <div class="card animate-on-scroll">
                <img src="{{ $evento->imagen }}" alt="{{ $evento->nombre }}">
                <div class="card-content">
                    <h3>{{ $evento->nombre }}</h3>
                    <p class="fecha">📅 {{ $evento->fecha->format('d/m/Y') }}</p>
                    <p>{{ Str::limit($evento->descripcion, 100) }}</p>
                    <a href="{{ route('eventos') }}" class="btn btn-secondary">Ver Más</a>
                </div>
            </div>
        @empty
            <p class="no-results">No hay eventos próximos.</p>
        @endforelse
    </div>
</section>

@include('partials.footer')
