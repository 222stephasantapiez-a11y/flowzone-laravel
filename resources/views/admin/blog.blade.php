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

{{-- ================= PENDIENTES DE APROBACIÓN ================= --}}
@php $pendientes = $posts->getCollection()->where('publicado', false)->where('empresa_id', '!=', null); @endphp
@if($pendientes->count())
<div class="admin-section" style="border-left:4px solid var(--warning);background:linear-gradient(135deg,#fffbeb,#fef3c7);">
    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
        <div style="width:40px;height:40px;background:var(--warning);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid fa-clock" style="color:#fff;font-size:1rem;"></i>
        </div>
        <div>
            <h3 style="margin:0;font-size:1rem;font-weight:700;color:#92400e;">
                {{ $pendientes->count() }} publicación(es) pendiente(s) de aprobación
            </h3>
            <p style="margin:0;font-size:.82rem;color:#a16207;">Enviadas por empresas, esperan tu revisión</p>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:.6rem;">
        @foreach($pendientes as $p)
        <div style="background:#fff;border-radius:.6rem;padding:.85rem 1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;box-shadow:0 1px 4px rgba(0,0,0,.07);">
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;font-size:.9rem;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $p->titulo }}
                </div>
                <div style="font-size:.78rem;color:#6b7280;margin-top:.15rem;">
                    <i class="fa-solid fa-building fa-xs"></i> {{ $p->empresa?->nombre ?? '—' }}
                    &nbsp;·&nbsp;
                    <i class="fa-solid fa-tag fa-xs"></i> {{ $p->tipo }}
                    &nbsp;·&nbsp;
                    <i class="fa-solid fa-calendar fa-xs"></i> {{ $p->fecha_publicacion?->format('d/m/Y') }}
                </div>
            </div>
            <div style="display:flex;gap:.5rem;flex-shrink:0;">
                {{-- Botón ver preview --}}
                <button type="button"
                        onclick="abrirPreview({{ $p->id }})"
                        style="padding:.4rem .8rem;border-radius:.4rem;border:1.5px solid var(--gray-200);background:#fff;cursor:pointer;font-size:.8rem;font-weight:600;color:var(--gray-600);display:inline-flex;align-items:center;gap:.3rem;">
                    <i class="fa-solid fa-eye fa-xs"></i> Ver
                </button>
                {{-- Botón aprobar --}}
                <form method="POST" action="{{ route('admin.blog.publicar', $p) }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit"
                            style="padding:.4rem .9rem;border-radius:.4rem;border:none;background:var(--success);color:#fff;cursor:pointer;font-size:.8rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;">
                        <i class="fa-solid fa-check fa-xs"></i> Aprobar
                    </button>
                </form>
                {{-- Botón rechazar (eliminar) --}}
                <button type="button"
                        onclick="abrirConfirmBlog({{ $p->id }}, '{{ addslashes($p->titulo) }}')"
                        style="padding:.4rem .8rem;border-radius:.4rem;border:none;background:var(--danger);color:#fff;cursor:pointer;font-size:.8rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;">
                    <i class="fa-solid fa-xmark fa-xs"></i> Rechazar
                </button>
                <form id="form-delete-blog-pend-{{ $p->id }}" method="POST"
                      action="{{ route('admin.blog.destroy', $p) }}" style="display:none;">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>

        {{-- Modal preview individual --}}
        <div id="preview-{{ $p->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:1100;overflow-y:auto;padding:2rem 1rem;">
            <div style="background:#fff;border-radius:1rem;max-width:700px;margin:0 auto;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.3);">
                <div style="background:linear-gradient(135deg,var(--green-900),var(--green-700));padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;">
                    <span style="color:#fff;font-weight:700;font-size:.95rem;">
                        <i class="fa-solid fa-eye"></i> Preview — {{ $p->titulo }}
                    </span>
                    <button onclick="cerrarPreview({{ $p->id }})" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:.9rem;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div style="padding:1.5rem;">
                    @if($p->imagen)
                        <img src="{{ str_starts_with($p->imagen,'http') ? $p->imagen : Storage::url($p->imagen) }}"
                             style="width:100%;max-height:280px;object-fit:cover;border-radius:.5rem;margin-bottom:1rem;">
                    @endif
                    <div style="display:flex;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap;">
                        <span class="badge badge-info">{{ $p->tipo }}</span>
                        <span style="font-size:.8rem;color:var(--gray-500);">
                            <i class="fa-solid fa-building fa-xs"></i> {{ $p->empresa?->nombre ?? '—' }}
                        </span>
                        <span style="font-size:.8rem;color:var(--gray-500);">
                            <i class="fa-solid fa-calendar fa-xs"></i> {{ $p->fecha_publicacion?->format('d M Y') }}
                        </span>
                    </div>
                    <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:.75rem;">{{ $p->titulo }}</h3>
                    <div style="font-size:.9rem;color:var(--gray-600);line-height:1.7;max-height:300px;overflow-y:auto;">
                        {!! nl2br(e($p->contenido)) !!}
                    </div>
                    <div style="display:flex;gap:.6rem;margin-top:1.25rem;justify-content:flex-end;">
                        <button onclick="cerrarPreview({{ $p->id }})"
                                style="padding:.5rem 1rem;border-radius:.4rem;border:1.5px solid var(--gray-200);background:#fff;cursor:pointer;font-size:.85rem;font-weight:600;">
                            Cerrar
                        </button>
                        <form method="POST" action="{{ route('admin.blog.publicar', $p) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="padding:.5rem 1.1rem;border-radius:.4rem;border:none;background:var(--success);color:#fff;cursor:pointer;font-size:.85rem;font-weight:600;">
                                <i class="fa-solid fa-check fa-xs"></i> Aprobar y publicar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===================== MODAL CREAR/EDITAR ===================== --}}
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
            background: linear-gradient(135deg, var(--green-900), var(--green-700));
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
                border: none; color: #fff;
                width: 32px; height: 32px;
                border-radius: 50%; cursor: pointer;
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
                        <option value="evento"  {{ old('tipo', $blog->tipo ?? '') === 'evento'  ? 'selected' : '' }}>Evento</option>
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

            <div class="form-group" style="display:flex;align-items:center;gap:.6rem;">
                <input type="checkbox" name="publicado" id="publicado"
                       style="width:18px;height:18px;accent-color:var(--primary);flex-shrink:0;"
                       {{ old('publicado', $blog->publicado ?? true) ? 'checked' : '' }}>
                <label for="publicado" style="margin:0;cursor:pointer;">Publicar</label>
            </div>

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

    <div id="filtrosBox" style="display:none; padding: 1rem 0 .5rem;">
        <form method="GET" action="{{ route('admin.blog.index') }}">
            <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
                <div class="filter-field">
                    <label class="filter-label">Título</label>
                    <input type="text" name="titulo" value="{{ request('titulo') }}"
                           placeholder="Buscar por título..." class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Autor</label>
                    <input type="text" name="autor" value="{{ request('autor') }}"
                           placeholder="Nombre del autor..." class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}" class="filter-input">
                </div>
                <div class="filter-field">
                    <label class="filter-label">Tipo</label>
                    <select name="tipo" class="filter-input">
                        <option value="">Todos</option>
                        <option value="noticia" {{ request('tipo') == 'noticia' ? 'selected' : '' }}>Noticia</option>
                        <option value="evento"  {{ request('tipo') == 'evento'  ? 'selected' : '' }}>Evento</option>
                    </select>
                </div>
                <div style="display:flex; gap:.5rem; align-items:flex-end; padding-bottom:1px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i> Aplicar
                    </button>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

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
                    <tr style="{{ !$p->publicado && $p->empresa_id ? 'background:#fffbeb;' : '' }}">
                        <td>{{ $p->id }}</td>
                        <td>
                            @if($p->imagen)
                                <img src="{{ str_starts_with($p->imagen,'http') ? $p->imagen : Storage::url($p->imagen) }}" width="90">
                            @endif
                        </td>
                        <td>
                            {{ $p->titulo }}
                            @if(!$p->publicado && $p->empresa_id)
                                <span class="badge badge-warning" style="margin-left:.3rem;font-size:.7rem;">Pendiente</span>
                            @endif
                        </td>
                        <td>{{ $p->tipo }}</td>
                        <td>{{ $p->autor_nombre }}</td>
                        <td>{{ $p->fecha_publicacion?->format('d/m/Y') }}</td>
                        <td>
                            @if($p->publicado)
                                <span class="badge badge-success">Publicado</span>
                            @elseif($p->empresa_id)
                                <span class="badge badge-warning">Pendiente</span>
                            @else
                                <span class="badge badge-secondary">Borrador</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;flex-wrap:wrap;gap:.35rem;align-items:center;">

                                {{-- Aprobar / Despublicar --}}
                                <form method="POST" action="{{ route('admin.blog.publicar', $p) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            title="{{ $p->publicado ? 'Despublicar' : 'Aprobar y publicar' }}"
                                            style="padding:.35rem .7rem;border-radius:.4rem;border:none;cursor:pointer;font-size:.78rem;font-weight:600;display:inline-flex;align-items:center;gap:.25rem;
                                                   background:{{ $p->publicado ? '#f59e0b' : 'var(--success)' }};color:#fff;">
                                        <i class="fa-solid fa-{{ $p->publicado ? 'eye-slash' : 'check' }} fa-xs"></i>
                                        {{ $p->publicado ? 'Despublicar' : 'Aprobar' }}
                                    </button>
                                </form>

                                <a href="{{ route('admin.blog.edit', $p) }}" class="btn-small btn-edit btn-sm">
                                    <i class="fa-solid fa-pen fa-xs"></i> Editar
                                </a>
                                <button type="button" class="btn-small btn-delete btn-sm"
                                        onclick="abrirConfirmBlog({{ $p->id }}, '{{ addslashes($p->titulo) }}')">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                                <form id="form-delete-blog-{{ $p->id }}" method="POST"
                                      action="{{ route('admin.blog.destroy', $p) }}"
                                      style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">Sin datos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $posts, 'perPage' => $perPage])
</div>

{{-- MODAL CONFIRMACIÓN ELIMINAR --}}
<div id="modal-confirm-blog" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="text-align:center;margin-bottom:1.25rem;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.4rem;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:.4rem;">¿Eliminar publicación?</h3>
            <p style="font-size:.88rem;color:#6b7280;" id="confirm-nombre-blog"></p>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:1.25rem;">
            <input type="checkbox" id="confirm-check-blog" style="accent-color:#dc2626;width:16px;height:16px;">
            <span style="font-size:.85rem;color:#991b1b;font-weight:500;">Entiendo que esta acción no se puede deshacer</span>
        </label>
        <div style="display:flex;gap:.75rem;">
            <button type="button" onclick="cerrarConfirmBlog()"
                    style="flex:1;padding:.7rem;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#fff;cursor:pointer;font-size:.88rem;font-weight:600;color:#374151;">
                Cancelar
            </button>
            <button type="button" id="btn-confirmar-delete-blog" onclick="ejecutarDeleteBlog()" disabled
                    style="flex:1;padding:.7rem;border:none;border-radius:.5rem;background:#dc2626;color:#fff;cursor:pointer;font-size:.88rem;font-weight:600;opacity:.5;transition:opacity .2s;">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

@endsection

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
    if (e.key === 'Escape') { cerrarModal(); cerrarConfirmBlog(); }
});
@isset($blog)
    abrirModal();
@endisset

// ── Preview pendientes ──
function abrirPreview(id) {
    document.getElementById('preview-' + id).style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function cerrarPreview(id) {
    document.getElementById('preview-' + id).style.display = 'none';
    document.body.style.overflow = '';
}

// ── Confirmar eliminar ──
let deleteBlogId = null;
function abrirConfirmBlog(id, titulo) {
    deleteBlogId = id;
    document.getElementById('confirm-nombre-blog').textContent = 'Vas a eliminar: ' + titulo;
    document.getElementById('confirm-check-blog').checked = false;
    document.getElementById('btn-confirmar-delete-blog').disabled = true;
    document.getElementById('btn-confirmar-delete-blog').style.opacity = '.5';
    document.getElementById('modal-confirm-blog').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function cerrarConfirmBlog() {
    deleteBlogId = null;
    document.getElementById('modal-confirm-blog').style.display = 'none';
    document.body.style.overflow = '';
}
function ejecutarDeleteBlog() {
    if (!deleteBlogId) return;
    // Intenta el form de pendientes primero, luego el de la tabla
    const form = document.getElementById('form-delete-blog-pend-' + deleteBlogId)
               || document.getElementById('form-delete-blog-' + deleteBlogId);
    if (form) form.submit();
}
document.getElementById('confirm-check-blog').addEventListener('change', function () {
    const btn = document.getElementById('btn-confirmar-delete-blog');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
});
document.getElementById('modal-confirm-blog').addEventListener('click', function (e) {
    if (e.target === this) cerrarConfirmBlog();
});

function toggleFiltros() {
    const box = document.getElementById('filtrosBox');
    box.style.display = (box.style.display === 'none') ? 'block' : 'none';
}
window.addEventListener('load', function () {
    if ("{{ request('titulo') }}" || "{{ request('autor') }}" || "{{ request('fecha') }}" || "{{ request('tipo') }}") {
        document.getElementById('filtrosBox').style.display = 'block';
    }
});
</script>
@endpush