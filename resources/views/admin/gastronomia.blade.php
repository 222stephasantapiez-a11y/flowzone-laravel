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
            <i class="fa-solid fa-{{ isset($gastronomium) ? 'pen-to-square' : 'plus-circle' }}"
               style="color:var(--primary);margin-right:.4rem;"></i>

            {{ isset($gastronomium) ? 'Editar: ' . $gastronomium->nombre : 'Gastronomía' }}
        </h2>

        @unless(isset($gastronomium))
        <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
            <a href="{{ route('admin.gastronomia.index') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Nuevo Elemento
            </a>

            <a href="{{ route('admin.gastronomia.export.excel') }}" class="btn btn-success btn-sm">
                Excel
            </a>

            <a href="{{ route('admin.gastronomia.export.pdf') }}" class="btn btn-danger btn-sm">
                PDF
            </a>
        </div>
        @endunless

        <form action="{{ route('admin.gastronomia.import.excel') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            <input type="file" name="archivo" required>
            <button type="submit" class="btn btn-primary btn-sm">
                Importar Excel
            </button>
        </form>
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
             @include('partials.import_modal', [
                'importRoute' => 'admin.gastronomia.import.excel',
                'sampleFile'  => 'ejemplo_gastronomia.xlsx',
                'modalId'     => 'importGastronomia',
                'columns'     => [
                    'nombre'      => 'Nombre del plato o restaurante (requerido)',
                    'descripcion' => 'Descripción',
                    'ubicacion'   => 'Ubicación o zona',
                    'precio'      => 'Precio promedio en COP',
                    'tipo'        => 'Tipo (Plato típico, Bebida, Postre, Restaurante...)',
                    'restaurante' => 'Nombre del establecimiento',
                    'direccion'   => 'Dirección física',
                    'telefono'    => 'Teléfono de contacto',
                ],
            ])
        </div>
    </div>
</div>

    {{-- FORMULARIO --}}
    @isset($gastronomium)
        <form method="POST"
              action="{{ route('admin.gastronomia.update', $gastronomium) }}"
              class="admin-form"
              enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST"
              action="{{ route('admin.gastronomia.store') }}"
              class="admin-form"
              enctype="multipart/form-data">
    @endisset

    @csrf

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
            <input type="text" name="nombre"
                   value="{{ old('nombre', $gastronomium->nombre ?? '') }}">
        </div>

        <div class="form-group">
            <label>Tipo</label>
            <select name="tipo">
                <option value="">— Seleccionar —</option>
                @foreach(['Plato típico','Bebida','Postre','Restaurante','Cafetería','Snack'] as $t)
                    <option value="{{ $t }}"
                        {{ old('tipo', $gastronomium->tipo ?? '') == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- 🔥 AGREGADO RESTAURANTE Y PRECIO --}}
    <div class="form-row">
        <div class="form-group">
            <label>Restaurante</label>
            <input type="text" name="restaurante"
                   value="{{ old('restaurante', $gastronomium->restaurante ?? '') }}">
        </div>

        <div class="form-group">
            <label>Precio promedio (COP)</label>
            <input type="number" name="precio_promedio"
                   value="{{ old('precio_promedio', $gastronomium->precio_promedio ?? '') }}">
        </div>
    </div>

    @include('partials.map_picker', [
        'mapId'        => 'gastro',
        'latValue'     => old('latitud', $gastronomium->latitud ?? ''),
        'lngValue'     => old('longitud', $gastronomium->longitud ?? ''),
        'addressValue' => old('direccion', $gastronomium->direccion ?? ''),
    ])

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion">{{ old('descripcion', $gastronomium->descripcion ?? '') }}</textarea>
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

    <button type="submit">
        {{ isset($gastronomium) ? 'Actualizar' : 'Guardar' }}
    </button>

    </form>
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

{{-- LISTADO --}}
<div class="admin-section">

    <div class="admin-section-header">
        <h2>Elementos Registrados</h2>

        <span class="badge badge-info">
            {{ $gastronomias->total() }} total
        </span>

        {{-- 🔥 BOTÓN FILTRO (MISMO ESTILO QUE USABAS) --}}
        <button type="button"
                class="btn btn-primary btn-sm"
                onclick="toggleFiltros()">
            <i class="fa-solid fa-filter"></i> Filtrar
        </button>
        <h2><i class="fa-solid fa-list" style="color:var(--primary);"></i> Elementos Registrados</h2>
        <span class="badge badge-info">{{ $items->total() }} total</span>
    </div>

    {{-- FILTROS --}}
    <form method="GET"
          action="{{ route('admin.gastronomia.index') }}"
          id="filtrosBox"
          style="display:none; margin:10px 0;">

        <input type="text" name="nombre" placeholder="Nombre"
               value="{{ request('nombre') }}">

        <select name="tipo">
            <option value="">Todos</option>
            <option value="comida rapida">Comida rápida</option>
            <option value="gourmet">Gourmet</option>
            <option value="tradicional">Tradicional</option>
            <option value="internacional">Internacional</option>
        </select>

        <input type="text" name="restaurante" placeholder="Restaurante"
               value="{{ request('restaurante') }}">

        <input type="number" name="precio" placeholder="Precio máximo"
               value="{{ request('precio') }}">


      <select name="empresa">
      <option value="">Todas las empresas</option>
    @foreach($empresas as $emp)
        <option value="{{ $emp->id }}"
            {{ request('empresa') == $emp->id ? 'selected' : '' }}>
            {{ $emp->nombre }}
        </option>
    @endforeach
   </select>
    

        <button type="submit">Aplicar</button>
    </form>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Restaurante</th>
                    <th>Precio</th>
                    <th>Empresa</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($gastronomias as $item)
                    <tr>
                        <td>{{ $item->id }}</td>

                        <td>
                            @if($item->imagen)
                                <img src="{{ str_starts_with($item->imagen,'http')
                                    ? $item->imagen
                                    : Storage::disk('public')->url($item->imagen) }}"
                                     width="50">
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->tipo }}</td>
                        <td>{{ $item->restaurante }}</td>
                        <td>{{ $item->precio_promedio }}</td>
                        <td>{{ $item->empresa?->nombre }}</td>

                        <td>
                            <a href="{{ route('admin.gastronomia.edit', $item) }}">Editar</a>

                            <form method="POST"
                                  action="{{ route('admin.gastronomia.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('¿Eliminar?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No hay datos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $gastronomias->links() }}
</div>

{{-- JS --}}
<script>
function toggleFiltros() {
    const box = document.getElementById('filtrosBox');

    if (box.style.display === 'none' || box.style.display === '') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
</script>

@endsection 
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    mapPickerInit(
        'gastro',
        {{ old('latitud', isset($gastronomium) && $gastronomium->latitud ? $gastronomium->latitud : 'null') }},
        {{ old('longitud', isset($gastronomium) && $gastronomium->longitud ? $gastronomium->longitud : 'null') }}
    );
});

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
</script>
@endpush
