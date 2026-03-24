@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>Lugares Turísticos</h1>
        <p>Descubre los mejores destinos de Ortega, Tolima</p>
    </div>
</section>

<section class="container section">
    <div class="filters">
        <form method="GET" action="{{ route('lugares') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar lugares..." value="{{ $busqueda }}">
            <select name="categoria">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ $categoria_filtro === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('lugares') }}" class="btn btn-secondary">Limpiar</a>
        </form>
    </div>

    <div class="grid">
        @forelse($lugares as $lugar)
            <div class="card animate-on-scroll">
                <img src="{{ $lugar->imagen }}" alt="{{ $lugar->nombre }}">
                <div class="card-content">
                    <h3>{{ $lugar->nombre }}</h3>
                    <p class="categoria">{{ $lugar->categoria }}</p>
                    <p class="ubicacion">📍 {{ $lugar->ubicacion }}</p>
                    @if($lugar->precio_entrada > 0)
                        <p class="precio">💵 ${{ number_format($lugar->precio_entrada, 0, ',', '.') }} COP</p>
                    @else
                        <p class="precio">✅ Entrada gratuita</p>
                    @endif
                    <p>{{ Str::limit($lugar->descripcion, 120) }}</p>
                    <a href="{{ route('lugares.detalle', $lugar) }}" class="btn btn-secondary">Ver Detalles</a>
                </div>
            </div>
        @empty
            <p class="no-results">No se encontraron lugares.</p>
        @endforelse
    </div>
</section>

@include('partials.footer')
