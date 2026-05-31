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

        {{-- Facebook (opcional) --}}
        <div class="form-group">
            <label>Facebook <small style="color:var(--gray-400);font-weight:400;">(opcional)</small></label>
            <input type="text" name="facebook" value="{{ old('facebook', $empresa->facebook) }}"
                   placeholder="facebook.com/miempresa" maxlength="200">
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

@include('empresa._galeria_section')

@endsection