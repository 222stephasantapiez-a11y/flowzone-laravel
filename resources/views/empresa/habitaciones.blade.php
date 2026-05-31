@extends('layouts.empresa')

@section('page-title', 'Habitaciones')
@section('page-subtitle', 'Gestiona las habitaciones de tus hoteles')

@section('topbar-actions')
@endsection

@section('content')

@if($hoteles->count() > 0)
<div class="admin-section" style="margin-bottom:1rem;padding:.75rem 1.25rem;">
    <form method="GET" action="{{ route('empresa.habitaciones.index') }}"
          style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
        <label style="font-size:.85rem;font-weight:600;color:var(--gray-700);">
            <i class="fa-solid fa-hotel fa-xs" style="color:var(--green-600);"></i> Hotel:
        </label>
        <select name="hotel_id" onchange="this.form.submit()"
                style="padding:.45rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;outline:none;min-width:200px;">
            @foreach($hoteles as $h)
                <option value="{{ $h->id }}" {{ $hotelActual?->id == $h->id ? 'selected' : '' }}>
                    {{ $h->nombre }}
                </option>
            @endforeach
        </select>
    </form>
</div>
@endif

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:1rem;">
    <i class="fa-solid fa-circle-check fa-xs"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error" style="margin-bottom:1rem;">
    <i class="fa-solid fa-circle-exclamation fa-xs"></i> {{ session('error') }}
</div>
@endif

@if(!$hotelActual)
<div class="admin-section">
    <div class="empty-state">
        <i class="fa-solid fa-hotel"></i>
        <p>No hay hotel vinculado a tu empresa.</p>
        <p style="font-size:.85rem;color:var(--gray-400);">Contacta al administrador para vincular un hotel a tu cuenta.</p>
    </div>
</div>
@else

@php
    $total       = $habitaciones->count();
    $disponibles = $habitaciones->where('disponible', true)->count();
    $ocupadas    = $total - $disponibles;
@endphp
<div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.25rem;">
    <span style="background:var(--gray-100);border-radius:2rem;padding:.35rem .9rem;font-size:.85rem;font-weight:600;color:var(--gray-700);">
        <i class="fa-solid fa-bed fa-xs"></i> {{ $total }} habitaciones
    </span>
    <span style="background:#dcfce7;border-radius:2rem;padding:.35rem .9rem;font-size:.85rem;font-weight:600;color:#16a34a;">
        <i class="fa-solid fa-circle-check fa-xs"></i> {{ $disponibles }} disponibles
    </span>
    <span style="background:#fee2e2;border-radius:2rem;padding:.35rem .9rem;font-size:.85rem;font-weight:600;color:#dc2626;">
        <i class="fa-solid fa-circle-xmark fa-xs"></i> {{ $ocupadas }} ocupadas
    </span>
</div>

{{-- MODAL Confirmar eliminación --}}
<div id="modalConfirmar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:var(--radius-lg);max-width:400px;width:90%;padding:2rem;box-shadow:0 24px 64px rgba(0,0,0,.2);text-align:center;">
        <div style="width:56px;height:56px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="fa-solid fa-trash" style="color:#dc2626;font-size:1.2rem;"></i>
        </div>
        <h3 style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:.5rem;">¿Eliminar habitación?</h3>
        <p style="font-size:.88rem;color:var(--gray-500);margin-bottom:1.5rem;">Esta acción no se puede deshacer.</p>
        <div style="display:flex;gap:.75rem;justify-content:center;">
            <button onclick="cerrarModalConfirmar()" class="btn btn-outline">Cancelar</button>
            <button id="btnConfirmarEliminar" style="background:#dc2626;color:#fff;border:none;padding:.65rem 1.25rem;border-radius:var(--radius-md);font-weight:600;cursor:pointer;font-size:.9rem;">
                <i class="fa-solid fa-trash fa-xs"></i> Sí, eliminar
            </button>
        </div>
    </div>
</div>

{{-- MODAL Editar Hotel --}}
<div id="modalHotel" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;overflow-y:auto;padding:1.5rem 1rem;">
    <div style="background:#fff;border-radius:var(--radius-lg);max-width:640px;margin:0 auto;box-shadow:0 24px 64px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100);">
            <h3 style="margin:0;font-size:1rem;font-weight:700;color:var(--gray-900);">
                <i class="fa-solid fa-pen fa-xs" style="color:var(--green-600);"></i> Editar hotel
            </h3>
            <button onclick="cerrarModalHotel()" style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--gray-400);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('empresa.hoteles.update', $hotelActual) }}"
              enctype="multipart/form-data" class="admin-form" style="padding:1.5rem;">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required value="{{ $hotelActual->nombre }}">
                </div>
                <div class="form-group">
                    <label>Precio por noche (COP) *</label>
                    <input type="number" name="precio" required min="0" step="1000" value="{{ $hotelActual->precio }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Capacidad (personas)</label>
                    <input type="number" name="capacidad" min="1" value="{{ $hotelActual->capacidad }}">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ $hotelActual->telefono }}">
                </div>
            </div>
            <div class="form-group">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" value="{{ $hotelActual->ubicacion }}">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3">{{ $hotelActual->descripcion }}</textarea>
            </div>
            <div class="form-group">
                <label>Servicios <span style="font-size:.78rem;font-weight:400;color:var(--gray-400);">(separados por coma)</span></label>
                <input type="text" name="servicios" placeholder="WiFi, Piscina, Parqueadero..." value="{{ $hotelActual->servicios }}">
            </div>
            <div class="form-group">
                <label>Imagen del hotel</label>
                @if($hotelActual->imagen)
                @php $hImgSrc = str_starts_with($hotelActual->imagen,'http') ? $hotelActual->imagen : asset('storage/'.$hotelActual->imagen); @endphp
                <div style="margin-bottom:.6rem;">
                    <img src="{{ $hImgSrc }}" alt="Imagen actual" style="width:120px;height:80px;object-fit:cover;border-radius:var(--radius-md);border:1.5px solid var(--gray-200);">
                    <div style="font-size:.75rem;color:var(--gray-400);margin-top:.25rem;">Imagen actual</div>
                </div>
                @endif
                <input type="file" name="imagen_file" accept="image/jpg,image/jpeg,image/png,image/webp" style="margin-bottom:.4rem;">
                <input type="url" name="imagen_url" placeholder="O pega una URL: https://..." style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;font-family:var(--font-body);outline:none;">
                <p style="font-size:.75rem;color:var(--gray-400);margin-top:.25rem;">Máx. 4MB — JPG, PNG, WEBP</p>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
                <button type="button" onclick="cerrarModalHotel()" class="btn btn-outline">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL Nueva habitación --}}
<div id="modalNueva" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;overflow-y:auto;padding:1.5rem 1rem;">
    <div style="background:#fff;border-radius:var(--radius-lg);max-width:640px;margin:0 auto;box-shadow:0 24px 64px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100);">
            <h3 style="margin:0;font-size:1rem;font-weight:700;color:var(--gray-900);">
                <i class="fa-solid fa-plus-circle fa-xs" style="color:var(--green-600);"></i> Nueva habitación
            </h3>
            <button onclick="cerrarModalNueva()" style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--gray-400);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('empresa.habitaciones.store', $hotelActual) }}" class="admin-form" style="padding:1.5rem;">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required maxlength="100" placeholder="Ej: Habitación 101" value="{{ old('nombre') }}">
                </div>
                <div class="form-group">
                    <label>Tipo *</label>
                    <select name="tipo" required>
                        @foreach(['sencilla'=>'Sencilla','doble'=>'Doble','triple'=>'Triple','suite'=>'Suite','familiar'=>'Familiar'] as $v => $l)
                        <option value="{{ $v }}" {{ old('tipo') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>N° camas *</label>
                    <input type="number" name="num_camas" required min="1" value="{{ old('num_camas', 1) }}">
                </div>
                <div class="form-group">
                    <label>Tipo de cama *</label>
                    <select name="tipo_cama" required>
                        @foreach(['individual'=>'Individual','doble'=>'Doble','queen'=>'Queen','king'=>'King','mixta'=>'Mixta'] as $v => $l)
                        <option value="{{ $v }}" {{ old('tipo_cama', 'doble') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacidad (personas) *</label>
                    <input type="number" name="capacidad_personas" required min="1" value="{{ old('capacidad_personas', 2) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Precio / noche (COP) *</label>
                    <input type="number" name="precio_noche" required min="0" step="0.01" placeholder="Ej: 120000" value="{{ old('precio_noche') }}">
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:.5rem;">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.9rem;font-weight:500;">
                        <input type="checkbox" name="disponible" value="1" {{ old('disponible', '1') ? 'checked' : '' }} style="accent-color:var(--green-700);width:16px;height:16px;">
                        Disponible
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="2" maxlength="500">{{ old('descripcion') }}</textarea>
            </div>
            <div class="form-group">
                <label>Amenidades</label>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                    @php $amenOld = old('amenidades', []); @endphp
                    @foreach(['TV','Aire acondicionado','Baño privado','Balcón','Nevera','Caja fuerte','Vista al jardín'] as $am)
                    <label style="display:flex;align-items:center;gap:.3rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.3rem .65rem;font-size:.82rem;cursor:pointer;">
                        <input type="checkbox" name="amenidades[]" value="{{ $am }}" {{ in_array($am, $amenOld) ? 'checked' : '' }} style="accent-color:var(--green-700);">
                        {{ $am }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
                <button type="button" onclick="cerrarModalNueva()" class="btn btn-outline">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar habitación</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL Editar habitación --}}
<div id="modalEditar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;overflow-y:auto;padding:1.5rem 1rem;">
    <div style="background:#fff;border-radius:var(--radius-lg);max-width:640px;margin:0 auto;box-shadow:0 24px 64px rgba(0,0,0,.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100);">
            <h3 id="editarTitulo" style="margin:0;font-size:1rem;font-weight:700;color:var(--gray-900);">Editar habitación</h3>
            <button onclick="cerrarModalEditar()" style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--gray-400);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="formEditar" method="POST" class="admin-form" style="padding:1.5rem;">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" id="e_nombre" required maxlength="100">
                </div>
                <div class="form-group">
                    <label>Tipo *</label>
                    <select name="tipo" id="e_tipo" required>
                        @foreach(['sencilla'=>'Sencilla','doble'=>'Doble','triple'=>'Triple','suite'=>'Suite','familiar'=>'Familiar'] as $v => $l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>N° camas *</label>
                    <input type="number" name="num_camas" id="e_num_camas" required min="1">
                </div>
                <div class="form-group">
                    <label>Tipo de cama *</label>
                    <select name="tipo_cama" id="e_tipo_cama" required>
                        @foreach(['individual'=>'Individual','doble'=>'Doble','queen'=>'Queen','king'=>'King','mixta'=>'Mixta'] as $v => $l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacidad (personas) *</label>
                    <input type="number" name="capacidad_personas" id="e_capacidad" required min="1">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Precio / noche (COP) *</label>
                    <input type="number" name="precio_noche" id="e_precio" required min="0" step="0.01">
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:.5rem;">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.9rem;font-weight:500;">
                        <input type="checkbox" name="disponible" id="e_disponible" value="1" style="accent-color:var(--green-700);width:16px;height:16px;">
                        Disponible
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" id="e_descripcion" rows="2" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label>Amenidades</label>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                    @foreach(['TV','Aire acondicionado','Baño privado','Balcón','Nevera','Caja fuerte','Vista al jardín'] as $am)
                    <label style="display:flex;align-items:center;gap:.3rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.3rem .65rem;font-size:.82rem;cursor:pointer;">
                        <input type="checkbox" name="amenidades[]" value="{{ $am }}" class="e-amenidad" style="accent-color:var(--green-700);">
                        {{ $am }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:.5rem;">
                <button type="button" onclick="cerrarModalEditar()" class="btn btn-outline">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk fa-xs"></i> Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- Tabla habitaciones --}}
<div class="admin-section">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.25rem;">
        <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);margin:0;display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-hotel" style="color:var(--green-600);"></i> {{ $hotelActual->nombre }}
            @if($hotelActual->precio > 0)
                <span style="font-size:.8rem;font-weight:600;color:var(--green-700);background:var(--green-50);padding:.2rem .6rem;border-radius:var(--radius-full);">
                    ${{ number_format($hotelActual->precio, 0, ',', '.') }}/noche
                </span>
            @else
                <span style="font-size:.8rem;font-weight:600;color:var(--warning);background:#fef3c7;padding:.2rem .6rem;border-radius:var(--radius-full);">Sin precio</span>
            @endif
        </h2>
        <div style="display:flex;gap:.5rem;">
            <button type="button" onclick="abrirModalHotel()" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-pen fa-xs"></i> Editar hotel
            </button>
            <button type="button" onclick="abrirModalNueva()" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus fa-xs"></i> Nueva habitación
            </button>
        </div>
    </div>

    @php
        $serviciosHotel = $hotelActual->servicios ? array_filter(array_map('trim', explode(',', $hotelActual->servicios))) : [];
    @endphp
    @if(count($serviciosHotel))
    <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.25rem;">
        @foreach($serviciosHotel as $srv)
        <span style="background:#f0fdf4;border:1px solid #b7e4c7;border-radius:2rem;padding:.25rem .75rem;font-size:.8rem;color:var(--green-700);font-weight:600;">{{ $srv }}</span>
        @endforeach
    </div>
    @endif

    @if($habitaciones->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-bed"></i>
            <p>No hay habitaciones registradas. Usa el botón <strong>Nueva habitación</strong> para agregar.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nombre</th><th>Tipo</th><th>Camas</th><th>Capacidad</th>
                    <th>Precio/noche</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($habitaciones as $hab)
                <tr>
                    <td>
                        <strong>{{ $hab->nombre }}</strong>
                        @if($hab->descripcion)
                        <div style="font-size:.75rem;color:var(--gray-400);margin-top:.1rem;">{{ Str::limit($hab->descripcion, 60) }}</div>
                        @endif
                        @if($hab->amenidades && count($hab->amenidades))
                        <div style="display:flex;flex-wrap:wrap;gap:.25rem;margin-top:.3rem;">
                            @foreach($hab->amenidades as $am)
                            <span style="background:var(--gray-100);border-radius:2rem;padding:.1rem .5rem;font-size:.72rem;color:var(--gray-600);">{{ $am }}</span>
                            @endforeach
                        </div>
                        @endif
                    </td>
                    <td><span class="badge badge-info">{{ ucfirst($hab->tipo) }}</span></td>
                    <td style="white-space:nowrap;">{{ $hab->num_camas }} × {{ ucfirst($hab->tipo_cama) }}</td>
                    <td>{{ $hab->capacidad_personas }} pers.</td>
                    <td style="white-space:nowrap;font-weight:600;color:var(--green-700);">${{ number_format($hab->precio_noche, 0) }}</td>
                    <td>
                        @if($hab->disponible)
                            <span class="badge badge-success"><i class="fa-solid fa-circle-check fa-xs"></i> Disponible</span>
                        @else
                            <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark fa-xs"></i> Ocupada</span>
                        @endif
                    </td>
                    <td style="white-space:nowrap;">
                        <button onclick='editarHabitacion({{ $hab->id }}, @json($hab))' class="btn-small btn-edit" title="Editar">
                            <i class="fa-solid fa-pen fa-xs"></i>
                        </button>
                        <form method="POST" action="{{ route('empresa.habitaciones.toggle', $hab) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-small {{ $hab->disponible ? 'btn-warning' : 'btn-success' }}"
                                    title="{{ $hab->disponible ? 'Marcar ocupada' : 'Marcar disponible' }}">
                                <i class="fa-solid fa-{{ $hab->disponible ? 'lock' : 'lock-open' }} fa-xs"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('empresa.habitaciones.destroy', $hab) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmarEliminar(this.closest('form'))"
                                    class="btn-small btn-delete" title="Eliminar">
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

@endif

@push('scripts')
<script>
function abrirModalHotel() { document.getElementById('modalHotel').style.display = 'block'; document.body.style.overflow = 'hidden'; }
function cerrarModalHotel() { document.getElementById('modalHotel').style.display = 'none'; document.body.style.overflow = ''; }
document.getElementById('modalHotel')?.addEventListener('click', function(e) { if (e.target === this) cerrarModalHotel(); });

function abrirModalNueva() { document.getElementById('modalNueva').style.display = 'block'; document.body.style.overflow = 'hidden'; }
function cerrarModalNueva() { document.getElementById('modalNueva').style.display = 'none'; document.body.style.overflow = ''; }
document.getElementById('modalNueva')?.addEventListener('click', function(e) { if (e.target === this) cerrarModalNueva(); });

function abrirModalEditar() { document.getElementById('modalEditar').style.display = 'block'; document.body.style.overflow = 'hidden'; }
function cerrarModalEditar() { document.getElementById('modalEditar').style.display = 'none'; document.body.style.overflow = ''; }
document.getElementById('modalEditar')?.addEventListener('click', function(e) { if (e.target === this) cerrarModalEditar(); });

// ── Modal Confirmar eliminación ──
function confirmarEliminar(form) {
    const modal = document.getElementById('modalConfirmar');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.getElementById('btnConfirmarEliminar').onclick = function() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        form.submit();
    };
}
function cerrarModalConfirmar() {
    document.getElementById('modalConfirmar').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('modalConfirmar')?.addEventListener('click', function(e) {
    if (e.target === this) cerrarModalConfirmar();
});

function editarHabitacion(id, data) {
    document.getElementById('formEditar').action = '{{ url("empresa/habitaciones") }}/' + id;
    document.getElementById('editarTitulo').textContent = 'Editar: ' + data.nombre;
    document.getElementById('e_nombre').value       = data.nombre || '';
    document.getElementById('e_tipo').value         = data.tipo || 'sencilla';
    document.getElementById('e_num_camas').value    = data.num_camas || 1;
    document.getElementById('e_tipo_cama').value    = data.tipo_cama || 'doble';
    document.getElementById('e_capacidad').value    = data.capacidad_personas || 2;
    document.getElementById('e_precio').value       = data.precio_noche || '';
    document.getElementById('e_disponible').checked = !!data.disponible;
    document.getElementById('e_descripcion').value  = data.descripcion || '';
    const amenidades = Array.isArray(data.amenidades) ? data.amenidades : [];
    document.querySelectorAll('.e-amenidad').forEach(c => { c.checked = amenidades.includes(c.value); });
    abrirModalEditar();
}

@if($errors->any() && old('_method') !== 'PUT')
    abrirModalNueva();
@endif

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalHotel();
        cerrarModalNueva();
        cerrarModalEditar();
        cerrarModalConfirmar();
    }
});
</script>
@endpush

@endsection