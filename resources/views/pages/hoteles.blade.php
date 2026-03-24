@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>Hoteles en Ortega</h1>
        <p>Encuentra el alojamiento perfecto para tu estadía</p>
    </div>
</section>

<section class="container section">

    <div class="filters">
        <form method="GET" action="{{ route('hoteles') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar hoteles..."
                   value="{{ $busqueda }}">
            <input type="number" name="precio_max" placeholder="Precio máximo COP"
                   value="{{ $precio_max }}">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('hoteles') }}" class="btn btn-secondary">Limpiar</a>
        </form>
    </div>

    <div class="grid">
        @forelse($hoteles as $hotel)
            <div class="card animate-on-scroll">
                @if($hotel->imagen)
                    <img src="{{ $hotel->imagen }}"
                         alt="{{ $hotel->nombre }}"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80'">
                @else
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&q=80"
                         alt="{{ $hotel->nombre }}">
                @endif

                <div class="card-content">
                    <h3>{{ $hotel->nombre }}</h3>
                    <p class="precio">${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche</p>

                    @if($hotel->ubicacion)
                        <p class="ubicacion">{{ $hotel->ubicacion }}</p>
                    @endif

                    @if($hotel->capacidad)
                        <p style="font-size:.85rem;color:var(--gray);margin:.2rem 0;">
                            Capacidad: {{ $hotel->capacidad }} personas
                        </p>
                    @endif

                    <p style="margin:.6rem 0;font-size:.9rem;color:var(--dark-soft);">
                        {{ Str::limit($hotel->descripcion, 110) }}
                    </p>

                    <div class="card-actions">
                        <a href="{{ route('hoteles.detalle', $hotel) }}"
                           class="btn btn-secondary">Ver Detalles</a>

                        @auth
                            <a href="{{ route('reservar', ['hotel_id' => $hotel->id]) }}"
                               class="btn btn-primary">Reservar</a>
                        @else
                            <a href="{{ route('login') }}"
                               class="btn btn-primary"
                               title="Inicia sesión para reservar">Reservar</a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1;">
                <p class="no-results">No se encontraron hoteles disponibles.</p>
            </div>
        @endforelse
    </div>

</section>

@include('partials.footer')
