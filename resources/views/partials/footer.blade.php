</main>
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>FlowZone</h3>
                <p>Descubre la belleza de Ortega, Tolima</p>
            </div>
            <div class="footer-section">
                <h4>Enlaces</h4>
                <ul>
                    <li><a href="{{ route('lugares') }}">Lugares Turísticos</a></li>
                    <li><a href="{{ route('hoteles') }}">Hoteles</a></li>
                    <li><a href="{{ route('eventos') }}">Eventos</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contacto</h4>
                <p>📧 info@flowzone.com</p>
                <p>📱 +57 320 123 4567</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} FlowZone. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/animaciones.js') }}"></script>
</body>
</html>
