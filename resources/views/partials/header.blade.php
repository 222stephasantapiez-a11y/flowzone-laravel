<nav class="navbar" id="navbar">
    <div class="container navbar-inner">
        <a class="nav-brand" href="{{ route('home') }}">
            <i class=></i> FlowZone

        </a>

        <ul class="nav-menu" id="navMenu">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
            <li><a href="{{ route('lugares') }}" class="{{ request()->routeIs('lugares*') ? 'active' : '' }}">Lugares</a></li>
            <li><a href="{{ route('hoteles') }}" class="{{ request()->routeIs('hoteles*') ? 'active' : '' }}">Hoteles</a></li>
            <li><a href="{{ route('eventos') }}" class="{{ request()->routeIs('eventos') ? 'active' : '' }}">Eventos</a></li>
            <li><a href="{{ route('gastronomia') }}" class="{{ request()->routeIs('gastronomia') ? 'active' : '' }}">Gastronomía</a></li>
            <li><a href="{{ route('blog') }}" class="{{ request()->routeIs('blog*') ? 'active' : '' }}">Blog</a></li>
            <li><a href="{{ route('maps') }}" class="{{ request()->routeIs('maps*') ? 'active' : '' }}">
                <i class="fa-solid fa-map-location-dot fa-xs"></i> Maps
            </a></li>
            @if(!auth()->check() || auth()->user()->rol === 'empresa')
                <li><a href="{{ route('contacto') }}" class="{{ request()->routeIs('contacto') ? 'active' : '' }}">Contacto</a></li>
            @endif
        </ul>

        <div class="nav-actions">
            @auth
                @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-glass btn-sm">
                        <i class="fa-solid fa-shield-halved"></i> Admin
                    </a>
                @elseif(auth()->user()->rol === 'empresa')
                    <a href="{{ route('empresa.dashboard') }}" class="btn btn-glass btn-sm">
                        <i class="fa-solid fa-building"></i> Mi Empresa
                    </a>
                @endif
                <a href="{{ route('favoritos') }}" class="btn btn-glass btn-sm">
                    <i class="fa-solid fa-heart"></i>
                </a>
            @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-glass btn-sm" title="Mi Panel">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                @elseif(auth()->user()->isEmpresa())
                    <a href="{{ route('empresa.dashboard') }}" class="btn btn-glass btn-sm" title="Mi Panel">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                @else
                    <a href="{{ route('mis-reservas') }}" class="btn btn-glass btn-sm" title="Mis Reservas">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn btn-white btn-sm">
                        <i class="fa-solid fa-right-from-bracket"></i> Salir
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-glass btn-sm">
                    <i class="fa-solid fa-right-to-bracket"></i> Ingresar
                </a>
                <a href="{{ route('registro') }}" class="btn btn-white btn-sm">
                    Registrarse
                </a>
            @endauth
        </div>

      <button class="dark-mode-toggle" id="darkToggle" title="Modo oscuro" aria-label="Cambiar tema">
    <i class="fa-solid fa-moon" id="darkIcon"></i>
</button>

        <button class="nav-hamburger" id="navToggle" aria-label="Abrir menú">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
