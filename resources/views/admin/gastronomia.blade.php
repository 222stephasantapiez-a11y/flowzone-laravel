@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.admin')

@section('title', 'Gastronomía')
@section('page-title', 'Gastronomía')
@section('page-subtitle', 'Administra platos, restaurantes y servicios gastronómicos')

@section('content')

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