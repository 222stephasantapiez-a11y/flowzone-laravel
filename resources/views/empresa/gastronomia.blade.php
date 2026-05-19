@extends('layouts.empresa')

@section('page-title', 'Gastronomía')
@section('page-subtitle')Platos y servicios de {{ $empresa->nombre }}@endsection

@section('topbar-actions')
    <button type="button" onclick="abrirModalPlato()"
            class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus fa-xs"></i> Nuevo plato
    </button>
@endsection

@section('content')



{{-- ══ MODAL Agregar / Editar plato ══ --}}
<div id="modalPlato"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:flex-start;justify-content:center;padding:1.5rem 1rem;overflow-y:auto;">
    <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:680px;box-shadow:0 24px 64px rgba(0,0,0,.2);margin:auto;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100);">
            <div style="display:flex;align-items:center;gap:.6rem;">
                <i class="fa-solid fa-{{ isset($gastronomium) ? 'pen-to-square' : 'plus-circle' }}" style="color:var(--green-600);font-size:1rem;"></i>
                <h3 style="margin:0;font-size:1rem;font-weight:700;color:var(--gray-900);">
                    {{ isset($gastronomium) ? 'Editar: ' . $gastronomium->nombre : 'Agregar plato o servicio' }}
                </h3>
            </div>
            <button type="button" onclick="cerrarModalPlato()"
                    style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--gray-400);line-height:1;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Formulario --}}
        @isset($gastronomium)
            <form method="POST" action="{{ route('empresa.gastronomia.update', $gastronomium) }}"
                  class="admin-form" enctype="multipart/form-data" style="padding:1.5rem;">
            @method('PUT')
        @else
            <form method="POST" action="{{ route('empresa.gastronomia.store') }}"
                  class="admin-form" enctype="multipart/form-data" style="padding:1.5rem;">
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
            <div class="form-group">
                <label>Precio promedio (COP)</label>
                <input type="number" step="0.01" name="precio_promedio"
                       placeholder="Ej: 25000"
                       value="{{ old('precio_promedio', $gastronomium->precio_promedio ?? '') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3"
                      placeholder="Describe el plato o servicio...">{{ old('descripcion', $gastronomium->descripcion ?? '') }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion"
                       value="{{ old('direccion', $gastronomium->direccion ?? '') }}">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" maxlength="20"
                       value="{{ old('telefono', $gastronomium->telefono ?? '') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Ingredientes <span style="font-size:.78rem;color:var(--gray-400);font-weight:400;">(separados por coma)</span></label>
            <input type="text" name="ingredientes"
                   placeholder="Ej: Cerdo, Arroz, Arveja"
                   value="{{ old('ingredientes', $gastronomium->ingredientes ?? '') }}">
        </div>

        @include('partials.imagen_field', [
            'currentImage' => $gastronomium->imagen ?? null,
            'fieldId'      => 'emp-gastro',
        ])

        {{-- Disponibilidad --}}
        <div style="border-top:1.5px solid var(--gray-100);padding-top:1rem;margin-top:.5rem;">
            <div style="font-size:.75rem;font-weight:600;color:var(--green-700);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">
                <i class="fa-solid fa-clock fa-xs"></i> Disponibilidad
            </div>

            <div class="form-row" style="align-items:center;">
                <div class="form-group" style="display:flex;align-items:center;gap:.5rem;padding-bottom:.25rem;">
                    <input type="checkbox" name="disponible_hoy" id="f_disponible_hoy" value="1"
                           {{ old('disponible_hoy', $gastronomium->disponible_hoy ?? true) ? 'checked' : '' }}
                           style="accent-color:var(--green-700);width:16px;height:16px;">
                    <label for="f_disponible_hoy" style="cursor:pointer;font-size:.9rem;font-weight:500;margin:0;">Disponible hoy</label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio"
                           value="{{ old('hora_inicio', $gastronomium->hora_inicio ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Hora fin</label>
                    <input type="time" name="hora_fin"
                           value="{{ old('hora_fin', $gastronomium->hora_fin ?? '') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Stock diario <small style="font-weight:400;color:var(--gray-400);">(0 = ilimitado)</small></label>
                    <input type="number" name="stock_diario" min="0"
                           value="{{ old('stock_diario', $gastronomium->stock_diario ?? '') }}"
                           placeholder="Ej: 20">
                </div>
                <div class="form-group">
                    <label>Stock actual</label>
                    <input type="number" name="stock_actual" min="0"
                           value="{{ old('stock_actual', $gastronomium->stock_actual ?? '') }}"
                           placeholder="Quedan...">
                </div>
            </div>

            <div class="form-group">
                <label>Días de la semana</label>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                    @php $diasActuales = old('dias_semana', $gastronomium->dias_semana ?? []); @endphp
                    @foreach(['lunes','martes','miercoles','jueves','viernes','sabado','domingo'] as $dia)
                    <label style="display:flex;align-items:center;gap:.3rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.3rem .65rem;font-size:.82rem;cursor:pointer;">
                        <input type="checkbox" name="dias_semana[]" value="{{ $dia }}"
                               {{ in_array($dia, $diasActuales) ? 'checked' : '' }}
                               style="accent-color:var(--green-700);">
                        {{ ucfirst($dia) }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
            <button type="button" onclick="cerrarModalPlato()"
                    class="btn btn-outline">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-{{ isset($gastronomium) ? 'floppy-disk' : 'plus' }} fa-xs"></i>
                {{ isset($gastronomium) ? 'Actualizar' : 'Guardar' }}
            </button>
        </div>
        </form>
    </div>
</div>

{{-- Lista de platos --}}
<div class="admin-section">

    {{-- Barra superior: título + acciones --}}
    <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.6rem;margin-bottom:.5rem;">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;margin-right:auto;">
            <i class="fa-solid fa-utensils" style="color:var(--green-600);"></i> Mis platos y servicios
        </h2>

        {{-- Contador --}}
        <span class="badge badge-info" style="font-size:.82rem;padding:.3rem .75rem;">{{ $items->count() }} total</span>

        {{-- Excel export --}}
        <a href="{{ route('empresa.gastronomia.export.excel') }}" 
           style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .9rem;font-size:.82rem;font-weight:700;border-radius:var(--radius-full);border:none;background:#1D6F42;color:#fff;cursor:pointer;text-decoration:none;box-shadow:0 2px 6px rgba(29,111,66,.3);">
            <i class="fa-solid fa-file-excel fa-xs"></i> Excel
        </a>

        {{-- PDF export --}}
        <a href="{{ route('empresa.gastronomia.export.pdf') }}"
           style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .9rem;font-size:.82rem;font-weight:700;border-radius:var(--radius-full);border:none;background:#C0392B;color:#fff;cursor:pointer;text-decoration:none;box-shadow:0 2px 6px rgba(192,57,43,.3);">
            <i class="fa-solid fa-file-pdf fa-xs"></i> PDF
        </a>

        {{-- Importar Excel --}}
        <button type="button" onclick="document.getElementById('modalImportar').style.display='flex'"
                style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .9rem;font-size:.82rem;font-weight:700;border-radius:var(--radius-full);border:1.5px solid var(--gray-300);background:#fff;color:var(--gray-700);cursor:pointer;">
            <i class="fa-solid fa-file-arrow-up fa-xs"></i> Importar CSV/Excel
        </button>

        {{-- Filtro --}}
        <button type="button" id="btnFiltro" onclick="toggleFiltro()"
                style="display:inline-flex;align-items:center;gap:.4rem;padding:.4rem 1rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:var(--green-700);color:#fff;cursor:pointer;box-shadow:0 2px 8px rgba(45,106,79,.25);">
            <i class="fa-solid fa-filter fa-xs"></i> Filtro
            @if($hayFiltros)<span style="width:7px;height:7px;background:#fff;border-radius:50%;display:inline-block;opacity:.85;"></span>@endif
        </button>

        {{-- Reset stock --}}
        <form method="POST" action="{{ route('empresa.gastronomia.reset-stock') }}" style="display:inline"
              onsubmit="return confirm('¿Reiniciar el stock de todos los platos al stock diario?')">
            @csrf @method('PATCH')
            <button type="submit"
                    style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .9rem;font-size:.82rem;font-weight:700;border-radius:var(--radius-full);border:1.5px solid #f97316;background:#fff;color:#f97316;cursor:pointer;">
                <i class="fa-solid fa-rotate fa-xs"></i> Resetear stock del día
            </button>
        </form>
    </div>

    {{-- Panel de filtros desplegable --}}
    <div id="panelFiltro" style="display:{{ $hayFiltros ? 'block' : 'none' }};margin-bottom:1.25rem;padding:1.1rem 1.25rem;background:#f8fffe;border-radius:var(--radius-md);border:1.5px solid #b7e4c7;">
        <form method="GET" action="{{ route('empresa.gastronomia.index') }}" style="margin:0;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;margin-bottom:.85rem;">

                <div>
                    <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#40916c;margin-bottom:.35rem;">Nombre</label>
                    <input type="text" name="nombre" placeholder="Ej: Tamal..."
                           value="{{ $filtros['nombre'] ?? '' }}"
                           style="width:100%;padding:.5rem .85rem;font-size:.85rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);background:#fff;outline:none;">
                </div>

                <div>
                    <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#40916c;margin-bottom:.35rem;">Tipo</label>
                    <select name="tipo"
                            style="width:100%;padding:.5rem .85rem;font-size:.85rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);background:#fff;outline:none;">
                        <option value="">Todos</option>
                        @foreach(['Plato típico','Bebida','Postre','Restaurante','Cafetería','Snack'] as $t)
                            <option value="{{ $t }}" {{ ($filtros['tipo'] ?? '') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#40916c;margin-bottom:.35rem;">Precio mín (COP)</label>
                    <input type="number" name="precio_min" placeholder="Ej: 5000"
                           value="{{ $filtros['precio_min'] ?? '' }}"
                           style="width:100%;padding:.5rem .85rem;font-size:.85rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);background:#fff;outline:none;">
                </div>

                <div>
                    <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#40916c;margin-bottom:.35rem;">Precio máx (COP)</label>
                    <input type="number" name="precio_max" placeholder="Ej: 50000"
                           value="{{ $filtros['precio_max'] ?? '' }}"
                           style="width:100%;padding:.5rem .85rem;font-size:.85rem;border:1.5px solid #b7e4c7;border-radius:var(--radius-md);background:#fff;outline:none;">
                </div>

            </div>
            <div style="display:flex;gap:.6rem;">
                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:.35rem;padding:.45rem 1.1rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:var(--green-700);color:#fff;cursor:pointer;">
                    <i class="fa-solid fa-magnifying-glass fa-xs"></i> Aplicar
                </button>
                <a href="{{ route('empresa.gastronomia.index') }}"
                   style="display:inline-flex;align-items:center;gap:.35rem;padding:.45rem 1.1rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:1.5px solid #f87171;background:#fff;color:#c0392b;text-decoration:none;">
                    <i class="fa-solid fa-xmark fa-xs"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

{{-- Modal Importar --}}
<div id="modalImportar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;padding:1rem;"
     onclick="if(event.target===this)this.style.display='none'">
    <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:420px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid var(--gray-100);">
            <div style="display:flex;align-items:center;gap:.5rem;font-weight:700;color:var(--gray-900);">
                <i class="fa-solid fa-file-arrow-up" style="color:var(--green-600);"></i> Importar CSV / Excel
            </div>
            <button onclick="document.getElementById('modalImportar').style.display='none'" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--gray-400);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('empresa.gastronomia.import.excel') }}" enctype="multipart/form-data" style="padding:1.4rem;">
            @csrf
            <p style="font-size:.82rem;color:var(--gray-500);margin-bottom:1rem;">Sube un archivo <strong>.xlsx</strong> o <strong>.csv</strong> con columnas: <code>nombre, tipo, precio_cop, descripcion, direccion, telefono, ingredientes</code></p>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:.78rem;font-weight:700;color:var(--gray-700);margin-bottom:.4rem;">Archivo</label>
                <input type="file" name="archivo" accept=".xlsx,.xls,.csv" required
                       style="width:100%;padding:.5rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.85rem;">
            </div>
            <div style="display:flex;gap:.6rem;justify-content:flex-end;">
                <button type="button" onclick="document.getElementById('modalImportar').style.display='none'"
                        style="padding:.45rem 1rem;font-size:.85rem;border-radius:var(--radius-full);border:1px solid var(--gray-200);background:#fff;color:var(--gray-600);cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" style="padding:.45rem 1.1rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:var(--green-700);color:#fff;cursor:pointer;">
                    <i class="fa-solid fa-upload fa-xs"></i> Importar
                </button>
            </div>
        </form>
    </div>
</div>

    @if($items->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-utensils"></i>
            <p>Aún no has agregado platos o servicios.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $imgSrc = $item->imagen
                                ? (str_starts_with($item->imagen,'http') ? $item->imagen : Storage::disk('public')->url($item->imagen))
                                : null;
                            $dispStatus = $item->estaDisponibleAhora()
                                ? ['label'=>'Disponible','class'=>'badge-success']
                                : ($item->disponible_hoy
                                    ? ($item->stock_actual !== null && $item->stock_actual <= 0
                                        ? ['label'=>'Agotado','class'=>'badge-danger']
                                        : ['label'=>'Fuera de horario','class'=>'badge-warning'])
                                    : ['label'=>'No disponible','class'=>'badge-danger']);
                        @endphp
                        <tr>
                            <td>
                                @if($imgSrc)
                                    <img src="{{ $imgSrc }}" alt="{{ $item->nombre }}"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:var(--radius-sm);">
                                @else
                                    <div style="width:48px;height:48px;background:var(--gray-100);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;color:var(--gray-400);">
                                        <i class="fa-solid fa-utensils fa-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $item->nombre }}</strong></td>
                            <td>
                                @if($item->tipo)
                                    <span class="badge badge-info">{{ $item->tipo }}</span>
                                @else
                                    <span style="color:var(--gray-400);">—</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;font-size:.875rem;">
                                @if($item->precio_promedio)
                                    ${{ number_format($item->precio_promedio, 0) }} COP
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $dispStatus['class'] }}">{{ $dispStatus['label'] }}</span>
                                @if($item->stock_actual !== null)
                                    <div style="font-size:.75rem;color:var(--gray-400);margin-top:.15rem;">Stock: {{ $item->stock_actual }}</div>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('empresa.gastronomia.toggle', $item) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-small {{ $item->disponible_hoy ? 'btn-warning' : 'btn-success' }}"
                                            title="{{ $item->disponible_hoy ? 'Marcar no disponible' : 'Marcar disponible' }}">
                                        <i class="fa-solid fa-{{ $item->disponible_hoy ? 'eye-slash' : 'eye' }} fa-xs"></i>
                                    </button>
                                </form>
                                <a href="{{ route('empresa.gastronomia.edit', $item) }}" class="btn-small btn-edit">
                                    <i class="fa-solid fa-pen fa-xs"></i> Editar
                                </a>
                                <form method="POST" action="{{ route('empresa.gastronomia.destroy', $item) }}"
                                      style="display:inline" onsubmit="return confirm('¿Eliminar este plato?')">
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

@push('scripts')
<script>
function toggleFiltro() {
    const panel = document.getElementById("panelFiltro");
    const open  = panel.style.display === "none" || panel.style.display === "";
    panel.style.display = open ? "block" : "none";
}


// ── Modal plato ──
function abrirModalPlato() {
    document.getElementById('modalPlato').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarModalPlato() {
    document.getElementById('modalPlato').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modalPlato').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalPlato();
});

// Abrir automáticamente si hay errores de validación o estamos editando
@if($errors->any() || isset($gastronomium))
    abrirModalPlato();
@endif
</script>
@endpush

@endsection