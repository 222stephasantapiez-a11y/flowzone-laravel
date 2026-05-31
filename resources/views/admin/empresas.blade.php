@extends('layouts.admin')

@section('title', 'Empresas')
@section('page-title', 'Empresas')
@section('page-subtitle', 'Aprueba, edita y administra las empresas registradas')

@section('content')

{{-- Notificaciones --}}
@if($notifCount > 0)
<div class="dash-card" style="border-left:4px solid var(--warning);margin-bottom:1.5rem;">
    <div class="dash-card-header">
        <h2>
            <i class="fa-solid fa-bell" style="color:var(--warning);margin-right:6px"></i>
            Solicitudes pendientes
            <span class="badge badge-warning">{{ $notifCount }}</span>
        </h2>
        <form method="POST" action="{{ route('admin.notificaciones.leer-todas') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-small btn-edit">
                Marcar todas leídas
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Solicitud</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notificaciones as $notif)
                <tr>
                    <td><strong>{{ $notif->empresa->nombre ?? '—' }}</strong></td>
                    <td>{{ $notif->mensaje }}</td>
                    <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                    <td style="min-width:260px;">
                        <div style="display:flex;flex-direction:column;gap:.5rem;">
                            <form method="POST" action="{{ route('admin.notificaciones.responder', $notif) }}">
                                @csrf
                                <div style="border:1.5px solid var(--gray-200);border-radius:.65rem;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                                    <textarea name="respuesta" rows="2" minlength="5" maxlength="1000" required
                                        placeholder="Escribe tu respuesta..."
                                        style="width:100%;border:none;outline:none;resize:none;padding:.6rem .75rem;font-size:.82rem;color:var(--gray-700);background:#fff;font-family:inherit;box-sizing:border-box;"></textarea>
                                    <div style="background:var(--gray-50);border-top:1px solid var(--gray-200);padding:.35rem .6rem;display:flex;justify-content:flex-end;gap:.4rem;">
                                        <form method="POST" action="{{ route('admin.notificaciones.leer', $notif) }}">
                                            @csrf @method('PATCH')
                                            <button class="btn-small btn-success">
                                                <i class="fa-solid fa-check fa-xs"></i> Leída
                                            </button>
                                        </form>
                                        <button type="submit" class="btn btn-primary btn-sm" style="font-size:.78rem;padding:.28rem .8rem;">
                                            <i class="fa-solid fa-paper-plane fa-xs"></i> Responder
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ===================== MODAL EDITAR ===================== --}}
<div id="modal-empresa" style="
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
        <div style="
            background: linear-gradient(135deg, var(--red-900), var(--red-700));
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <h3 style="color:#fff;font-size:1.05rem;font-weight:700;margin:0;display:flex;align-items:center;gap:.5rem;">
                <i class="fa-solid fa-pen-to-square"></i>
                Editar Empresa
            </h3>
            <button onclick="cerrarModal()" style="
                background: rgba(255,255,255,.15);
                border: none; color: #fff;
                width: 32px; height: 32px;
                border-radius: 50%; cursor: pointer;
                font-size: 1rem; display: flex;
                align-items: center; justify-content: center;
                transition: background .2s;
            " onmouseover="this.style.background='rgba(255,255,255,.3)'"
               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 1.75rem;">
            @isset($empresa)
                <form method="POST" action="{{ route('admin.empresas.update', $empresa) }}" class="admin-form" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $empresa->nombre) }}">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de empresa</label>
                        <select name="tipo_empresa">
                            <option value="">— Selecciona —</option>
                            @foreach(['hotel'=>'🏨 Hotel/Hospedaje','restaurante'=>'🍽️ Restaurante','agencia_turismo'=>'🧭 Agencia de turismo','transporte'=>'🚌 Transporte','artesanias'=>'🎨 Artesanías','otro'=>'📦 Otro'] as $val => $label)
                                <option value="{{ $val }}" {{ old('tipo_empresa', $empresa->tipo_empresa) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>NIT</label>
                        <input type="text" name="nit" value="{{ old('nit', $empresa->nit) }}" maxlength="20">
                    </div>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3" maxlength="1000">{{ old('descripcion', $empresa->descripcion) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Servicios</label>
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                        @php $serviciosActuales = old('servicios', $empresa->servicios ?? []); @endphp
                        @foreach(['WiFi','Parqueadero','Restaurante propio','Piscina','Eventos','Guía turístico','Reservas online','Domicilios','Sala de conferencias','Pet friendly'] as $srv)
                        <label style="display:flex;align-items:center;gap:.3rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.3rem .65rem;font-size:.82rem;cursor:pointer;">
                            <input type="checkbox" name="servicios[]" value="{{ $srv }}"
                                   {{ in_array($srv, $serviciosActuales) ? 'checked' : '' }}
                                   style="accent-color:var(--green-700);">
                            {{ $srv }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Sitio web</label>
                        <input type="url" name="sitio_web" value="{{ old('sitio_web', $empresa->sitio_web) }}" maxlength="300">
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <input type="text" name="instagram" value="{{ old('instagram', $empresa->instagram) }}" maxlength="200">
                    </div>
                </div>

                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="{{ old('facebook', $empresa->facebook) }}" maxlength="200">
                </div>

                <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Actualizar Empresa
                    </button>
                    <a href="{{ route('admin.empresas.index') }}" class="btn btn-outline">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                </div>
                </form>
            @endisset
        </div>
    </div>
</div>

{{-- TABLA --}}
<div class="admin-section">

    <div class="admin-section-header">
        <h2>Empresas Registradas</h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
            <span class="badge badge-info">{{ $empresas->total() }} total</span>
            <button type="button" onclick="toggleFiltrosEmpresas()" class="btn btn-success btn-sm">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
            <a href="{{ route('admin.empresas.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.empresas.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
       
        </div>
    </div>

    <div id="filtrosEmpresas" style="display:none; padding: 1rem 0 .5rem;">
        <div style="background:#fff;border-radius:.75rem;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:1rem 1.25rem;margin-bottom:1rem;">
            <form method="GET" action="{{ route('admin.empresas.index') }}"
                  style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem;align-items:end;">
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem;">Nombre empresa</label>
                    <input type="text" name="busqueda" value="{{ request('busqueda') }}" placeholder="Buscar por nombre..."
                           style="width:100%;border-radius:.5rem;border:1px solid #d1d5db;background:#fff;padding:.5rem .85rem;font-size:.875rem;color:#374151;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem;">Responsable / correo</label>
                    <input type="text" name="responsable" value="{{ request('responsable') }}" placeholder="Nombre o email..."
                           style="width:100%;border-radius:.5rem;border:1px solid #d1d5db;background:#fff;padding:.5rem .85rem;font-size:.875rem;color:#374151;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.35rem;">Estado</label>
                    <select name="estado" style="width:100%;border-radius:.5rem;border:1px solid #d1d5db;background:#fff;padding:.5rem .85rem;font-size:.875rem;color:#374151;outline:none;">
                        <option value="">Todos</option>
                        <option value="aprobado"  {{ request('estado')==='aprobado'?'selected':'' }}>Aprobada</option>
                        <option value="pendiente" {{ request('estado')==='pendiente'?'selected':'' }}>Pendiente</option>
                    </select>
                </div>
                <div style="display:flex;gap:.5rem;align-items:flex-end;">
                    <button type="submit" style="display:inline-flex;align-items:center;gap:.4rem;background:#16a34a;color:#fff;font-size:.875rem;font-weight:700;padding:.5rem 1.1rem;border-radius:.5rem;border:none;cursor:pointer;">
                        <i class="fa-solid fa-magnifying-glass fa-xs"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.empresas.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;background:#f3f4f6;color:#374151;font-size:.875rem;font-weight:700;padding:.5rem 1.1rem;border-radius:.5rem;text-decoration:none;">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                @php
                    $sort      = $sort ?? 'aprobado';
                    $direction = $direction ?? 'asc';
                @endphp
                <tr>
                    <th>
                        <a href="{{ route('admin.empresas.index', array_merge(request()->all(), ['sort' => 'id', 'direction' => ($sort === 'id' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            # @if($sort === 'id') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('admin.empresas.index', array_merge(request()->all(), ['sort' => 'nombre', 'direction' => ($sort === 'nombre' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Empresa @if($sort === 'nombre') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Responsable</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Tipo</th>
                    <th>NIT</th>
                    <th>
                        <a href="{{ route('admin.empresas.index', array_merge(request()->all(), ['sort' => 'aprobado', 'direction' => ($sort === 'aprobado' && $direction === 'asc') ? 'desc' : 'asc'])) }}"
                           style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                            Estado @if($sort === 'aprobado') <i class="fa-solid fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }} fa-xs"></i> @else <i class="fa-solid fa-sort fa-xs" style="opacity:.35"></i> @endif
                        </a>
                    </th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresas as $e)
                <tr>
                    <td>{{ $e->id }}</td>
                    <td><strong>{{ $e->nombre }}</strong></td>
                    <td>{{ $e->usuario->name ?? '—' }}</td>
                    <td>{{ $e->telefono ?? '—' }}</td>
                    <td>{{ $e->direccion ?? '—' }}</td>
                    <td>
                        @php
                            $tl = ['hotel'=>'🏨 Hotel','restaurante'=>'🍽️ Restaurante','agencia_turismo'=>'🧭 Agencia','transporte'=>'🚌 Transporte','artesanias'=>'🎨 Artesanías','otro'=>'📦 Otro'];
                        @endphp
                        @if($e->tipo_empresa)
                            <span class="badge badge-info">{{ $tl[$e->tipo_empresa] ?? $e->tipo_empresa }}</span>
                        @else
                            <span style="color:var(--gray-400);">—</span>
                        @endif
                    </td>
                    <td>{{ $e->nit ?? '—' }}</td>
                    <td>
                        @if($e->aprobado)
                            <span class="badge badge-success">Aprobada</span>
                        @else
                            <span class="badge badge-warning">Pendiente</span>
                        @endif
                    </td>
                    <td style="min-width:200px;">
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem;align-items:center;">
                            @unless($e->aprobado)
                                <form method="POST" action="{{ route('admin.empresas.aprobar', $e) }}" style="display:contents">
                                    @csrf @method('PATCH')
                                    <button class="btn-small btn-success" style="font-size:.75rem;font-weight:700;padding:.3rem .7rem;border-radius:.4rem;">
                                        <i class="fa-solid fa-check fa-xs"></i> Aprobar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.empresas.rechazar', $e) }}" style="display:contents">
                                    @csrf @method('PATCH')
                                    <button class="btn-small btn-delete" style="font-size:.75rem;font-weight:700;padding:.3rem .7rem;border-radius:.4rem;">
                                        <i class="fa-solid fa-xmark fa-xs"></i> Rechazar
                                    </button>
                                </form>
                            @endunless
                            <a href="{{ route('admin.empresas.edit', $e) }}" class="btn-small btn-edit" style="font-size:.75rem;font-weight:700;padding:.3rem .7rem;border-radius:.4rem;">
                                <i class="fa-solid fa-pen fa-xs"></i> Editar
                            </a>
                            <button type="button" class="btn-small btn-delete" style="font-size:.75rem;font-weight:700;padding:.3rem .7rem;border-radius:.4rem;"
                                    onclick="abrirConfirmEmpresa({{ $e->id }}, '{{ addslashes($e->nombre) }}')">
                                <i class="fa-solid fa-trash fa-xs"></i> Eliminar
                            </button>
                            <form id="form-delete-empresa-{{ $e->id }}" method="POST"
                                  action="{{ route('admin.empresas.destroy', $e) }}"
                                  style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;">
                        No hay empresas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.pagination', ['paginator' => $empresas, 'perPage' => $perPage])

</div>

{{-- MODAL CONFIRMACIÓN ELIMINAR --}}
<div id="modal-confirm-empresa" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="text-align:center;margin-bottom:1.25rem;">
            <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.4rem;"></i>
            </div>
            <h3 style="font-size:1.05rem;font-weight:700;color:#111827;margin-bottom:.4rem;">¿Eliminar empresa?</h3>
            <p style="font-size:.88rem;color:#6b7280;" id="confirm-nombre-empresa"></p>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.5rem;padding:.75rem 1rem;cursor:pointer;margin-bottom:1.25rem;">
            <input type="checkbox" id="confirm-check-empresa" style="accent-color:#dc2626;width:16px;height:16px;">
            <span style="font-size:.85rem;color:#991b1b;font-weight:500;">Entiendo que esta acción no se puede deshacer</span>
        </label>
        <div style="display:flex;gap:.75rem;">
            <button type="button" onclick="cerrarConfirmEmpresa()"
                    style="flex:1;padding:.7rem;border:1.5px solid #e5e7eb;border-radius:.5rem;background:#fff;cursor:pointer;font-size:.88rem;font-weight:600;color:#374151;">
                Cancelar
            </button>
            <button type="button" id="btn-confirmar-delete-empresa" onclick="ejecutarDeleteEmpresa()" disabled
                    style="flex:1;padding:.7rem;border:none;border-radius:.5rem;background:#dc2626;color:#fff;cursor:pointer;font-size:.88rem;font-weight:600;opacity:.5;transition:opacity .2s;">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function cerrarModal() {
    document.getElementById('modal-empresa').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modal-empresa').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
        cerrarConfirmEmpresa();
    }
});

@isset($empresa)
    document.getElementById('modal-empresa').style.display = 'block';
    document.body.style.overflow = 'hidden';
@endisset

// ── Confirmar eliminar empresa ──
let deleteEmpresaId = null;

function abrirConfirmEmpresa(id, nombre) {
    deleteEmpresaId = id;
    document.getElementById('confirm-nombre-empresa').textContent = 'Vas a eliminar: ' + nombre;
    document.getElementById('confirm-check-empresa').checked = false;
    document.getElementById('btn-confirmar-delete-empresa').disabled = true;
    document.getElementById('btn-confirmar-delete-empresa').style.opacity = '.5';
    const modal = document.getElementById('modal-confirm-empresa');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function cerrarConfirmEmpresa() {
    deleteEmpresaId = null;
    document.getElementById('modal-confirm-empresa').style.display = 'none';
    document.body.style.overflow = '';
}

function ejecutarDeleteEmpresa() {
    if (deleteEmpresaId) document.getElementById('form-delete-empresa-' + deleteEmpresaId).submit();
}

document.getElementById('confirm-check-empresa').addEventListener('change', function () {
    const btn = document.getElementById('btn-confirmar-delete-empresa');
    btn.disabled = !this.checked;
    btn.style.opacity = this.checked ? '1' : '.5';
});

document.getElementById('modal-confirm-empresa').addEventListener('click', function (e) {
    if (e.target === this) cerrarConfirmEmpresa();
});

function toggleFiltrosEmpresas() {
    const box = document.getElementById('filtrosEmpresas');
    box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
}

window.addEventListener('load', function () {
    if ("{{ request('busqueda') }}" || "{{ request('responsable') }}" || "{{ request('estado') }}") {
        document.getElementById('filtrosEmpresas').style.display = 'block';
    }
});
</script>
@endpush

@endsection