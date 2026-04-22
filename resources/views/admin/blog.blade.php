@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Blog')
@section('page-title', 'Blog')
@section('page-subtitle', 'Publica eventos, noticias y contenido para la comunidad')

@section('content')

<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-{{ isset($blog) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--primary);margin-right:.4rem;"></i>
            {{ isset($blog) ? 'Editar: ' . $blog->titulo : 'Blog' }}
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

    @isset($blog)
        <form method="POST" action="{{ route('admin.blog.update', $blog) }}"
              class="admin-form" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.blog.store') }}"
              class="admin-form" enctype="multipart/form-data">
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

    <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-{{ isset($blog) ? 'floppy-disk' : 'plus' }}"></i>
            {{ isset($blog) ? 'Actualizar' : 'Publicar' }}
        </button>
        @isset($blog)
            <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </a>
        @endisset
    </div>
    </form>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Publicaciones</h2>
        <span class="badge badge-info">{{ $posts->count() }} total</span>
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
                                @php $src = str_starts_with($p->imagen,'http') ? $p->imagen : Storage::disk('public')->url($p->imagen); @endphp
                                <img src="{{ $src }}" alt="{{ $p->titulo }}" onerror="this.style.display='none'">
                            @else
                                <span style="color:var(--gray-lt);font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td><strong>{{ Str::limit($p->titulo, 45) }}</strong></td>
                        <td>
                            <span class="blog-tipo-badge blog-tipo-{{ $p->tipo }}">{{ ucfirst($p->tipo) }}</span>
                        </td>
                        <td>{{ $p->autor_nombre }}</td>
                        <td style="white-space:nowrap;font-size:.82rem;">{{ $p->fecha_publicacion?->format('d/m/Y') }}</td>
                        <td>
                            @if($p->publicado)
                                <span class="badge badge-success"><i class="fa-solid fa-eye fa-xs"></i> Publicado</span>
                            @else
                                <span class="badge badge-warning"><i class="fa-solid fa-eye-slash fa-xs"></i> Borrador</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.blog.publicar', $p) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-small btn-sm {{ $p->publicado ? 'btn-warning' : 'btn-success' }}">
                                    {{ $p->publicado ? 'Ocultar' : 'Publicar' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.blog.edit', $p) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $p) }}"
                                  style="display:inline" onsubmit="return confirm('¿Eliminar esta publicación?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay publicaciones aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
