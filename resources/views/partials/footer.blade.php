<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <h3><i></i> FlowZone</h3>
                <p>Descubre la belleza natural y cultural de Ortega, Tolima. Tu guía de turismo local para vivir experiencias únicas en el corazón del campo colombiano.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter/X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Explorar</h4>
                <ul>
                    <li><a href="{{ route('lugares') }}"><i class="fa-solid fa-map-pin fa-xs"></i> Lugares Turísticos</a></li>
                    <li><a href="{{ route('hoteles') }}"><i class="fa-solid fa-hotel fa-xs"></i> Hoteles</a></li>
                    <li><a href="{{ route('eventos') }}"><i class="fa-solid fa-calendar fa-xs"></i> Eventos</a></li>
                    <li><a href="{{ route('gastronomia') }}"><i class="fa-solid fa-utensils fa-xs"></i> Gastronomía</a></li>
                    <li><a href="{{ route('blog') }}"><i class="fa-solid fa-newspaper fa-xs"></i> Blog</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Servicios</h4>
                <ul>
                    <li><a href="{{ route('mis-reservas') }}"><i class="fa-solid fa-calendar-check fa-xs"></i> Mis Reservas</a></li>
                    @auth
                    <li><a href="{{ route('favoritos') }}"><i class="fa-solid fa-heart fa-xs"></i> Favoritos</a></li>
                    @endauth
                    <li><a href="{{ route('contacto') }}"><i class="fa-solid fa-envelope fa-xs"></i> Contacto</a></li>
                    @guest
                    <li><a href="{{ route('registro') }}"><i class="fa-solid fa-user-plus fa-xs"></i> Registrarse</a></li>
                    @endguest
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contacto</h4>
                <ul>
                    <li><a href="mailto:info@flowzone.com"><i class="fa-solid fa-envelope fa-xs"></i> info@flowzone.com</a></li>
                    <li><a href="tel:+573201234567"><i class="fa-solid fa-phone fa-xs"></i> +57 320 123 4567</a></li>
                    <li><a href="#"><i class="fa-solid fa-location-dot fa-xs"></i> Ortega, Tolima, Colombia</a></li>
                    <li><a href="#"><i class="fa-solid fa-clock fa-xs"></i> Lun–Vie 8am–6pm</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} FlowZone. Todos los derechos reservados.</p>
            <p>Hecho con <i class="fa-solid fa-heart" style="color:#ef4444;"></i> en Ortega, Tolima</p>
        </div>
    </div>
</footer>
