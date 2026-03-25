@extends('layouts.empresa')

@section('page-title', 'Blog')
@section('page-subtitle', 'Publicaciones de {{ $empresa->nombre }}')

@section('topbar-actions')
    @unless(isset($post))
        <a href="{{ route('empresa.blog.index') }}#form" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus fa-xs"></i> Nueva publicación
        </a>
    @endunless
@endsection

@section('content')

{{-- Formulario crear / editar --}}
<div class="admin-section" id="form">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-{{ isset($post) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--green-600);"></i>
        {{ isset($post) ? 'Editar: ' . Str::limit($post->titulo, 50) : 'Nueva publicación' }}
    </h2>

    @isset($post)
        <form method="POST" action="{{ route('empresa.blog.update', $post) }}"
              class="admin-form" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{ route('empresa.blog.store') }}"
              class="admin-form" enctype="multipart/form-data">
    @endisset
    @csrf

    <div class="form-row">
        <div class="form-group" style="flex:2;">
            <label>Título *</label>
            <input type="text" name="titulo" required maxlength="200"
                   value="{{ old('titulo', $post->titulo ?? '') }}"
                   placeholder="Título de la publicación">
        </div>
        <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" required>
                <option value="noticia" {{ old('tipo', $post->tipo ?? '') === 'noticia' ? 'selected' : '' }}>Noticia</option>
                <option value="evento"  {{ old('tipo', $post->tipo ?? '') === 'evento'  ? 'selected' : '' }}>Evento</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Contenido *</label>
        <textarea name="contenido" rows="7" required
                  placeholder="Escribe el contenido...">{{ old('contenido', $post->contenido ?? '') }}</textarea>
    </div>

    @include('partials.imagen_field', [
        'currentImage' => $post->imagen ?? null,
        'fieldId'      => 'emp-blog',
    ])

    <div class="alert alert-info" style="margin-bottom:1rem;">
        <i class="fa-solid fa-circle-info"></i>
        Tu publicación quedará pendiente de aprobación por el administrador.
    </div>

    <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-{{ isset($post) ? 'floppy-disk' : 'paper-plane' }} fa-xs"></i>
            {{ isset($post) ? 'Actualizar' : 'Enviar para revisión' }}
        </button>
        @isset($post)
            <a href="{{ route('empresa.blog.index') }}" class="btn btn-outline">Cancelar</a>
        @endisset
    </div>
    </form>
</div>

{{-- Lista de publicaciones --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-list" style="color:var(--green-600);"></i> Mis publicaciones
        </h2>
        <span class="badge badge-info">{{ $posts->count() }}</span>
    </div>

    @if($posts->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-newspaper"></i>
            <p>Aún no has publicado nada.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $p)
                        <tr>
                            <td><strong>{{ Str::limit($p->titulo, 50) }}</strong></td>
                            <td>
                                <span class="badge {{ $p->tipo === 'evento' ? 'badge-info' : 'badge-success' }}">
                                    {{ ucfirst($p->tipo) }}
                                </span>
                            </td>
                            <td style="white-space:nowrap;font-size:.82rem;color:var(--gray-400);">
                                {{ $p->fecha_publicacion?->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($p->publicado)
                                    <span class="badge badge-success"><i class="fa-solid fa-eye fa-xs"></i> Publicado</span>
                                @else
                                    <span class="badge badge-warning"><i class="fa-solid fa-clock fa-xs"></i> Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('empresa.blog.edit', $p) }}" class="btn-small btn-edit">
                                    <i class="fa-solid fa-pen fa-xs"></i> Editar
                                </a>
                                <form method="POST" action="{{ route('empresa.blog.destroy', $p) }}"
                                      style="display:inline" onsubmit="return confirm('¿Eliminar esta publicación?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete">
                                        <i class="fa-solid fa-trash fa-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
