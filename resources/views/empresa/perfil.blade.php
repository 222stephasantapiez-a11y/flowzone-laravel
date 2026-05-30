@extends('layouts.empresa')

@section('page-title', 'Editar perfil')
@section('page-subtitle', 'Actualiza la información de tu empresa')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $tipoLabels = ['hotel'=>'🏨 Hotel/Hospedaje','restaurante'=>'🍽️ Restaurante','agencia_turismo'=>'🧭 Agencia de turismo','transporte'=>'🚌 Transporte','artesanias'=>'🎨 Artesanías','otro'=>'📦 Otro'];
    $serviciosOpciones = ['WiFi','Parqueadero','Restaurante propio','Piscina','Eventos','Guía turístico','Reservas online','Domicilios','Sala de conferencias','Pet friendly'];
    $serviciosActuales = old('servicios', $empresa->servicios ?? []);
@endphp

{{-- Alertas de sesión --}}
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.25rem;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1.25rem;">
        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
    </div>
@endif

{{-- ── DATOS GENERALES ── --}}
<div class="admin-section">
    <form method="POST" action="{{ route('empresa.perfil.update') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label>Nombre de la empresa *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $empresa->nombre) }}" required maxlength="200">
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}" maxlength="30">
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}" maxlength="400">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tipo de empresa</label>
                <select name="tipo_empresa">
                    <option value="">— Selecciona —</option>
                    @foreach($tipoLabels as $val => $label)
                        <option value="{{ $val }}" {{ old('tipo_empresa', $empresa->tipo_empresa) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>NIT <small style="color:var(--gray-400);font-weight:400;">(solo lectura)</small></label>
                <input type="text" value="{{ $empresa->nit ?? '—' }}" disabled
                       style="background:var(--gray-100);color:var(--gray-500);cursor:not-allowed;">
                <small style="color:var(--gray-400);font-size:.78rem;">Para cambiar el NIT contacta al administrador.</small>
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="4" maxlength="1000"
                      placeholder="Cuéntanos sobre tu empresa...">{{ old('descripcion', $empresa->descripcion) }}</textarea>
        </div>

        {{-- Servicios --}}
        <div class="form-group">
            <label>Servicios que ofrece</label>
            <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.4rem;">
                @foreach($serviciosOpciones as $srv)
                <label style="display:flex;align-items:center;gap:.35rem;background:var(--gray-50);border:1.5px solid var(--gray-200);border-radius:2rem;padding:.35rem .75rem;font-size:.85rem;cursor:pointer;">
                    <input type="checkbox" name="servicios[]" value="{{ $srv }}"
                           {{ in_array($srv, $serviciosActuales) ? 'checked' : '' }}
                           style="accent-color:var(--green-700);">
                    {{ $srv }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Logo --}}
        <div class="form-group">
            <label>Logo</label>
            @if($empresa->logo)
            <div style="margin-bottom:.75rem;">
                <img src="{{ Str::startsWith($empresa->logo, 'http') ? $empresa->logo : Storage::url($empresa->logo) }}"
                     alt="Logo actual" style="width:80px;height:80px;object-fit:cover;border-radius:var(--radius-md);border:1.5px solid var(--gray-200);">
                <div style="font-size:.78rem;color:var(--gray-400);margin-top:.3rem;">Logo actual</div>
            </div>
            @endif
            <input type="file" name="empresa_logo_file" accept="image/*" style="margin-bottom:.5rem;">
            <input type="url" name="empresa_logo_url" value="{{ old('empresa_logo_url') }}"
                   placeholder="O pega una URL: https://..."
                   style="width:100%;padding:.7rem 1rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-family:var(--font-body);font-size:.9rem;outline:none;">
        </div>

        <div class="form-group">
            <label>Sitio web</label>
            <input type="url" name="sitio_web" value="{{ old('sitio_web', $empresa->sitio_web) }}"
                   placeholder="https://miempresa.com" maxlength="300">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram', $empresa->instagram) }}"
                       placeholder="@miempresa" maxlength="200">
            </div>
            <div class="form-group">
                <label>Facebook</label>
                <input type="text" name="facebook" value="{{ old('facebook', $empresa->facebook) }}"
                       placeholder="facebook.com/miempresa" maxlength="200">
            </div>
        </div>

        <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-top:.5rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar cambios
            </button>
            <a href="{{ route('empresa.dashboard') }}" class="btn btn-outline">
                <i class="fa-solid fa-arrow-left fa-xs"></i> Volver
            </a>
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════════
     SECCIÓN: IMAGEN PRINCIPAL (HERO)
══════════════════════════════════════════════════════════ --}}
<div class="admin-section" style="margin-top:2rem;">

    <div class="admin-section-header" style="margin-bottom:1.25rem;">
        <h2 style="display:flex;align-items:center;gap:.5rem;">
            <i class="fa-solid fa-image" style="color:var(--primary);"></i>
            Imagen Principal del Sitio
        </h2>
        <p style="font-size:.82rem;color:var(--gray-400);margin:0;">
            Esta imagen aparece en el carrusel de la página de inicio cuando tu empresa está activa.
        </p>
    </div>

    {{-- Formulario subir nueva imagen --}}
    <form method="POST" action="{{ route('empresa.hero.store') }}" enctype="multipart/form-data"
          style="background:var(--gray-50);border:1.5px dashed var(--gray-200);border-radius:var(--radius-md);padding:1.5rem;margin-bottom:1.75rem;">
        @csrf

        <p style="font-size:.85rem;font-weight:700;color:var(--gray-700);margin-bottom:1rem;">
            <i class="fa-solid fa-plus-circle" style="color:var(--green-600);margin-right:.3rem;"></i>
            Agregar nueva imagen
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="form-group" style="margin:0;">
                <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">
                    Título <span style="font-weight:400;color:var(--gray-400);">(opcional)</span>
                </label>
                <input type="text" name="titulo" placeholder="Ej: Vista al río" maxlength="200"
                       style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;font-family:var(--font-body);outline:none;">
            </div>
            <div class="form-group" style="margin:0;">
                <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">
                    Tipo de imagen
                </label>
                <select name="tipo" id="hero-tipo-select" onchange="toggleHeroTipo(this.value)"
                        style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;font-family:var(--font-body);outline:none;">
                    <option value="upload">📁 Subir archivo (JPG, PNG, WEBP)</option>
                    <option value="url">🔗 URL externa</option>
                </select>
            </div>
        </div>

        <div id="hero-upload-field" class="form-group" style="margin-bottom:.75rem;">
            <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">
                Archivo de imagen
            </label>
            <input type="file" name="imagen" accept="image/jpg,image/jpeg,image/png,image/webp"
                   style="width:100%;padding:.6rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.88rem;background:#fff;font-family:var(--font-body);">
            <p style="font-size:.75rem;color:var(--gray-400);margin-top:.3rem;">Máximo 4MB — JPG, PNG o WEBP recomendado.</p>
        </div>

        <div id="hero-url-field" class="form-group" style="display:none;margin-bottom:.75rem;">
            <label style="font-size:.83rem;font-weight:600;display:block;margin-bottom:.4rem;">
                URL de la imagen
            </label>
            <input type="url" name="url" placeholder="https://ejemplo.com/imagen.jpg"
                   style="width:100%;padding:.65rem .9rem;border:1.5px solid var(--gray-200);border-radius:var(--radius-md);font-size:.9rem;font-family:var(--font-body);outline:none;">
        </div>

        @error('imagen') <p style="color:var(--danger);font-size:.8rem;margin-bottom:.5rem;">{{ $message }}</p> @enderror
        @error('url')    <p style="color:var(--danger);font-size:.8rem;margin-bottom:.5rem;">{{ $message }}</p> @enderror

        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-upload fa-xs"></i> Subir imagen
        </button>
    </form>

    {{-- Listado de imágenes actuales --}}
    @php
        $heroImagenes = \App\Models\HeroImage::where('empresa_id', $empresa->id)
            ->where('seccion', 'hero')
            ->orderBy('orden')
            ->get();
    @endphp

    @if($heroImagenes->isEmpty())
        <div style="text-align:center;padding:2.5rem 1rem;color:var(--gray-400);">
            <i class="fa-solid fa-image" style="font-size:2rem;display:block;margin-bottom:.6rem;opacity:.3;"></i>
            <p style="font-size:.88rem;margin:0;">No tienes imágenes principales aún. Sube una arriba.</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
            @foreach($heroImagenes as $img)
                @php $src = str_starts_with($img->url, 'http') ? $img->url : asset('storage/' . $img->url); @endphp
                <div style="border:1.5px solid var(--gray-200);border-radius:var(--radius-md);overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.05);">

                    {{-- Miniatura --}}
                    <div style="position:relative;">
                        <img src="{{ $src }}" alt="{{ $img->titulo ?? 'Imagen hero' }}"
                             style="width:100%;height:120px;object-fit:cover;display:block;"
                             onerror="this.parentElement.style.background='var(--gray-100)';this.style.display='none'">
                        <span style="
                            position:absolute;top:.5rem;right:.5rem;
                            padding:.2rem .55rem;border-radius:2rem;
                            font-size:.7rem;font-weight:700;
                            background:{{ $img->activa ? '#d1fae5' : '#fee2e2' }};
                            color:{{ $img->activa ? '#065f46' : '#991b1b' }};">
                            {{ $img->activa ? '● Activa' : '● Inactiva' }}
                        </span>
                    </div>

                    <div style="padding:.75rem;">
                        @if($img->titulo)
                            <p style="font-size:.82rem;font-weight:700;color:var(--gray-800);margin:0 0 .6rem;">
                                {{ $img->titulo }}
                            </p>
                        @endif

                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            {{-- Activar / Desactivar --}}
                            <form method="POST" action="{{ route('empresa.hero.toggle', $img) }}" style="margin:0;">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        style="padding:.3rem .65rem;font-size:.74rem;font-weight:600;border:none;border-radius:var(--radius-sm);cursor:pointer;
                                               background:{{ $img->activa ? '#fef3c7' : '#d1fae5' }};
                                               color:{{ $img->activa ? '#92400e' : '#065f46' }};">
                                    <i class="fa-solid fa-{{ $img->activa ? 'eye-slash' : 'eye' }} fa-xs"></i>
                                    {{ $img->activa ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>

                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('empresa.hero.destroy', $img) }}" style="margin:0;"
                                  onsubmit="return confirm('¿Seguro que quieres eliminar esta imagen?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="padding:.3rem .65rem;font-size:.74rem;font-weight:600;border:none;border-radius:var(--radius-sm);cursor:pointer;background:#fee2e2;color:#991b1b;">
                                    <i class="fa-solid fa-trash fa-xs"></i> Eliminar
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
function toggleHeroTipo(val) {
    document.getElementById('hero-upload-field').style.display = val === 'upload' ? 'block' : 'none';
    document.getElementById('hero-url-field').style.display    = val === 'url'    ? 'block' : 'none';
}
</script>
@endpush

@endsection