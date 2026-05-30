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
            <li><a href="{{ route('planes.publico') }}" class="{{ request()->routeIs('planes.publico') ? 'active' : '' }}">Planes</a></li>
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

                {{-- Notificaciones (solo para usuarios con rol usuario) --}}
                @if(auth()->user()->rol === 'usuario')
                @php $notifCount = auth()->user()->unreadNotifications->count(); @endphp
                <div style="position:relative;display:inline-block;" id="notif-wrapper">
                    <button onclick="toggleNotif()" class="btn btn-glass btn-sm" style="position:relative;" title="Notificaciones">
                        <i class="fa-solid fa-bell"></i>
                        @if($notifCount > 0)
                        <span style="position:absolute;top:-5px;right:-5px;background:#ef4444;color:#fff;font-size:.6rem;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;line-height:1;">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                        @endif
                    </button>

                    {{-- Dropdown notificaciones --}}
                    <div id="notif-dropdown" style="display:none;position:absolute;right:0;top:calc(100% + .5rem);width:320px;background:#fff;border-radius:var(--radius-lg);box-shadow:0 8px 32px rgba(0,0,0,.15);border:1px solid var(--gray-100);z-index:9999;overflow:hidden;">
                        <div style="padding:.85rem 1rem;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-weight:700;font-size:.88rem;color:var(--gray-900);">
                                <i class="fa-solid fa-bell fa-xs" style="color:var(--green-600);"></i> Notificaciones
                            </span>
                            @if($notifCount > 0)
                            <form method="POST" action="{{ route('usuario.notificaciones.leer-todas') }}" style="margin:0;">
                                @csrf
                                <button type="submit" style="background:none;border:none;font-size:.75rem;color:var(--green-700);cursor:pointer;font-weight:600;">
                                    Marcar todas leídas
                                </button>
                            </form>
                            @endif
                        </div>
                        <div style="max-height:320px;overflow-y:auto;">
                            @forelse(auth()->user()->notifications->take(10) as $notif)
                            <div style="padding:.85rem 1rem;border-bottom:1px solid var(--gray-50);background:{{ $notif->read_at ? '#fff' : '#f0fdf4' }};display:flex;gap:.75rem;align-items:flex-start;">
                                <div style="flex-shrink:0;width:32px;height:32px;border-radius:50%;background:{{ ($notif->data['tipo'] ?? '') === 'reserva_confirmada' ? '#dcfce7' : '#fee2e2' }};display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-{{ ($notif->data['tipo'] ?? '') === 'reserva_confirmada' ? 'check' : 'ban' }}" style="font-size:.75rem;color:{{ ($notif->data['tipo'] ?? '') === 'reserva_confirmada' ? '#16a34a' : '#ef4444' }};"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <p style="margin:0;font-size:.82rem;color:var(--gray-800);line-height:1.4;">{{ $notif->data['mensaje'] ?? '' }}</p>
                                    @if(isset($notif->data['fecha_entrada']))
                                    <p style="margin:.2rem 0 0;font-size:.72rem;color:var(--gray-400);">
                                        {{ $notif->data['fecha_entrada'] }} → {{ $notif->data['fecha_salida'] ?? '' }}
                                    </p>
                                    @endif
                                    <p style="margin:.2rem 0 0;font-size:.7rem;color:var(--gray-400);">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                                @if(!$notif->read_at)
                                <form method="POST" action="{{ route('usuario.notificaciones.leer', $notif->id) }}" style="margin:0;flex-shrink:0;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--gray-400);font-size:.7rem;" title="Marcar leída">
                                        <i class="fa-solid fa-circle-dot" style="color:var(--green-500);"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @empty
                            <div style="padding:1.5rem;text-align:center;color:var(--gray-400);font-size:.85rem;">
                                <i class="fa-solid fa-bell-slash" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                                Sin notificaciones
                            </div>
                            @endforelse
                        </div>
                        <div style="padding:.65rem 1rem;border-top:1px solid var(--gray-100);text-align:center;">
                            <a href="{{ route('mis-reservas') }}" style="font-size:.78rem;color:var(--green-700);font-weight:600;text-decoration:none;">
                                Ver mis reservas →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-glass btn-sm" title="Mi Panel">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                @elseif(auth()->user()->isEmpresa())
                    <a href="{{ route('empresa.dashboard') }}" class="btn btn-glass btn-sm" title="Mi Panel">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                @else
                    <a href="{{ route('usuario.dashboard') }}" class="btn btn-glass btn-sm" title="Mi Cuenta">
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

<script>
function toggleNotif() {
    const d = document.getElementById('notif-dropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        const d = document.getElementById('notif-dropdown');
        if (d) d.style.display = 'none';
    }
});
</script>