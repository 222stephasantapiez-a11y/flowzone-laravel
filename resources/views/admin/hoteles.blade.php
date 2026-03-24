<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Hoteles - FlowZone Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="admin-layout">

    {{-- Sidebar --}}
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <h2>FlowZone</h2>
            <span>Panel de Administración</span>
        </div>
        <nav class="admin-nav">
            <div class="nav-section-label">Principal</div>
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <div class="nav-section-label">Gestión</div>
            <a href="{{ route('admin.hoteles.index') }}" class="active">
                <i class="fa-solid fa-hotel"></i> Hoteles
            </a>
            <a href="{{ route('admin.lugares.index') }}">
                <i class="fa-solid fa-map-location-dot"></i> Lugares
            </a>
            <a href="{{ route('admin.eventos.index') }}">
                <i class="fa-solid fa-calendar-days"></i> Eventos
            </a>
            <a href="{{ route('admin.reservas.index') }}">
                <i class="fa-solid fa-clipboard-list"></i> Reservas
            </a>
            <a href="{{ route('admin.empresas.index') }}">
                <i class="fa-solid fa-building"></i> Empresas
            </a>
            <div class="nav-section-label" style="margin-top:auto;padding-top:1.5rem;">Sesión</div>
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-globe"></i> Ver sitio
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <div class="topbar-title">
                <h1>Gestión de Hoteles</h1>
                <p>Agrega, edita o elimina hoteles del sistema</p>
            </div>
        </div>

        <div class="admin-main-inner">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            {{-- Formulario crear / editar --}}
            <div class="admin-section">
                @isset($hotel)
                    <h2>Editar Hotel: {{ $hotel->nombre }}</h2>
                    <form method="POST" action="{{ route('admin.hoteles.update', $hotel) }}" class="admin-form">
                    @method('PUT')
                @else
                    <h2>Agregar Nuevo Hotel</h2>
                    <form method="POST" action="{{ route('admin.hoteles.store') }}" class="admin-form">
                @endisset
                @csrf

                {{-- Fila 1: nombre y precio --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" required maxlength="150"
                               placeholder="Ej: Hotel Campestre El Paraíso"
                               value="{{ old('nombre', $hotel->nombre ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Precio por noche (COP) *</label>
                        <input type="number" name="precio" required min="0" step="1000"
                               placeholder="Ej: 120000"
                               value="{{ old('precio', $hotel->precio ?? '') }}">
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="form-group">
                    <label>Descripción *</label>
                    <textarea name="descripcion" rows="3" required
                              placeholder="Describe el hotel, sus características y entorno...">{{ old('descripcion', $hotel->descripcion ?? '') }}</textarea>
                </div>

                {{-- Fila 2: ubicación y capacidad --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion" maxlength="200"
                               placeholder="Ej: Km 2 Vía Ortega-Chaparral"
                               value="{{ old('ubicacion', $hotel->ubicacion ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Capacidad (personas)</label>
                        <input type="number" name="capacidad" min="1"
                               placeholder="Ej: 50"
                               value="{{ old('capacidad', $hotel->capacidad ?? '') }}">
                    </div>
                </div>

                {{-- Fila 3: coordenadas --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Latitud <small style="color:var(--gray)">(opcional · entre -90 y 90, ej: 4.711000)</small></label>
                        <input type="number" step="0.000001" name="latitud"
                               min="-90" max="90"
                               placeholder="4.711000"
                               value="{{ old('latitud', $hotel->latitud ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Longitud <small style="color:var(--gray)">(opcional · entre -180 y 180, ej: -74.072100)</small></label>
                        <input type="number" step="0.000001" name="longitud"
                               min="-180" max="180"
                               placeholder="-74.072100"
                               value="{{ old('longitud', $hotel->longitud ?? '') }}">
                    </div>
                </div>

                {{-- Servicios --}}
                <div class="form-group">
                    <label>Servicios <small style="color:var(--gray)">(separados por coma)</small></label>
                    <input type="text" name="servicios"
                           placeholder="Ej: WiFi, Piscina, Parqueadero, Restaurante"
                           value="{{ old('servicios', $hotel->servicios ?? '') }}">
                </div>

                {{-- Fila 4: teléfono y email --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" maxlength="20"
                               placeholder="Ej: 3201234567"
                               value="{{ old('telefono', $hotel->telefono ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" maxlength="150"
                               placeholder="hotel@correo.com"
                               value="{{ old('email', $hotel->email ?? '') }}">
                    </div>
                </div>

                {{-- URL imagen --}}
                <div class="form-group">
                    <label>URL de imagen *</label>
                    <input type="url" name="imagen" required
                           placeholder="https://..."
                           value="{{ old('imagen', $hotel->imagen ?? '') }}"
                           id="input-imagen">
                    <div id="preview-imagen" style="margin-top:.6rem;display:none;">
                        <img id="img-preview" src="" alt="Vista previa"
                             style="max-height:160px;border-radius:8px;border:1px solid var(--borde,#dde5df);">
                    </div>
                </div>

                {{-- Disponibilidad --}}
                <div class="form-group" style="display:flex;align-items:center;gap:.6rem;">
                    <input type="checkbox" name="disponibilidad" id="disponibilidad"
                           style="width:18px;height:18px;accent-color:var(--primary);"
                           {{ old('disponibilidad', $hotel->disponibilidad ?? true) ? 'checked' : '' }}>
                    <label for="disponibilidad" style="margin:0;font-weight:600;cursor:pointer;">
                        Disponible para reservas
                    </label>
                </div>

                <div style="display:flex;gap:.8rem;margin-top:.5rem;">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($hotel) ? 'Actualizar Hotel' : 'Guardar Hotel' }}
                    </button>
                    @isset($hotel)
                        <a href="{{ route('admin.hoteles.index') }}" class="btn btn-secondary">Cancelar</a>
                    @endisset
                </div>

                </form>
            </div>

            {{-- Tabla de hoteles --}}
            <div class="admin-section">
                <h2>Hoteles Registrados ({{ $hoteles->count() }})</h2>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Precio / noche</th>
                                <th>Ubicación</th>
                                <th>Capacidad</th>
                                <th>Disponible</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hoteles as $h)
                                <tr>
                                    <td>{{ $h->id }}</td>
                                    <td>
                                        @if($h->imagen)
                                            <img src="{{ $h->imagen }}" alt="{{ $h->nombre }}"
                                                 style="width:60px;height:45px;object-fit:cover;border-radius:6px;"
                                                 onerror="this.style.display='none'">
                                        @else
                                            <span style="color:var(--gray);font-size:.8rem;">Sin imagen</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $h->nombre }}</strong></td>
                                    <td>${{ number_format($h->precio, 0, ',', '.') }}</td>
                                    <td>{{ $h->ubicacion ?? '—' }}</td>
                                    <td>{{ $h->capacidad ?? '—' }}</td>
                                    <td>
                                        @if($h->disponibilidad)
                                            <span class="badge" style="background:#d4edda;color:#155724;">Disponible</span>
                                        @else
                                            <span class="badge" style="background:#f8d7da;color:#721c24;">No disponible</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.hoteles.edit', $h) }}"
                                           class="btn-small btn-edit">Editar</a>

                                        <form method="POST"
                                              action="{{ route('admin.hoteles.destroy', $h) }}"
                                              style="display:inline"
                                              onsubmit="return confirm('¿Eliminar {{ addslashes($h->nombre) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-small btn-delete">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center;color:var(--gray);padding:2rem;">
                                        No hay hoteles registrados aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
// Vista previa de imagen al escribir la URL
const inputImagen  = document.getElementById('input-imagen');
const previewWrap  = document.getElementById('preview-imagen');
const imgPreview   = document.getElementById('img-preview');

function actualizarPreview() {
    const url = inputImagen.value.trim();
    if (url) {
        imgPreview.src = url;
        previewWrap.style.display = 'block';
    } else {
        previewWrap.style.display = 'none';
    }
}

inputImagen.addEventListener('input', actualizarPreview);
// Mostrar preview si ya hay valor (edición)
if (inputImagen.value) actualizarPreview();
</script>
</body>
</html>
