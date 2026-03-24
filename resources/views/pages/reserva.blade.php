@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>Reservar Hotel</h1>
        <p>{{ $hotel->nombre }}</p>
    </div>
</section>

<section class="container section">
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="reserva-container">
        <div class="hotel-info-card">
            <img src="{{ $hotel->imagen }}" alt="{{ $hotel->nombre }}">
            <h3>{{ $hotel->nombre }}</h3>
            <p class="precio">${{ number_format($hotel->precio, 0, ',', '.') }} COP / noche</p>
            <p>📍 {{ $hotel->ubicacion }}</p>
            <p>👥 Capacidad: {{ $hotel->capacidad }} personas</p>
        </div>

        <div class="reserva-form-card">
            <h2>Datos de la Reserva</h2>

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('reservar.store') }}">
                @csrf
                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                <div class="form-group">
                    <label for="fecha_entrada">Fecha de Entrada</label>
                    <input type="date" id="fecha_entrada" name="fecha_entrada" required
                           min="{{ date('Y-m-d') }}" value="{{ old('fecha_entrada') }}">
                </div>

                <div class="form-group">
                    <label for="fecha_salida">Fecha de Salida</label>
                    <input type="date" id="fecha_salida" name="fecha_salida" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('fecha_salida') }}">
                </div>

                <div class="form-group">
                    <label for="num_personas">Número de Personas</label>
                    <input type="number" id="num_personas" name="num_personas" required
                           min="1" max="{{ $hotel->capacidad }}" value="{{ old('num_personas', 1) }}">
                </div>

                <div id="precio-calculado" class="precio-calculado"></div>

                <button type="submit" class="btn btn-primary btn-block">Confirmar Reserva</button>
            </form>
        </div>
    </div>
</section>

<script>
const precioPorNoche = {{ $hotel->precio }};
document.getElementById('fecha_entrada').addEventListener('change', calcular);
document.getElementById('fecha_salida').addEventListener('change', calcular);
function calcular() {
    const entrada = document.getElementById('fecha_entrada').value;
    const salida  = document.getElementById('fecha_salida').value;
    if (entrada && salida) {
        const dias = (new Date(salida) - new Date(entrada)) / 86400000;
        if (dias > 0) {
            const total = dias * precioPorNoche;
            document.getElementById('precio-calculado').innerHTML =
                `<strong>Total: $${total.toLocaleString('es-CO')} COP</strong> (${dias} noche${dias > 1 ? 's' : ''})`;
        }
    }
}
</script>

@include('partials.footer')
