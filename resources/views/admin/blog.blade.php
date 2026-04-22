@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Blog')
@section('page-title', 'Blog')
@section('page-subtitle', 'Publica eventos, noticias y contenido para la comunidad')

@section('content')

{{-- Barra superior --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-newspaper" style="color:var(--primary);margin-right:.4rem;"></i>
            Blog
        </h2>
        @unless(isset($blog))
           
            <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
                 <a href="{{ route('admin.blog.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nueva Publicación
            </a>
    <a href="{{ route('admin.blog.export.excel') }}" class="btn btn-success btn-sm">
        Exportar Excel
    </a>

    <a href="{{ route('admin.blog.export.pdf') }}" class="btn btn-danger btn-sm">
        Exportar PDF
    </a>
</div>
        @endunless

            <form action="{{ route('admin.blog.import.excel') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="archivo" required>
        <button type="submit" class="btn btn-primary btn-sm">
            Importar
        </button>
    </form>
    </div>
</div>

{{-- ===================== MODAL ===================== --}}
<div id="modal-blog" style="
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(4px);
    z-index: 999;
    overflow-y: auto;
    padding: 2rem 1rem;
">
    <div style="
        background: #fff;
        border-radius: 1rem;
        max-width: 820px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        overflow: hidden;
    ">
        {{-- Header modal --}}
        <div style="
            background: linear-gradient(135deg, var(--indigo-900), var(--indigo-700));
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <h3 style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-{{ isset($blog) ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ isset($blog) ? 'Editar Publicación' : 'Nueva Publicación' }}
            </h3>
            <button onclick="cerrarModal()" style="
                background: rgba(255,255,255,.15);
                border: none;
                color: #fff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background .2s;
            " onmouseover="this.style.background='rgba(255,255,255,.3)'"
               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Body modal --}}
        <div style="padding: 1.75rem;max-height:calc(90vh - 120px);overflow-y:auto;">

            @isset($blog)
                <form method="POST" action="{{ route('admin.blog.update', $blog) }}" class="admin-form" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.blog.store') }}" class="admin-form" enctype="multipart/form-data">
            @endisset
            @csrf

    <div class="form-row">
        <div class="form-group" style="flex:2;">
            <label>Título *</label>
            <input type="text" name="titulo" required maxlength="200"
                   placeholder="Título de la publicación"
                   value="{{ old('titulo', $blog->titulo ?? '') }}">
        </div>
        <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" required>
                <option value="noticia" {{ old('tipo', $blog->tipo ?? '') === 'noticia' ? 'selected' : '' }}>Noticia</option>
                <option value="evento"  {{ old('tipo', $blog->tipo ?? '') === 'evento'  ? 'selected' : '' }}>Evento</option>
                
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Contenido *</label>
        <textarea name="contenido" rows="8" required
                  placeholder="Escribe el contenido completo de la publicación...">{{ old('contenido', $blog->contenido ?? '') }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Autor</label>
            <input type="text" name="autor" maxlength="150"
                   placeholder="Nombre del autor"
                   value="{{ old('autor', $blog->autor ?? '') }}">
        </div>
        <div class="form-group">
            <label>Empresa asociada</label>
            <select name="empresa_id">
                <option value="">— Sin empresa —</option>
                @foreach($empresas as $emp)
                    <option value="{{ $emp->id }}"
                        {{ old('empresa_id', $blog->empresa_id ?? '') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Fecha de publicación</label>
            <input type="datetime-local" name="fecha_publicacion"
                   value="{{ old('fecha_publicacion', isset($blog) ? $blog->fecha_publicacion?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
        </div>
    </div>

    @include('partials.imagen_field', [
        'currentImage' => $blog->imagen ?? null,
        'fieldId'      => 'blog',
    ])

    <div class="form-group" style="display:flex;align-items:center;gap:.6rem;">
        <input type="checkbox" name="publicado" id="publicado"
               style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;"
               {{ old('publicado', $blog->publicado ?? true) ? 'checked' : '' }}>
        <label for="publicado" style="margin:0;cursor:pointer;">Publicar inmediatamente</label>
    </div>

            <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-{{ isset($blog) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($blog) ? 'Actualizar' : 'Publicar' }}
                </button>
                @isset($blog)
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                @else
                    <button type="button" onclick="cerrarModal()" class="btn btn-outline">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                @endisset
            </div>
            </form>
        </div>
    </div>
</div>

{{-- TABLA --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Publicaciones</h2>
        <div style="display:flex; gap:.5rem; align-items:center;">
            <span class="badge badge-info">{{ $posts->total() }} total</span>
        </div>
    </div>
    {{-- BOTÓN FILTRO --}}
<button type="button"
        class="btn btn-primary btn-sm"
        onclick="toggleFiltrosBlog()">
    <i class="fa-solid fa-filter"></i> Filtrar
</button>

{{-- FILTRO --}}
<form method="GET"
      action="{{ route('admin.blog.index') }}"
      id="filtrosBlogBox"
      style="display:none; margin:10px 0;">

    <input type="text" name="titulo"
           placeholder="Título"
           value="{{ request('titulo') }}">

    <input type="date" name="fecha"
           value="{{ request('fecha') }}">

    <input type="text" name="autor"
           placeholder="Autor"
           value="{{ request('autor') }}">

    {{-- TIPO FIJO --}}
    <select name="tipo">
        <option value="">Todos</option>
        <option value="noticia" {{ request('tipo') == 'noticia' ? 'selected' : '' }}>Noticia</option>
        <option value="evento" {{ request('tipo') == 'evento' ? 'selected' : '' }}>Evento</option>
        <option value="articulo" {{ request('tipo') == 'articulo' ? 'selected' : '' }}>Artículo</option>
        <option value="opinión" {{ request('tipo') == 'opinión' ? 'selected' : '' }}>Opinión</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">
        Aplicar
    </button>

    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline btn-sm">
        Limpiar
    </a>
</form>

{{-- JS filtro --}}
<script>
function toggleFiltrosBlog() {
    const box = document.getElementById('filtrosBlogBox');

    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
</script>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th><th>Imagen</th><th>Título</th><th>Tipo</th>
                    <th>Autor</th><th>Fecha</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($posts as $p)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $p->id }}</td>

                        <td class="td-img">
                            @if($p->imagen)
                                @php
                                    $src = str_starts_with($p->imagen,'http')
                                        ? $p->imagen
                                        : Storage::disk('public')->url($p->imagen);
                                @endphp
                                <img src="{{ $src }}" alt="{{ $p->titulo }}">
                            @else
                                <span>—</span>
                            @endif
                        </td>

                        <td><strong>{{ \Illuminate\Support\Str::limit($p->titulo, 45) }}</strong></td>

                        <td>
                            <span class="blog-tipo-{{ $p->tipo }}">
                                {{ ucfirst($p->tipo) }}
                            </span>
                        </td>

                        <td>{{ $p->autor_nombre }}</td>

                        <td style="white-space:nowrap;font-size:.82rem;">
                            {{ $p->fecha_publicacion?->format('d/m/Y') }}
                        </td>

                        <td>
                            @if($p->publicado)
                                <span class="badge badge-success">Publicado</span>
                            @else
                                <span class="badge badge-warning">Borrador</span>
                            @endif
                        </td>

                        <td>
                            <form method="POST" action="{{ route('admin.blog.publicar', $p) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-small btn-sm">
                                    {{ $p->publicado ? 'Ocultar' : 'Publicar' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.blog.edit', $p) }}" class="btn-small btn-edit btn-sm">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('admin.blog.destroy', $p) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;">
                            No hay publicaciones aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($posts->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $posts->firstItem() }}</strong>–<strong>{{ $posts->lastItem() }}</strong>
            de <strong>{{ $posts->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($posts->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($posts->getUrlRange(max(1,$posts->currentPage()-2), min($posts->lastPage(),$posts->currentPage()+2)) as $page => $url)
                @if($page == $posts->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
            @else
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-right fa-xs"></i></span>
            @endif
        </div>

        <form method="GET" class="per-page-form">
            @foreach(request()->except(['page','per_page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <label class="per-page-label">Filas:</label>
            <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                @foreach([5,10,25,50,100] as $n)
                    <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

</div>

@push('scripts')
<script>
function abrirModal() {
    document.getElementById('modal-blog').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-blog').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-blog').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});

@isset($blog)
    abrirModal();
@endisset
</script>
@endpush

@endsection