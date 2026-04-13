@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Gastronomía')
@section('page-title', 'Gastronomía')
@section('page-subtitle', 'Administra platos, restaurantes y servicios gastronómicos')

@section('content')

{{-- Barra superior --}}
<div class="admin-section">
    <div class="admin-section-header">
        <h2>
            <i class="fa-solid fa-utensils" style="color:var(--primary);margin-right:.4rem;"></i>
            Gastronomía
        </h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <button onclick="abrirModal()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Elemento
            </button>
            <a href="{{ route('admin.gastronomia.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.gastronomia.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            <span class="badge badge-info">{{ $items->total() }} total</span>
        </div>
    </div>

    {{-- Importar Excel --}}
    <form action="{{ route('admin.gastronomia.import.excel') }}"
          method="POST"
          enctype="multipart/form-data"
          style="margin-top:.75rem;">
        @csrf
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <input type="file" name="archivo" required>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-upload"></i> Importar Excel
            </button>
        </div>
    </form>
</div>

{{-- ===================== MODAL ===================== --}}
<div id="modal-gastronomia" style="
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
        max-width: 720px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(0,0,0,.25);
        overflow: hidden;
    ">
        {{-- Header modal --}}
        <div style="
            background: linear-gradient(135deg, var(--orange-900), var(--orange-700));
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <h3 style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-{{ isset($gastronomium) ? 'pen-to-square' : 'plus-circle' }}"></i>
                {{ isset($gastronomium) ? 'Editar: ' . $gastronomium->nombre : 'Nuevo Elemento' }}
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

            @isset($gastronomium)
                <form method="POST" action="{{ route('admin.gastronomia.update', $gastronomium) }}" class="admin-form" enctype="multipart/form-data">
                @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.gastronomia.store') }}" class="admin-form" enctype="multipart/form-data">
            @endisset
            @csrf

    <div class="form-row">
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required maxlength="150"
                   placeholder="Ej: Lechona Tolimense"
                   value="{{ old('nombre', $gastronomium->nombre ?? '') }}">
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select name="tipo">
                <option value="">— Seleccionar —</option>
                @foreach(['Plato típico','Bebida','Postre','Restaurante','Cafetería','Snack'] as $t)
                    <option value="{{ $t }}"
                        {{ old('tipo', $gastronomium->tipo ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" rows="3"
                  placeholder="Describe el plato, ingredientes principales, historia...">{{ old('descripcion', $gastronomium->descripcion ?? '') }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Precio promedio (COP)</label>
            <input type="number" step="0.01" name="precio_promedio"
                   placeholder="Ej: 25000"
                   value="{{ old('precio_promedio', $gastronomium->precio_promedio ?? '') }}">
        </div>
        <div class="form-group">
            <label>Restaurante / Establecimiento</label>
            <input type="text" name="restaurante" maxlength="150"
                   placeholder="Nombre del lugar"
                   value="{{ old('restaurante', $gastronomium->restaurante ?? '') }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion"
                   placeholder="Ej: Calle 5 #10-20, Ortega"
                   value="{{ old('direccion', $gastronomium->direccion ?? '') }}">
        </div>
        <div class="form-group">
            <label>Ubicación</label>
            <input type="text" name="ubicacion"
                   placeholder="Barrio o zona"
                   value="{{ old('ubicacion', $gastronomium->ubicacion ?? '') }}">
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" maxlength="20"
                   value="{{ old('telefono', $gastronomium->telefono ?? '') }}">
        </div>
    </div>

    <div class="form-group">
        <label>Ingredientes principales <span class="form-hint" style="display:inline">(separados por coma)</span></label>
        <input type="text" name="ingredientes"
               placeholder="Ej: Cerdo, Arroz, Arveja, Zanahoria"
               value="{{ old('ingredientes', $gastronomium->ingredientes ?? '') }}">
    </div>

    <div class="form-group">
        <label>Empresa asociada</label>
        <select name="empresa_id">
            <option value="">— Sin empresa —</option>
            @foreach($empresas as $emp)
                <option value="{{ $emp->id }}"
                    {{ old('empresa_id', $gastronomium->empresa_id ?? '') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    @include('partials.imagen_field', [
        'currentImage' => $gastronomium->imagen ?? null,
        'fieldId'      => 'gastro',
    ])

            <div style="display:flex;gap:.8rem;margin-top:.5rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-{{ isset($gastronomium) ? 'floppy-disk' : 'plus' }}"></i>
                    {{ isset($gastronomium) ? 'Actualizar' : 'Guardar' }}
                </button>
                @isset($gastronomium)
                    <a href="{{ route('admin.gastronomia.index') }}" class="btn btn-outline">
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

<div class="admin-section">
    <div class="admin-section-header">
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Elementos Registrados</h2>
        <span class="badge badge-info">{{ $items->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th><th>Imagen</th><th>Nombre</th><th>Tipo</th>
                    <th>Restaurante</th><th>Precio</th><th>Empresa</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="color:var(--gray);font-size:.8rem;">{{ $item->id }}</td>
                        <td class="td-img">
                            @if($item->imagen)
                                @php $src = str_starts_with($item->imagen,'http') ? $item->imagen : Storage::disk('public')->url($item->imagen); @endphp
                                <img src="{{ $src }}" alt="{{ $item->nombre }}" onerror="this.style.display='none'">
                            @else
                                <span style="color:var(--gray-lt);font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $item->nombre }}</strong></td>
                        <td>@if($item->tipo)<span class="badge badge-info">{{ $item->tipo }}</span>@else —@endif</td>
                        <td>{{ $item->restaurante ?? '—' }}</td>
                        <td>{{ $item->precio_promedio ? '$'.number_format($item->precio_promedio,0) : '—' }}</td>
                        <td>{{ $item->empresa?->nombre ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.gastronomia.edit', $item) }}" class="btn-small btn-edit btn-sm">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.gastronomia.destroy', $item) }}"
                                  style="display:inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-small btn-delete btn-sm">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--gray);padding:2.5rem;">
                            <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            No hay elementos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($items->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $items->firstItem() }}</strong>–<strong>{{ $items->lastItem() }}</strong>
            de <strong>{{ $items->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($items->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $items->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($items->getUrlRange(max(1,$items->currentPage()-2), min($items->lastPage(),$items->currentPage()+2)) as $page => $url)
                @if($page == $items->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
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
    document.getElementById('modal-gastronomia').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModal() {
    document.getElementById('modal-gastronomia').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-gastronomia').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});

@isset($gastronomium)
    abrirModal();
@endisset
</script>
@endpush

@endsection