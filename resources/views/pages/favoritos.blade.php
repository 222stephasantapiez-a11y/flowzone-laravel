@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>❤️ Mis Favoritos</h1>
        <p>Lugares y hoteles que has guardado</p>
    </div>
</section>

<section class="container section">
    <div class="empty-state">
        <p>La sección de favoritos estará disponible próximamente.</p>
        <a href="{{ route('lugares') }}" class="btn btn-primary" style="margin-top:1rem;">Explorar Lugares</a>
    </div>
</section>

@include('partials.footer')
