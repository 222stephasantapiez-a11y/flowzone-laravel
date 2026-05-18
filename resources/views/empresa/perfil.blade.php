@extends('layouts.empresa')

@section('page-title', 'Mi Perfil')
@section('page-subtitle', 'Información de tu empresa')

@section('content')

@if(!$empresa)
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        No se encontraron datos asociados a tu cuenta. Contacta al administrador.
    </div>
@else

{{-- ── Tarjeta de perfil ── --}}
<div class="admin-section">

    {{-- Cabecera con nombre y estado --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.75rem;">
        <div style="display:flex;align-items:center;gap:1rem;">
            <div style="width:56px;height:56px;border-radius:var(--radius-lg);background:rgba(45,106,79,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-building" style="font-size:1.4rem;color:var(--green-700);"></i>
            </div>
            <div>
                <h2 style="font-size:1.3rem;font-weight:800;color:var(--gray-900);margin:0 0 .2rem;">
                    {{ $empresa->nombre }}
                </h2>
                @if($empresa->aprobado)
                    <span class="badge badge-success"><i class="fa-solid fa-circle-check fa-xs"></i> Aprobada</span>
                @else
                    <span class="badge badge-warning"><i class="fa-solid fa-clock fa-xs"></i> Pendiente de aprobación</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Grid de datos --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.75rem;">

        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1rem 1.1rem;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:.3rem;font-weight:600;">
                <i class="fa-solid fa-building fa-xs" style="margin-right:.3rem;"></i>Empresa
            </div>
            <div style="font-weight:600;color:var(--gray-900);font-size:.95rem;">{{ $empresa->nombre }}</div>
        </div>

        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1rem 1.1rem;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:.3rem;font-weight:600;">
                <i class="fa-solid fa-envelope fa-xs" style="margin-right:.3rem;"></i>Correo electrónico
            </div>
            <div style="font-weight:600;color:var(--gray-900);font-size:.95rem;">{{ auth()->user()->email }}</div>
        </div>

        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1rem 1.1rem;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:.3rem;font-weight:600;">
                <i class="fa-solid fa-phone fa-xs" style="margin-right:.3rem;"></i>Teléfono
            </div>
            <div style="font-weight:600;color:var(--gray-900);font-size:.95rem;">{{ $empresa->telefono ?? '—' }}</div>
        </div>

        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1rem 1.1rem;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:.3rem;font-weight:600;">
                <i class="fa-solid fa-location-dot fa-xs" style="margin-right:.3rem;"></i>Dirección
            </div>
            <div style="font-weight:600;color:var(--gray-900);font-size:.95rem;">{{ $empresa->direccion ?? '—' }}</div>
        </div>

        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1rem 1.1rem;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:.3rem;font-weight:600;">
                <i class="fa-solid fa-user fa-xs" style="margin-right:.3rem;"></i>Responsable
            </div>
            <div style="font-weight:600;color:var(--gray-900);font-size:.95rem;">{{ auth()->user()->name }}</div>
        </div>

        <div style="background:var(--gray-50);border-radius:var(--radius-md);padding:1rem 1.1rem;">
            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:var(--gray-400);margin-bottom:.3rem;font-weight:600;">
                <i class="fa-solid fa-shield-halved fa-xs" style="margin-right:.3rem;"></i>Estado
            </div>
            <div style="font-weight:600;font-size:.95rem;">
                @if($empresa->aprobado)
                    <span style="color:var(--green-700);">Aprobada</span>
                @else
                    <span style="color:var(--warning);">Pendiente</span>
                @endif
            </div>
        </div>

    </div>

    {{-- Botón Editar --}}
    <button type="button" onclick="document.getElementById('modalEditarPerfil').classList.add('open')"
            class="btn btn-primary" style="gap:.5rem;">
        <i class="fa-solid fa-pen-to-square fa-xs"></i> Editar información
    </button>

</div>

{{-- ══════════════════════════════════════
     MODAL Editar perfil
══════════════════════════════════════ --}}
<div id="modalEditarPerfil" style="
    display:none;
    position:fixed;inset:0;
    background:rgba(0,0,0,.45);
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:1rem;
">
    <div style="
        background:var(--white);
        border-radius:var(--radius-lg);
        width:100%;max-width:540px;
        box-shadow:0 20px 60px rgba(0,0,0,.2);
        overflow:hidden;
        animation: modalIn .2s ease;
    ">
        {{-- Header modal --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--gray-100);">
            <div style="display:flex;align-items:center;gap:.6rem;">
                <i class="fa-solid fa-pen-to-square" style="color:var(--green-600);"></i>
                <h3 style="margin:0;font-size:1rem;font-weight:700;color:var(--gray-900);">Editar información</h3>
            </div>
            <button type="button"
                    onclick="document.getElementById('modalEditarPerfil').classList.remove('open')"
                    style="background:none;border:none;cursor:pointer;font-size:1.1rem;color:var(--gray-400);line-height:1;padding:.2rem;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('empresa.perfil.update') }}" style="padding:1.5rem;" class="admin-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre de la empresa *</label>
                    <input type="text" name="nombre" required maxlength="200"
                           value="{{ old('nombre', $empresa->nombre) }}"
                           placeholder="Ej: Empresa Demo S.A.S">
                    @error('nombre')
                        <span style="color:var(--danger);font-size:.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" maxlength="30"
                           value="{{ old('telefono', $empresa->telefono) }}"
                           placeholder="Ej: +57 300 000 0000">
                </div>
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" maxlength="400"
                       value="{{ old('direccion', $empresa->direccion) }}"
                       placeholder="Ej: Calle 123 # 45-67, Bogotá">
            </div>

            <div class="form-group" style="margin-bottom:1.75rem;">
                <label>Correo electrónico</label>
                <input type="email" value="{{ auth()->user()->email }}" disabled
                       style="background:var(--gray-50);color:var(--gray-400);cursor:not-allowed;">
                <small style="color:var(--gray-400);font-size:.78rem;">El email no puede modificarse aquí.</small>
            </div>

            {{-- Footer modal --}}
            <div style="display:flex;gap:.75rem;justify-content:flex-end;">
                <button type="button"
                        onclick="document.getElementById('modalEditarPerfil').classList.remove('open')"
                        class="btn" style="background:var(--gray-100);color:var(--gray-700);border-color:var(--gray-200);">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk fa-xs"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

@endif

@push('scripts')
<script>
    // Abrir modal con clase
    const modal = document.getElementById('modalEditarPerfil');
    if (modal) {
        // Mostrar/ocultar via clase
        const observer = new MutationObserver(() => {
            modal.style.display = modal.classList.contains('open') ? 'flex' : 'none';
        });
        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });

        // Cerrar al click fuera
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.classList.remove('open');
        });

        // Si hay errores de validación, abrir el modal automáticamente
        @if($errors->any())
            modal.classList.add('open');
        @endif
    }
</script>
<style>
    @keyframes modalIn {
        from { opacity:0; transform:translateY(-12px) scale(.97); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
</style>
@endpush