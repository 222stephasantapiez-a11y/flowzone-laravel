@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>Eventos Culturales</h1>
        <p>Descubre los próximos eventos en Ortega, Tolima</p>
    </div>
</section>

<section class="container section">
    <div class="filters">
        <form method="GET" action="{{ route('eventos') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar eventos..." value="{{ $busqueda }}">
            <select name="categoria">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ $categoria_filtro === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('eventos') }}" class="btn btn-secondary">Limpiar</a>
        </form>
    </div>

    <div class="grid">
        @forelse($eventos as $evento)
            <div class="card animate-on-scroll">
                <img src="{{ $evento->imagen }}" alt="{{ $evento->nombre }}">
                <div class="card-content">
                    <h3>{{ $evento->nombre }}</h3>
                    <p class="categoria">{{ $evento->categoria }}</p>
                    <p class="fecha">📅 {{ $evento->fecha->format('d/m/Y') }}</p>
                    @if($evento->hora)
                        <p>🕐 {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}</p>
                    @endif
                    <p class="ubicacion">📍 {{ $evento->ubicacion }}</p>
                    @if($evento->precio > 0)
                        <p class="precio">💵 ${{ number_format($evento->precio, 0, ',', '.') }} COP</p>
                    @else
                        <p class="precio">✅ Entrada gratuita</p>
                    @endif
                    <p>{{ Str::limit($evento->descripcion, 120) }}</p>
                </div>
            </div>
        @empty
            <p class="no-results">No hay eventos próximos programados.</p>
        @endforelse
    </div>
</section>

@include('partials.footer')
