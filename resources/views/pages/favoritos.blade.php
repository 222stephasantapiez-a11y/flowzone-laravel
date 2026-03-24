<section class="page-header">
    <div class="container">
        <h1>❤️ Mis Favoritos</h1>
        <p>Lugares y hoteles que has guardado</p>
    </div>
</section>

<section class="container section">
        <div class="empty-state">
            <p>No tienes favoritos guardados aún.</p>
            <a href="lugaresphp" class="btn btn-primary">Explorar Lugares</a>
        </div>
            <h2 class="section-title">Lugares Favoritos</h2>
            <div class="grid">
                    <div class="card animate-on-scroll">
                        <img src="" alt="">
                        <div class="card-content">
                            <h3></h3>
                            <p class="categoria"></p>
                            <p class="ubicacion">📍 </p>
                            <p>...</p>
                            <div class="card-actions">
                                <a href="detalle_lugarphp?id=" class="btn btn-secondary">Ver Detalles</a>
                                <button class="btn btn-favorito active" data-tipo="lugar" data-id="">
                                    ❤️ Quitar
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
        
            <h2 class="section-title">Hoteles Favoritos</h2>
            <div class="grid">
                    <div class="card animate-on-scroll">
                        <img src="" alt="">
                        <div class="card-content">
                            <h3><h3>
                            <p class="precio">$ COP / noche</p>
                            <p class="ubicacion">📍 <p>
                            <p>...</p>
                            <div class="card-actions">
                                <a href="detalle_hotelphp?id=" class="btn btn-secondary">Ver Detalles</a>
                                <button class="btn btn-favorito active" data-tipo="hotel" data-id="">
                                    ❤️ Quitar
                                </button>
                            </div>
                        </div>
                    </div>

            </div>
</section>