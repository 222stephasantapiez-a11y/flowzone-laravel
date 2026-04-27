<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — FlowZone</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    @stack('head')
</head>
<body>
<div class="admin-layout">

    {{-- ── Sidebar ── --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <div class="admin-brand-logo">
                <div class=""><i></i></div>
                <h2>FlowZone</h2>
            </div>
            <span>Panel de Administración</span>
        </div>

        <nav class="admin-nav">
            <div class="nav-section-label">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section-label">Gestión</div>

               <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i> Usuarios
                @isset($usuariosPend)
                    @if($usuariosPend > 0)
                        <span class="admin-notif-badge">{{ $usuariosPend }}</span>
                    @endif
                @endisset
            </a>
            <a href="{{ route('admin.hoteles.index') }}" class="{{ request()->routeIs('admin.hoteles.*') ? 'active' : '' }}">
                <i class="fa-solid fa-hotel"></i> Hoteles
            </a>
            <a href="{{ route('admin.lugares.index') }}" class="{{ request()->routeIs('admin.lugares.*') ? 'active' : '' }}">
                <i class="fa-solid fa-map-location-dot"></i> Lugares
            </a>
            <a href="{{ route('admin.eventos.index') }}" class="{{ request()->routeIs('admin.eventos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-days"></i> Eventos
            </a>
            <a href="{{ route('admin.gastronomia.index') }}" class="{{ request()->routeIs('admin.gastronomia.*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i> Gastronomía
            </a>
            <a href="{{ route('admin.blog.index') }}" class="{{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i> Blog
            </a>
            <a href="{{ route('admin.reservas.index') }}" class="{{ request()->routeIs('admin.reservas.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i> Reservas
                @isset($reservasPend)
                    @if($reservasPend > 0)
                        <span class="admin-notif-badge">{{ $reservasPend }}</span>
                    @endif
                @endisset
            </a>
            <a href="{{ route('admin.empresas.index') }}" class="{{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}">
                <i class="fa-solid fa-building"></i> Empresas
                @isset($empresasPend, $notifCount)
                    @if($empresasPend + $notifCount > 0)
                        <span class="admin-notif-badge">{{ $empresasPend + $notifCount }}</span>
                    @endif
                @endisset
            </a>

            <div class="nav-section-label">Contenido</div>
            <a href="{{ route('admin.imagenes.index') }}" class="{{ request()->routeIs('admin.imagenes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-images"></i> Galería / Hero
            </a>

            <div class="nav-section-label" style="margin-top:auto;padding-top:1.5rem;">Sesión</div>
            <a href="{{ route('home') }}" target="_blank">
                <i class="fa-solid fa-globe"></i> Ver sitio
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>

    {{-- ── Wrapper derecho ── --}}
    <div class="admin-wrapper">
        <header class="admin-topbar">
            <div style="display:flex;align-items:center;gap:1rem;">
                <button class="admin-menu-toggle" id="adminMenuToggle" style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--gray-600);">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h1>@yield('page-title', 'Panel')</h1>
                    <p>@yield('page-subtitle', now()->format('d/m/Y'))</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;">
                @yield('topbar-actions')
                <div style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:var(--gray-600);">
                    <i class="fa-solid fa-circle-user" style="font-size:1.1rem;color:var(--green-700);"></i>
                    {{ auth()->user()->name ?? 'Admin' }}
                </div>
            </div>
        </header>

        <main class="admin-main-inner">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('import_errors'))
                <div class="alert alert-warning" style="margin-top:.5rem;">
                    <p style="font-weight:600;margin:0 0 .4rem;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Filas omitidas durante la importación:
                    </p>
                    <ul style="margin:0;padding-left:1.2rem;font-size:.85rem;">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

<script src="{{ asset('js/script.js') }}"></script>
@stack('scripts')
</body>
</html>
