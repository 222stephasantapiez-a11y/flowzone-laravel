<section class="detalle-header" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('');">
    <div class="container">
        <h1></h1>
        <p class="precio-grande">$ COP / noche</p>
    </div>
</section>

<section class="container section">
    <div class="detalle-content">
        <div class="detalle-main">
            <div class="detalle-info">
                <h2>Descripción</h2>
                <p></p>

                <h3 style="margin-top:1.5rem;">Servicios</h3>
                <ul class="servicios-list">
                        <li>✓ </li>
                </ul>

                <div class="info-grid">
                    <div class="info-item">
                        <strong>📍 Ubicación:</strong>
                        <p></p>
                    </div>
                    <div class="info-item">
                        <strong>👥 Capacidad:</strong>
                        <p> personas</p>
                    </div>
                 
                    <div class="info-item">
                        <strong>📱 Teléfono:</strong>
                        <p></p>
                    </div>
               
                  
                    <div class="info-item">
                        <strong>📧 Email:</strong>
                        <p></p>
                    </div>
                </div>
                <div class="mapa">
                    <h3>Ubicación en el mapa</h3>
                    <iframe width="100%" height="400" frameborder="0" style="border:0"
                        src="https://www.google.com/maps?q=&output=embed"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <div class="detalle-sidebar">
            <!-- Calificación -->
            <div class="calificacion-box">
                <h3>Calificación</h3>
                <div class="rating-display">
                    <span class="rating-number"></span>
                    <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                    <span class="rating-count">( valoraciones)</span>
                </div>

                <div class="rating-form">
                    <p>Tu calificación:</p>
                    <div class="stars" data-tipo="hotel" data-id="">
                        <span class="star" data-value="1">⭐</span>
                        <span class="star" data-value="2">⭐</span>
                        <span class="star" data-value="3">⭐</span>
                        <span class="star" data-value="4">⭐</span>
                        <span class="star" data-value="5">⭐</span>
                    </div>
                </div>
            </div>

            <!-- Reservar -->
            <div class="reserva-box">
                <h3>Reservar ahora</h3>
                <p class="precio-destacado">$ COP</p>
                <p style="font-size:0.85rem;color:var(--gray);margin-bottom:1rem;">por noche</p>

                    <a href="reservarphp?hotel_id="
                       class="btn btn-primary btn-block">
                        🛒 Hacer Reserva
                    </a>
                    <a href="mis_reservasphp"
                       class="btn btn-secondary btn-block"
                       style="margin-top:0.5rem;text-align:center;">
                        📋 Ver mis reservas
                    </a>
                    <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:1rem;text-align:center;margin-bottom:1rem;">
                        <p style="font-size:0.9rem;color:#795548;margin-bottom:0.8rem;">
                            🔒 Inicia sesión para reservar
                        </p>
                        <a href="loginphp" class="btn btn-primary btn-block">
                            Iniciar Sesión
                        </a>
                        <p style="font-size:0.8rem;color:var(--gray);margin-top:0.5rem;">
                            ¿No tienes cuenta? <a href="/registrophp">Regístrate gratis</a>
                        </p>
                    </div>
             
            </div>

            <!-- Favorito -->

            <button class="btn btn-favorito 
                    data-tipo="hotel data-id="">
             
            </button>
        </div>
    </div>
</section>
