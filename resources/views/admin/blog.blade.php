@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Blog')
@section('page-title', 'Blog')
@section('page-subtitle', 'Publica eventos, noticias y contenido para la comunidad')

@section('content')

{{-- ================= HEADER SUPERIOR ================= --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-newspaper" style="color:var(--primary);margin-right:.4rem;"></i>
            Blog
        </h2>

        @unless(isset($blog))
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nueva Publicación
            </button>

            <a href="{{ route('admin.blog.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>

            <a href="{{ route('admin.blog.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>

            @include('partials.import_modal', [
                'importRoute' => 'admin.blog.import.excel',
                'sampleFile'  => 'ejemplo_blog.xlsx',
                'modalId'     => 'importBlog',
                'columns'     => [
                    'titulo'            => 'Título de la publicación (requerido)',
                    'contenido'         => 'Contenido completo (requerido)',
                    'tipo'              => 'Tipo: noticia o evento (requerido)',
                    'autor'             => 'Nombre del autor',
                    'publicado'         => 'Publicado: 1 = sí, 0 = borrador',
                    'fecha_publicacion' => 'Fecha en formato YYYY-MM-DD HH:MM:SS',
                ],
            ])
        </div>
        @endunless
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
            ">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

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
                           value="{{ old('titulo', $blog->titulo ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Tipo *</label>
                    <select name="tipo" required>
                        <option value="noticia" {{ old('tipo', $blog->tipo ?? '') === 'noticia' ? 'selected' : '' }}>Noticia</option>
                        <option value="evento" {{ old('tipo', $blog->tipo ?? '') === 'evento' ? 'selected' : '' }}>Evento</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Contenido *</label>
                <textarea name="contenido" rows="8" required>{{ old('contenido', $blog->contenido ?? '') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Autor</label>
                    <input type="text" name="autor" value="{{ old('autor', $blog->autor ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Empresa</label>
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
                    <label>Fecha</label>
                    <input type="datetime-local" name="fecha_publicacion"
                           value="{{ old('fecha_publicacion', isset($blog) ? $blog->fecha_publicacion?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            @include('partials.imagen_field', [
                'currentImage' => $blog->imagen ?? null,
                'fieldId'      => 'blog',
            ])

            <div class="form-group">
                <input type="checkbox" name="publicado"
                    {{ old('publicado', $blog->publicado ?? true) ? 'checked' : '' }}>
                Publicar
            </div>

            {{-- BOTONES --}}
            <div style="display:flex; gap:.7rem; margin-top:1rem; flex-wrap:wrap;">
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

{{-- ================= LISTADO ================= --}}
<div class="admin-section" style="margin-top:1.5rem;">

    {{-- HEADER TABLA --}}
    <div class="admin-section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">

        <h2>
            <i class="fa-solid fa-list" style="color:var(--primary);"></i> Publicaciones
        </h2>

        <div style="display:flex; align-items:center; gap:.5rem;">
            <span class="badge badge-info">{{ $posts->total() }} total</span>

            <button type="button" onclick="toggleFiltros()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
        </div>

    </div>

    {{-- FILTROS --}}
    <div id="filtrosBox" style="display:none; margin-bottom:1rem;">
        <form method="GET" action="{{ route('admin.blog.index') }}">
            
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">

                <div>
                    <label>Título</label><br>
                    <input type="text" name="titulo" value="{{ request('titulo') }}">
                </div>

                <div>
                    <label>Autor</label><br>
                    <input type="text" name="autor" value="{{ request('autor') }}">
                </div>

                <div>
                    <label>Fecha</label><br>
                    <input type="date" name="fecha" value="{{ request('fecha') }}">
                </div>

                <div>
                    <label>Tipo</label><br>
                    <select name="tipo">
                        <option value="">Todos</option>
                        <option value="noticia" {{ request('tipo') == 'noticia' ? 'selected' : '' }}>Noticia</option>
                        <option value="evento" {{ request('tipo') == 'evento' ? 'selected' : '' }}>Evento</option>
                    </select>
                </div>

                <div>
                    <button class="btn btn-primary btn-sm">Aplicar</button>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary btn-sm">Limpiar</a>
                </div>

            </div>
        </form>
    </div>

    {{-- TABLA (NO TOCADA) --}}
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                @php
                    $sort      = $sort ?? 'id';
                    $direction = $direction ?? 'desc';
                @endphp
                <tr>
                    <th>
                        <a href="{{ route('admin.blog.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Imagen</th>
                    <th>
                        <a href="{{ route('admin.blog.index', array_merge(request()->all(), ['sort' => 'titulo', 'direction' => ($sort === 'titulo' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Título @if($sort === 'titulo') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.blog.index', array_merge(request()->all(), ['sort' => 'tipo', 'direction' => ($sort === 'tipo' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Tipo @if($sort === 'tipo') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.blog.index', array_merge(request()->all(), ['sort' => 'autor', 'direction' => ($sort === 'autor' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Autor @if($sort === 'autor') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.blog.index', array_merge(request()->all(), ['sort' => 'fecha_publicacion', 'direction' => ($sort === 'fecha_publicacion' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Fecha @if($sort === 'fecha_publicacion') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.blog.index', array_merge(request()->all(), ['sort' => 'publicado', 'direction' => ($sort === 'publicado' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Estado @if($sort === 'publicado') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($posts as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            @if($p->imagen)
                                <img src="{{ str_starts_with($p->imagen,'http') ? $p->imagen : Storage::url($p->imagen) }}" width="90   ">
                            @endif
                        </td>
                        <td>{{ $p->titulo }}</td>
                        <td>{{ $p->tipo }}</td>
                        <td>{{ $p->autor_nombre }}</td>
                        <td>{{ $p->fecha_publicacion?->format('d/m/Y') }}</td>

                        <td>
                            @if($p->publicado)
                                <span class="badge badge-success">Publicado</span>
                            @else
                                <span class="badge badge-warning">Borrador</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.blog.edit',$p) }}" class="btn-small btn-edit btn-sm">Editar</a>

                            <form method="POST" action="{{ route('admin.blog.destroy',$p) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button class="btn-small btn-delete btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">Sin datos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $posts, 'perPage' => $perPage])
</div>


{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script>
function abrirModal(){document.getElementById('modal-blog').style.display='block';}
function cerrarModal(){document.getElementById('modal-blog').style.display='none';}

@isset($blog) abrirModal(); @endisset

function toggleFiltros(){
    let box=document.getElementById('filtrosBox');
    box.style.display=(box.style.display==='none')?'block':'none';
}
</script>
@endpush

@endsection