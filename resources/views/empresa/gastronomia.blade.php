@extends('layouts.empresa')

@section('page-title', 'Gastronomía')
@section('page-subtitle', 'Platos y servicios de {{ $empresa->nombre }}')

@section('topbar-actions')
    <button type="button" onclick="abrirModalPlato()"
            class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus fa-xs"></i> Nuevo plato
    </button>
@endsection

@section('content')

{{-- ══════════════════════════════════════
     GENERADOR DE PLANES TURÍSTICOS
══════════════════════════════════════ --}}
<div class="admin-section" style="margin-bottom:1.5rem;border:2px dashed #b7e4c7;background:#f8fffe;">

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem;">
        <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;margin:0;">
            <i class="fa-solid fa-wand-magic-sparkles" style="color:var(--green-600);"></i> Generador de Planes Turísticos
        </h2>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
            <button type="button" id="btnGenerar" onclick="generarPlan()"
                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1.1rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:var(--green-700);color:#fff;cursor:pointer;box-shadow:0 2px 8px rgba(45,106,79,.25);">
                <i class="fa-solid fa-dice fa-xs"></i> Generar plan
            </button>
            <button type="button" id="btnLimpiar" onclick="limpiarPlan()" style="display:none;
                    display:none;align-items:center;gap:.4rem;padding:.45rem 1.1rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:1.5px solid #f87171;background:#fff;color:#c0392b;cursor:pointer;">
                <i class="fa-solid fa-xmark fa-xs"></i> Limpiar
            </button>
        </div>
    </div>

    {{-- Panel del plan generado (oculto hasta generar) --}}
    <div id="planGenerado" style="display:none;">
        <div style="overflow:hidden;border-radius:var(--radius-md);border:1px solid #b7e4c7;">
            {{-- Banner --}}
            <div style="background:var(--green-700);color:#fff;padding:.75rem 1.25rem;text-align:center;font-weight:700;font-size:.9rem;letter-spacing:.03em;">
                <i class="fa-solid fa-tag fa-xs"></i> ¡OFERTA ESPECIAL: PLAN TURÍSTICO CON 20% DE DESCUENTO!
            </div>
            {{-- Cards de componentes --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;padding:1.25rem;background:#fff;">
                <div style="padding:.85rem 1rem;border-left:4px solid #6366f1;background:#f5f3ff;border-radius:0 var(--radius-md) var(--radius-md) 0;">
                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#6366f1;margin-bottom:.2rem;">Evento</div>
                    <div id="planEvento" style="font-weight:700;color:var(--gray-900);font-size:.9rem;">—</div>
                </div>
                <div style="padding:.85rem 1rem;border-left:4px solid #f97316;background:#fff7ed;border-radius:0 var(--radius-md) var(--radius-md) 0;">
                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#f97316;margin-bottom:.2rem;">Gastronomía</div>
                    <div id="planGastronomia" style="font-weight:700;color:var(--gray-900);font-size:.9rem;">—</div>
                </div>
                <div style="padding:.85rem 1rem;border-left:4px solid #22c55e;background:#f0fdf4;border-radius:0 var(--radius-md) var(--radius-md) 0;">
                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#22c55e;margin-bottom:.2rem;">Hotel</div>
                    <div id="planHotel" style="font-weight:700;color:var(--gray-900);font-size:.9rem;">—</div>
                </div>
                <div style="padding:.85rem 1rem;border-left:4px solid #3b82f6;background:#eff6ff;border-radius:0 var(--radius-md) var(--radius-md) 0;">
                    <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#3b82f6;margin-bottom:.2rem;">Lugar</div>
                    <div id="planLugar" style="font-weight:700;color:var(--gray-900);font-size:.9rem;">—</div>
                </div>
            </div>
            {{-- Footer con precios + botón guardar --}}
            <div style="background:#1e293b;color:#fff;padding:1.1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem;">
                <div>
                    <span id="planSubtotal" style="text-decoration:line-through;color:#94a3b8;margin-right:.75rem;">—</span>
                    <span style="background:#ef4444;padding:2px 8px;border-radius:5px;font-size:.75rem;font-weight:700;">-20% DCTO</span>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <div style="text-align:right;">
                        <div style="font-size:.78rem;color:#94a3b8;">Precio Total Plan:</div>
                        <div id="planPrecioFinal" style="font-size:1.6rem;font-weight:900;color:#fbbf24;">—</div>
                    </div>
                    {{-- Formulario para guardar --}}
                    <form method="POST" action="{{ route('empresa.gastronomia.planes.guardar') }}" id="formGuardarPlan">
                        @csrf
                        <input type="hidden" name="evento_id"      id="inputEventoId">
                        <input type="hidden" name="gastronomia_id" id="inputGastronomiaId">
                        <input type="hidden" name="hotel_id"       id="inputHotelId">
                        <input type="hidden" name="lugar_id"       id="inputLugarId">
                        <input type="hidden" name="subtotal"       id="inputSubtotal">
                        <input type="hidden" name="descuento"      id="inputDescuento">
                        <input type="hidden" name="precio_final"   id="inputPrecioFinal">
                        <button type="submit"
                                style="padding:.5rem 1.2rem;font-size:.85rem;font-weight:700;border-radius:var(--radius-full);border:none;background:#fbbf24;color:#1e293b;cursor:pointer;">
                            <i class="fa-solid fa-floppy-disk fa-xs"></i> Agregar plan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Mensaje por defecto --}}
    <div id="planVacio" style="text-align:center;color:#64748b;padding:.5rem 0;font-size:.88rem;">
        Haz clic en <strong>Generar plan</strong> para crear una combinación aleatoria con 20% de descuento.
    </div>
</div>

{{-- ══════════════════════════════════════
     PLANES SUGERIDOS GUARDADOS
══════════════════════════════════════ --}}
@if(isset($planes) && $planes->count() > 0)
<div class="admin-section" style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;">
        <i class="fa-solid fa-star" style="color:#fbbf24;"></i> Planes Sugeridos
        <span class="badge badge-info" style="margin-left:.25rem;">{{ $planes->count() }}</span>
    </h2>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
        @foreach($planes as $p)
        <div style="border:1px solid #b7e4c7;border-radius:var(--radius-md);overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.06);">
            <div style="background:var(--green-700);color:#fff;padding:.6rem 1rem;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:.8rem;font-weight:700;">{{ $p->titulo }}</span>
                <form method="POST" action="{{ route('empresa.gastronomia.planes.destroy', $p) }}">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:.9rem;" title="Eliminar plan">
                        <i class="fa-solid fa-trash fa-xs"></i>
                    </button>
                </form>
            </div>
            <div style="padding:.85rem 1rem;font-size:.82rem;display:grid;gap:.3rem;">
                <div><span style="color:#6366f1;font-weight:700;">🎭</span> {{ $p->evento->nombre ?? '—' }}</div>
                <div><span style="color:#f97316;font-weight:700;">🍽</span> {{ $p->gastronomia->nombre ?? '—' }}</div>
                <div><span style="color:#22c55e;font-weight:700;">🏨</span> {{ $p->hotel->nombre ?? '—' }}</div>
                <div><span style="color:#3b82f6;font-weight:700;">📍</span> {{ $p->lugar->nombre ?? '—' }}</div>
            </div>
            <div style="background:#f8fafc;padding:.6rem 1rem;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0;">
                <span style="text-decoration:line-through;color:#94a3b8;font-size:.78rem;">${{ number_format($p->subtotal, 0, ',', '.') }}</span>
                <span style="font-weight:900;color:var(--green-700);font-size:1rem;">${{ number_format($p->precio_final, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif


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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $imgSrc = $item->imagen
                                ? (str_starts_with($item->imagen,'http') ? $item->imagen : Storage::disk('public')->url($item->imagen))
                                : null;
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

// ── Generador de planes ──
function generarPlan() {
    const btn = document.getElementById('btnGenerar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-xs"></i> Generando...';

    fetch('{{ route("empresa.gastronomia.planes.generar") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.error) { alert(d.error); return; }

        document.getElementById('planEvento').textContent      = d.evento.nombre;
        document.getElementById('planGastronomia').textContent = d.gastronomia.nombre;
        document.getElementById('planHotel').textContent       = d.hotel.nombre;
        document.getElementById('planLugar').textContent       = d.lugar.nombre;
        document.getElementById('planSubtotal').textContent    = 'Antes: $' + d.subtotal.toLocaleString('es-CO');
        document.getElementById('planPrecioFinal').textContent = '$' + d.precioFinal.toLocaleString('es-CO');

        document.getElementById('inputEventoId').value      = d.evento.id;
        document.getElementById('inputGastronomiaId').value = d.gastronomia.id;
        document.getElementById('inputHotelId').value       = d.hotel.id;
        document.getElementById('inputLugarId').value       = d.lugar.id;
        document.getElementById('inputSubtotal').value      = d.subtotal;
        document.getElementById('inputDescuento').value     = d.descuento;
        document.getElementById('inputPrecioFinal').value   = d.precioFinal;

        document.getElementById('planVacio').style.display    = 'none';
        document.getElementById('planGenerado').style.display = 'block';
        document.getElementById('btnLimpiar').style.display   = 'inline-flex';
    })
    .catch(() => alert('Error al generar el plan.'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-dice fa-xs"></i> Generar plan';
    });
}

function limpiarPlan() {
    document.getElementById('planGenerado').style.display = 'none';
    document.getElementById('planVacio').style.display    = 'block';
    document.getElementById('btnLimpiar').style.display   = 'none';
}

// Modal plato
</script>
@endpush

@endsection