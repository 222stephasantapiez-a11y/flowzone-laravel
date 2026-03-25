@extends('layouts.app')

@section('title', 'Blog de Viajes')
@section('body-class', 'no-hero')

@section('content')
<main>

{{-- Page Hero --}}
<section class="page-hero" style="background:linear-gradient(135deg,var(--green-900) 0%,var(--green-700) 100%);">
    <div class="container">
        <div class="page-hero-content">
            <span class="page-hero-eyebrow">
                <i class="fa-solid fa-newspaper"></i> Noticias
            </span>
            <h1>Blog de Viajes</h1>
            <p>Historias, noticias y novedades de Ortega, Tolima</p>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Inicio</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">Blog</span>
            </nav>
        </div>
    </div>
</section>

{{-- Contenido --}}
<section class="container section">

    {{-- Filtros --}}
    <div class="filters">
        <form method="GET" action="{{ route('blog') }}" class="filter-form">
            <input type="text" name="busqueda" placeholder="Buscar publicaciones..."
                   value="{{ $busqueda }}" aria-label="Buscar en el blog">
            <select name="tipo" aria-label="Filtrar por tipo">
                <option value="">Todos</option>
                <option value="noticia" {{ $tipo_filtro === 'noticia' ? 'selected' : '' }}>Noticias</option>
                <option value="evento"  {{ $tipo_filtro === 'evento'  ? 'selected' : '' }}>Eventos</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass fa-xs" aria-hidden="true"></i> Filtrar
            </button>
            <a href="{{ route('blog') }}" class="btn btn-outline">Limpiar</a>
        </form>
    </div>

    {{-- Blog grid --}}
    <div class="blog-grid">
    @forelse($posts as $post)
        @php
            $imgSrc = $post->imagen
                ? (str_starts_with($post->imagen, 'http') ? $post->imagen : Storage::disk('public')->url($post->imagen))
                : null;
        @endphp
        <article class="blog-card animate-on-scroll">
            <div class="card-img-wrap">
                @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $post->titulo }}"
                         onerror="this.parentElement.innerHTML='<div class=\'card-img-fallback\'><i class=\'fa-solid fa-newspaper\'></i></div>'">
                @else
                    <div class="card-img-fallback">
                        <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                    </div>
                @endif
                <span class="card-badge card-badge-accent">
                    {{ $post->tipo === 'evento' ? 'Evento' : 'Noticia' }}
                </span>
            </div>
            <div class="blog-card-body">
                <div class="blog-card-meta">
                    <span>
                        <i class="fa-solid fa-calendar fa-xs" aria-hidden="true"></i>
                        {{ $post->fecha_publicacion?->format('d/m/Y') }}
                    </span>
                    <span>
                        <i class="fa-solid fa-user fa-xs" aria-hidden="true"></i>
                        {{ $post->autor_nombre }}
                    </span>
                </div>
                <h2 class="blog-card-title">{{ $post->titulo }}</h2>
                <p class="blog-card-excerpt">{{ Str::limit(strip_tags($post->contenido), 130) }}</p>
                <div class="blog-card-footer">
                    <a href="{{ route('blog.post', $post->slug) }}" class="btn btn-primary btn-sm">
                        Leer más <i class="fa-solid fa-arrow-right fa-xs" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div class="empty-state">
            <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
            <p>No hay publicaciones disponibles.</p>
        </div>
    @endforelse
    </div>{{-- /.blog-grid --}}

    {{-- Paginación --}}
    @if($posts->hasPages())
        <div class="pagination-wrap">
            {{ $posts->withQueryString()->links() }}
        </div>
    @endif

</section>

</main>
@endsection
