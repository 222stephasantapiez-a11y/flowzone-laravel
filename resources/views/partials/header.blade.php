<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowZone - Turismo en Ortega, Tolima</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <a href="{{ route('home') }}">FlowZone</a>
        </div>
        <ul class="nav-menu">
            <li><a href="{{ route('home') }}">Inicio</a></li>
            <li><a href="{{ route('lugares') }}">Lugares</a></li>
            <li><a href="{{ route('hoteles') }}">Hoteles</a></li>
            <li><a href="{{ route('gastronomia') }}">Gastronomía</a></li>
            <li><a href="{{ route('eventos') }}">Eventos</a></li>
            <li><a href="{{ route('contacto') }}">Contacto</a></li>

            @auth
                <li><a href="{{ route('mis-reservas') }}">Mis Reservas</a></li>
                <li><a href="{{ route('favoritos') }}">Favoritos</a></li>

                @if(auth()->user()->rol === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}">Panel Admin</a></li>
                @endif

                @if(auth()->user()->rol === 'empresa')
                    <li><a href="{{ route('empresa.dashboard') }}">Mi Empresa</a></li>
                @endif

                <li class="user-menu">
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-logout">Salir</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a></li>
            @endauth
        </ul>
    </div>
</nav>
<main class="main-content">
