@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>Gastronomía Local</h1>
        <p>Descubre los sabores tradicionales de Ortega, Tolima</p>
    </div>
</section>

<section class="container section">
    <div class="filters">
        <form method="GET" action="{{ route('gastronomia') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar platos..." value="{{ $busqueda }}">
            <select name="tipo">
                <option value="">Todos los tipos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" {{ $tipo_filtro === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('gastronomia') }}" class="btn btn-secondary">Limpiar</a>
        </form>
    </div>

    <div class="grid">
        @forelse($platos as $plato)
            <div class="card animate-on-scroll">
                <img src="{{ $plato->imagen }}" alt="{{ $plato->nombre }}">
                <div class="card-content">
                    <h3>{{ $plato->nombre }}</h3>
                    <p class="categoria">{{ $plato->tipo }}</p>
                    <p class="precio">💵 ${{ number_format($plato->precio_promedio, 0, ',', '.') }} COP</p>
                    <p>{{ Str::limit($plato->descripcion, 120) }}</p>
                    <div class="restaurante-info">
                        <p><strong>🍽️ {{ $plato->restaurante }}</strong></p>
                        @if($plato->direccion)
                            <p class="ubicacion">📍 {{ $plato->direccion }}</p>
                        @endif
                        @if($plato->telefono)
                            <p>📱 {{ $plato->telefono }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="no-results">No se encontraron platos.</p>
        @endforelse
    </div>
</section>

@include('partials.footer')
