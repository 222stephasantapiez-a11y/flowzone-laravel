@extends('layouts.empresa')

@section('page-title', 'Paquetes Turísticos')
@section('page-subtitle', 'Crea y gestiona tus paquetes de turismo')

@section('topbar-actions')
    <button type="button" onclick="abrirFormulario()"
            class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus fa-xs"></i> Nuevo paquete
    </button>
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $incluyeOpciones  = ['Transporte','Guía certificado','Almuerzo','Desayuno','Cena','Seguro de vida','Equipo especializado','Hospedaje'];
    $llevarOpciones   = ['Ropa cómoda','Zapatos de senderismo','Protector solar','Repelente','Agua','Cámara fotográfica','Documento de identidad'];
@endphp

{{-- Formulario crear/editar (colapsable) --}}
<div id="secFormulario" style="display:none;margin-bottom:1.5rem;">
<div class="admin-section">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <h2 id="formTitulo" style="font-size:1.05rem;font-weight:700;color:var(--gray-900);margin:0;">Nuevo paquete</h2>
        <button onclick="cerrarFormulario()" style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--gray-400);">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form id="formPaquete" method="POST" action="{{ route('empresa.paquetes.store') }}"
          enctype="multipart/form-data" class="admin-form">
        @csrf
        <span id="paqueteMethod"></span>

        <div class="form-row">
            <div class="form-group" style="flex:2;">
                <label>Nombre del paquete *</label>
                <input type="text" name="nombre" id="p_nombre" required maxlength="200" placeholder="Ej: Aventura en Ortega">
            </div>
            <div class="form-group">
                <label>Dificultad</label>
                <select name="dificultad" id="p_dificultad">
                    <option value="">— Selecciona —</option>
                    <option value="facil">🟢 Fácil</option>
                    <option value="moderado">🟡 Moderado</option>
                    <option value="dificil">🔴 Difícil</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" id="p_descripcion" rows="3" required placeholder="Describe el paquete..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Duración (días) *</label>
                <input type="number" name="duracion_dias" id="p_dias" required min="1" value="1">
            </div>
            <div class="form-group">
                <label>Duración (horas adicionales)</label>
                <input type="number" name="duracion_horas" id="p_horas" min="0" placeholder="Opcional">
            </div>
            <div class="form-group">
                <label>Hora de salida</label>
                <input type="time" name="hora_salida" id="p_hora_salida">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Precio adulto (COP) *</label>
                <input type="number" name="precio_adulto" id="p_precio_adulto" required min="0" step="0.01" placeholder="Ej: 150000">
            </div>
            <div class="form-group">
                <label>Precio niño (COP)</label>
                <input type="number" name="precio_nino" id="p_precio_nino" min="0" step="0.01" placeholder="Opcional">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Cupo máximo *</label>
                <input type="number" name="cupo_maximo" id="p_cupo_max" required min="1" value="10">
            </div>
            <div class="form-group">
                <label>Cupo mínimo *</label>
                <input type="number" name="cupo_minimo" id="p_cupo_min" required min="1" value="1">
            </div>
            <div class="form-group">
                <label>Cupo disponible *</label>
                <input type="number" name="cupo_disponible" id="p_cupo_disp" required min="0" value="10">
            </div>
        </div>

        <div class="form-group">
            <label>Punto de salida</label>
            <input type="text" name="punto_salida" id="p_punto_salida" maxlength="300" placeholder="Ej: Parque principal de Ortega">
        </div>

        <div class="form-group">
            <label>Itinerario</label>
            <textarea name="itinerario" id="p_itinerario" rows="4" placeholder="Describe el itinerario general del paquete..."></textarea>
        </div>

        {{-- Ruta día a día --}}
        <div class="form-group">
            <label>Ruta día a día</label>
            <div id="rutaContainer" style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:.5rem;"></div>
            <button type="button" onclick="agregarDia()"
                    style="display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .9rem;font-size:.82rem;font-weight:600;border-radius:var(--radius-full);border:1.5px solid var(--green-700);background:#fff;color:var(--green-700);cursor:pointer;">
                <i class="fa-solid fa-plus fa-xs"></i> Agregar día
            </button>
            <input type="hidden" name="ruta_json" id="ruta_json">
        </div>

        {{-- Qué incluye --}}
        <div class="form-group">
            <label>Qué incluye</label>
            <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                @foreach($incluyeOpciones as $inc)
                <label style="display:flex;align-items:center;gap:.3rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.3rem .65rem;font-size:.82rem;cursor:pointer;">
                    <input type="checkbox" name="incluye[]" value="{{ $inc }}" class="incluye-check" style="accent-color:var(--green-700);">
                    {{ $inc }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Qué NO incluye --}}
        <div class="form-group">
            <label>Qué NO incluye <small style="font-weight:400;color:var(--gray-400);">(texto libre)</small></label>
            <input type="hidden" name="no_incluye_json" id="no_incluye_json">
            <div id="noIncluyeContainer" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.4rem;"></div>
            <div style="display:flex;gap:.4rem;">
                <input type="text" id="noIncluyeInput" placeholder="Ej: Hospedaje"
                       style="flex:1;padding:.45rem .8rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.85rem;outline:none;">
                <button type="button" onclick="agregarNoIncluye()"
                        style="padding:.45rem .9rem;font-size:.82rem;font-weight:600;border-radius:var(--radius-md);border:none;background:var(--gray-200);color:var(--gray-700);cursor:pointer;">
                    + Agregar
                </button>
            </div>
        </div>

        {{-- Qué llevar --}}
        <div class="form-group">
            <label>Qué llevar</label>
            <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                @foreach($llevarOpciones as $ll)
                <label style="display:flex;align-items:center;gap:.3rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.3rem .65rem;font-size:.82rem;cursor:pointer;">
                    <input type="checkbox" name="que_llevar[]" value="{{ $ll }}" class="llevar-check" style="accent-color:var(--green-700);">
                    {{ $ll }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Fechas disponibles --}}
        <div class="form-group">
            <label>Fechas disponibles</label>
            <input type="hidden" name="fechas_disponibles_json" id="fechas_disponibles_json">
            <div id="fechasContainer" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.4rem;"></div>
            <div style="display:flex;gap:.4rem;">
                <input type="date" id="fechaInput"
                       style="padding:.45rem .8rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.85rem;outline:none;">
                <button type="button" onclick="agregarFecha()"
                        style="padding:.45rem .9rem;font-size:.82rem;font-weight:600;border-radius:var(--radius-md);border:none;background:var(--gray-200);color:var(--gray-700);cursor:pointer;">
                    + Agregar
                </button>
            </div>
        </div>

        {{-- Imagen --}}
        <div class="form-group">
            <label>Imagen</label>
            <div id="imgPreviewWrap" style="margin-bottom:.5rem;display:none;">
                <img id="imgPreviewActual" src="" alt="Imagen actual"
                     style="width:100px;height:70px;object-fit:cover;border-radius:var(--radius-md);border:1.5px solid var(--gray-200);">
            </div>
            <input type="file" name="imagen_file" accept="image/*" style="margin-bottom:.5rem;">
            <input type="url" name="imagen_url" id="p_imagen_url" placeholder="O pega una URL: https://..."
                   style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-family:var(--font-body);font-size:.9rem;outline:none;">
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:.5rem;">
            <input type="checkbox" name="activo" id="p_activo" value="1" checked style="accent-color:var(--green-700);width:16px;height:16px;">
            <label for="p_activo" style="cursor:pointer;font-size:.9rem;font-weight:500;margin:0;">Paquete activo</label>
        </div>

        <div style="display:flex;gap:.75rem;margin-top:.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar paquete
            </button>
            <button type="button" onclick="cerrarFormulario()" class="btn btn-outline">Cancelar</button>
        </div>
    </form>
</div>
</div>

{{-- Cards de paquetes --}}
<div class="admin-section">
    <h2 style="font-size:1.05rem;font-weight:700;color:var(--gray-900);margin-bottom:1.25rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-map-location-dot" style="color:var(--green-600);"></i> Mis paquetes
        <span class="badge badge-info">{{ $paquetes->count() }}</span>
    </h2>

    @if($paquetes->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-map-location-dot"></i>
            <p>No has creado paquetes turísticos aún.</p>
        </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
        @foreach($paquetes as $p)
        @php
            $imgSrc = $p->imagen
                ? (Str::startsWith($p->imagen,'http') ? $p->imagen : Storage::url($p->imagen))
                : null;
        @endphp
        <div style="border:1px solid var(--gray-200);border-radius:var(--radius-lg);overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.06);">
            {{-- Imagen --}}
            @if($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $p->nombre }}"
                 style="width:100%;height:160px;object-fit:cover;">
            @else
            <div style="width:100%;height:100px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;color:var(--gray-400);">
                <i class="fa-solid fa-image fa-2x"></i>
            </div>
            @endif

            <div style="padding:1rem;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;margin-bottom:.5rem;">
                    <h3 style="font-size:.95rem;font-weight:700;color:var(--gray-900);margin:0;">{{ $p->nombre }}</h3>
                    @if($p->activo)
                        <span class="badge badge-success" style="white-space:nowrap;">Activo</span>
                    @else
                        <span class="badge badge-warning" style="white-space:nowrap;">Inactivo</span>
                    @endif
                </div>

                <div style="font-size:.82rem;color:var(--gray-500);margin-bottom:.75rem;display:flex;flex-wrap:wrap;gap:.4rem;">
                    <span><i class="fa-solid fa-clock fa-xs"></i> {{ $p->duracion_dias }} día(s)</span>
                    @if($p->dificultad)
                    <span>·
                        @php $dif = ['facil'=>'🟢 Fácil','moderado'=>'🟡 Moderado','dificil'=>'🔴 Difícil']; @endphp
                        {{ $dif[$p->dificultad] ?? $p->dificultad }}
                    </span>
                    @endif
                    <span>· <i class="fa-solid fa-users fa-xs"></i> {{ $p->cupo_disponible }}/{{ $p->cupo_maximo }} cupos</span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                    <div>
                        <div style="font-size:.72rem;color:var(--gray-400);">Adulto</div>
                        <div style="font-weight:700;color:var(--green-700);font-size:1rem;">${{ number_format($p->precio_adulto, 0) }}</div>
                    </div>
                    @if($p->precio_nino)
                    <div>
                        <div style="font-size:.72rem;color:var(--gray-400);">Niño</div>
                        <div style="font-weight:700;color:var(--gray-700);">${{ number_format($p->precio_nino, 0) }}</div>
                    </div>
                    @endif
                </div>

                <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                    <button onclick="editarPaquete({{ $p->id }}, {{ json_encode($p) }})"
                            class="btn-small btn-edit">
                        <i class="fa-solid fa-pen fa-xs"></i> Editar
                    </button>
                    <form method="POST" action="{{ route('empresa.paquetes.toggle', $p) }}" style="display:inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-small {{ $p->activo ? 'btn-warning' : 'btn-success' }}">
                            <i class="fa-solid fa-{{ $p->activo ? 'eye-slash' : 'eye' }} fa-xs"></i>
                            {{ $p->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('empresa.paquetes.destroy', $p) }}" style="display:inline"
                          onsubmit="return confirm('¿Eliminar este paquete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-small btn-delete">
                            <i class="fa-solid fa-trash fa-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script>
// ── Estado de listas dinámicas ──
let rutaDias    = [];
let noIncluye   = [];
let fechas      = [];

function abrirFormulario(reset = true) {
    if (reset) {
        document.getElementById('formPaquete').action = "{{ route('empresa.paquetes.store') }}";
        document.getElementById('paqueteMethod').innerHTML = '';
        document.getElementById('formTitulo').textContent = 'Nuevo paquete';
        document.getElementById('formPaquete').reset();
        rutaDias = []; noIncluye = []; fechas = [];
        renderRuta(); renderNoIncluye(); renderFechas();
        document.getElementById('imgPreviewWrap').style.display = 'none';
        document.querySelectorAll('.incluye-check,.llevar-check').forEach(c => c.checked = false);
    }
    document.getElementById('secFormulario').style.display = 'block';
    document.getElementById('secFormulario').scrollIntoView({behavior:'smooth'});
}

function cerrarFormulario() {
    document.getElementById('secFormulario').style.display = 'none';
}

// ── Ruta día a día ──
function agregarDia() {
    rutaDias.push({dia: rutaDias.length + 1, lugar: '', actividad: '', duracion: ''});
    renderRuta();
}

function renderRuta() {
    const c = document.getElementById('rutaContainer');
    c.innerHTML = rutaDias.map((d, i) => `
        <div style="display:grid;grid-template-columns:60px 1fr 1fr 100px 32px;gap:.4rem;align-items:center;">
            <input type="number" value="${d.dia}" min="1" placeholder="Día"
                   onchange="rutaDias[${i}].dia=+this.value;syncRuta()"
                   style="padding:.4rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-size:.82rem;outline:none;">
            <input type="text" value="${d.lugar}" placeholder="Lugar"
                   onchange="rutaDias[${i}].lugar=this.value;syncRuta()"
                   style="padding:.4rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-size:.82rem;outline:none;">
            <input type="text" value="${d.actividad}" placeholder="Actividad"
                   onchange="rutaDias[${i}].actividad=this.value;syncRuta()"
                   style="padding:.4rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-size:.82rem;outline:none;">
            <input type="text" value="${d.duracion}" placeholder="Duración"
                   onchange="rutaDias[${i}].duracion=this.value;syncRuta()"
                   style="padding:.4rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-size:.82rem;outline:none;">
            <button type="button" onclick="rutaDias.splice(${i},1);renderRuta()"
                    style="background:#fee2e2;border:none;border-radius:var(--radius-sm);color:#dc2626;cursor:pointer;padding:.3rem .5rem;">
                <i class="fa-solid fa-xmark fa-xs"></i>
            </button>
        </div>
    `).join('');
    syncRuta();
}

function syncRuta() {
    document.getElementById('ruta_json').value = JSON.stringify(rutaDias);
}

// ── No incluye ──
function agregarNoIncluye() {
    const v = document.getElementById('noIncluyeInput').value.trim();
    if (!v) return;
    noIncluye.push(v);
    document.getElementById('noIncluyeInput').value = '';
    renderNoIncluye();
}

function renderNoIncluye() {
    const c = document.getElementById('noIncluyeContainer');
    c.innerHTML = noIncluye.map((v, i) => `
        <span style="background:#fee2e2;color:#dc2626;border-radius:2rem;padding:.25rem .7rem;font-size:.8rem;display:flex;align-items:center;gap:.3rem;">
            ${v}
            <button type="button" onclick="noIncluye.splice(${i},1);renderNoIncluye()"
                    style="background:none;border:none;cursor:pointer;color:#dc2626;font-size:.75rem;padding:0;">✕</button>
        </span>
    `).join('');
    document.getElementById('no_incluye_json').value = JSON.stringify(noIncluye);
}

// ── Fechas ──
function agregarFecha() {
    const v = document.getElementById('fechaInput').value;
    if (!v || fechas.includes(v)) return;
    fechas.push(v);
    document.getElementById('fechaInput').value = '';
    renderFechas();
}

function renderFechas() {
    const c = document.getElementById('fechasContainer');
    c.innerHTML = fechas.map((f, i) => `
        <span style="background:#dbeafe;color:#1d4ed8;border-radius:2rem;padding:.25rem .7rem;font-size:.8rem;display:flex;align-items:center;gap:.3rem;">
            ${f}
            <button type="button" onclick="fechas.splice(${i},1);renderFechas()"
                    style="background:none;border:none;cursor:pointer;color:#1d4ed8;font-size:.75rem;padding:0;">✕</button>
        </span>
    `).join('');
    document.getElementById('fechas_disponibles_json').value = JSON.stringify(fechas);
}

// ── Editar paquete ──
function editarPaquete(id, data) {
    document.getElementById('formPaquete').action = "{{ url('empresa/paquetes') }}/" + id;
    document.getElementById('paqueteMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('formTitulo').textContent = 'Editar: ' + data.nombre;

    document.getElementById('p_nombre').value        = data.nombre || '';
    document.getElementById('p_descripcion').value   = data.descripcion || '';
    document.getElementById('p_dias').value          = data.duracion_dias || 1;
    document.getElementById('p_horas').value         = data.duracion_horas || '';
    document.getElementById('p_hora_salida').value   = data.hora_salida || '';
    document.getElementById('p_precio_adulto').value = data.precio_adulto || '';
    document.getElementById('p_precio_nino').value   = data.precio_nino || '';
    document.getElementById('p_cupo_max').value      = data.cupo_maximo || 10;
    document.getElementById('p_cupo_min').value      = data.cupo_minimo || 1;
    document.getElementById('p_cupo_disp').value     = data.cupo_disponible || 0;
    document.getElementById('p_punto_salida').value  = data.punto_salida || '';
    document.getElementById('p_itinerario').value    = data.itinerario || '';
    document.getElementById('p_dificultad').value    = data.dificultad || '';
    document.getElementById('p_activo').checked      = data.activo;
    document.getElementById('p_imagen_url').value    = '';

    // Imagen actual
    if (data.imagen) {
        const src = data.imagen.startsWith('http') ? data.imagen : '/storage/' + data.imagen;
        document.getElementById('imgPreviewActual').src = src;
        document.getElementById('imgPreviewWrap').style.display = 'block';
    } else {
        document.getElementById('imgPreviewWrap').style.display = 'none';
    }

    // Incluye
    const incluyeArr = data.incluye || [];
    document.querySelectorAll('.incluye-check').forEach(c => c.checked = incluyeArr.includes(c.value));

    // Llevar
    const llevarArr = data.que_llevar || [];
    document.querySelectorAll('.llevar-check').forEach(c => c.checked = llevarArr.includes(c.value));

    // Ruta
    rutaDias = data.ruta || [];
    renderRuta();

    // No incluye
    noIncluye = data.no_incluye || [];
    renderNoIncluye();

    // Fechas
    fechas = data.fechas_disponibles || [];
    renderFechas();

    abrirFormulario(false);
}

// Sincronizar antes de submit
document.getElementById('formPaquete').addEventListener('submit', function() {
    syncRuta();
    document.getElementById('no_incluye_json').value = JSON.stringify(noIncluye);
    document.getElementById('fechas_disponibles_json').value = JSON.stringify(fechas);
});
</script>
@endpush

@endsection
