@include('partials.header')

<section class="page-header">
    <div class="container">
        <h1>Contacto</h1>
        <p>¿Tienes preguntas? Estamos aquí para ayudarte</p>
    </div>
</section>

<section class="container section">
    <div class="contacto-container">
        <div class="contacto-info">
            <h2>Información de Contacto</h2>
            <div class="info-item"><span>📧</span><div><strong>Email</strong><p>info@flowzone.com</p></div></div>
            <div class="info-item"><span>📱</span><div><strong>Teléfono</strong><p>+57 320 123 4567</p></div></div>
            <div class="info-item"><span>📍</span><div><strong>Ubicación</strong><p>Ortega, Tolima, Colombia</p></div></div>
        </div>
        <div class="contacto-form-card">
            <h2>Envíanos un Mensaje</h2>
            <form>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Mensaje</label>
                    <textarea name="mensaje" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
            </form>
        </div>
    </div>
</section>

@include('partials.footer')
