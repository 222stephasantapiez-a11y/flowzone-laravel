@extends('layouts.app')

@section('title', $post->titulo)

@php
    $imgSrc = $post->imagen
        ? (str_starts_with($post->imagen, 'http') ? $post->imagen : Storage::disk('public')->url($post->imagen))
        : null;
@endphp

@section('content')

{{-- Hero --}}
<section style="min-height:55vh;display:flex;align-items:flex-end;position:relative;overflow:hidden;
    background:{{ $imgSrc ? 'url(\''.$imgSrc.'\') center/cover no-repeat' : 'linear-gradient(135deg,var(--green-900),var(--green-700))' }};">
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(27,67,50,.88) 0%,rgba(27,67,50,.5) 55%,transparent 100%);"></div>
    <div class="container" style="position:relative;z-index:2;padding-bottom:3rem;padding-top:calc(var(--navbar-height) + 2rem);">
        <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;align-items:center;">
            <span class="hero-eyebrow">
                <i class="fa-solid fa-newspaper fa-xs"></i>
                {{ $post->tipo === 'evento' ? 'Evento' : 'Noticia' }}
            </span>
        </div>
        <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,4.5vw,3rem);font-weight:900;color:#fff;line-height:1.15;margin-bottom:1.25rem;max-width:800px;">
            {{ $post->titulo }}
        </h1>
        <div style="display:flex;flex-wrap:wrap;gap:1.25rem;font-size:.85rem;color:rgba(255,255,255,.75);">
            <span><i class="fa-solid fa-calendar fa-xs" style="margin-right:.35rem;"></i>{{ $post->fecha_publicacion?->format('d \d\e F, Y') }}</span>
            <span><i class="fa-solid fa-user fa-xs" style="margin-right:.35rem;"></i>{{ $post->autor_nombre }}</span>
            @if($post->empresa)
                <span><i class="fa-solid fa-building fa-xs" style="margin-right:.35rem;"></i>{{ $post->empresa->nombre }}</span>
            @endif
        </div>
    </div>
</section>

{{-- Body --}}
<section class="container section">
    <div style="display:grid;grid-template-columns:1fr {{ $relacionados->isNotEmpty() ? '300px' : '' }};gap:3rem;align-items:start;">

        {{-- Artículo --}}
        <article>
            <div class="admin-section" style="padding:2.5rem;">
                <div style="font-size:1.05rem;line-height:1.9;color:var(--gray-600);">
                    {!! nl2br(e($post->contenido)) !!}
                </div>
            </div>

            {{-- Calificaciones --}}
            @include('partials.reviews', [
                'stats'          => $stats,
                'miCalificacion' => $miCalificacion,
                'reseñas'        => $reseñas,
                'tipo'           => 'blog',
                'itemId'         => $post->id,
            ])

            <div style="margin-top:2rem;">
                <a href="{{ route('blog') }}" class="btn btn-outline">
                    <i class="fa-solid fa-arrow-left fa-xs"></i> Volver al Blog
                </a>
            </div>
        </article>

        {{-- Sidebar relacionados --}}
        @if($relacionados->isNotEmpty())
        <aside style="position:sticky;top:calc(var(--navbar-height) + 1.5rem);">
            <div class="admin-section">
                <h3 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:2px solid var(--green-50);">
                    <i class="fa-solid fa-layer-group fa-xs" style="color:var(--green-600);margin-right:.4rem;"></i>
                    Más {{ $post->tipo === 'evento' ? 'eventos' : 'noticias' }}
                </h3>
                <div style="display:flex;flex-direction:column;gap:1.25rem;">
                    @foreach($relacionados as $rel)
                    @php
                        $relImg = $rel->imagen
                            ? (str_starts_with($rel->imagen,'http') ? $rel->imagen : Storage::disk('public')->url($rel->imagen))
                            : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=400&q=80';
                    @endphp
                    <a href="{{ route('blog.post', $rel->slug) }}"
                       style="display:flex;gap:.85rem;align-items:flex-start;text-decoration:none;color:inherit;transition:var(--transition);"
                       onmouseover="this.style.opacity='.75'" onmouseout="this.style.opacity='1'">
                        <img src="{{ $relImg }}" alt="{{ $rel->titulo }}"
                             style="width:72px;height:56px;object-fit:cover;border-radius:var(--radius-sm);flex-shrink:0;">
                        <div>
                            <p style="font-size:.82rem;color:var(--gray-400);margin-bottom:.2rem;">
                                {{ $rel->fecha_publicacion?->format('d/m/Y') }}
                            </p>
                            <p style="font-size:.88rem;font-weight:600;color:var(--gray-900);line-height:1.35;">
                                {{ Str::limit($rel->titulo, 65) }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
        @endif

    </div>
</section>

@endsection
