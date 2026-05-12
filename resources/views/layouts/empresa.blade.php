<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Empresa — FlowZone</title>
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
                <div class="admin-brand-icon"><i class="fa-solid fa-building"></i></div>
                <h2>FlowZone</h2>
            </div>
            <span>Panel Empresa</span>
        </div>

        <nav class="admin-nav">
            <div class="nav-section-label">Principal</div>
            <a href="{{ route('empresa.dashboard') }}" class="{{ request()->routeIs('empresa.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-section-label">Contenido</div>
            <a href="{{ route('empresa.blog.index') }}" class="{{ request()->routeIs('empresa.blog.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i> Blog
            </a>
            <a href="{{ route('empresa.gastronomia.index') }}" class="{{ request()->routeIs('empresa.gastronomia.*') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i> Gastronomía
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
                    <h1>@yield('page-title', 'Panel Empresa')</h1>
                    <p>@yield('page-subtitle', now()->format('d/m/Y'))</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:.8rem;flex-wrap:wrap;">
                @yield('topbar-actions')
                <div style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:var(--gray-600);">
                    <i class="fa-solid fa-circle-user" style="font-size:1.1rem;color:var(--green-600);"></i>
                    {{ auth()->user()->name ?? 'Empresa' }}
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
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
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
