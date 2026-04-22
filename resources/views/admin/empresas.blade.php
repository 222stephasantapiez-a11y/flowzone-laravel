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
                    <td>
                        <form method="POST" action="{{ route('admin.notificaciones.leer', $notif) }}">
                            @csrf @method('PATCH')
                            <button class="btn-small btn-success">
                                Leída
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ===================== MODAL ===================== --}}
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
        {{-- Header modal --}}
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
                border: none;
                color: #fff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background .2s;
            " onmouseover="this.style.background='rgba(255,255,255,.3)'"
               onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Body modal --}}
        <div style="padding: 1.75rem;">

            @isset($empresa)
                <form method="POST" action="{{ route('admin.empresas.update', $empresa) }}" class="admin-form">
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

                <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Actualizar Empresa
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

{{-- SECCIÓN DEL GENERADOR DE PLANES --}}
<div class="admin-section" style="margin-bottom: 2rem; background: #f8fafc; border: 2px dashed #cbd5e1;">
    <div class="admin-section-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--primary);"></i> Generador de Planes Turísticos</h2>
        <a href="{{ route('admin.empresas.index', ['generar' => 'true']) }}" class="btn btn-primary">
            <i class="fa-solid fa-dice"></i> Generar Nuevo Plan
        </a>
    </div>

    @if(isset($plan))
        <div style="margin-top: 1.5rem; background: white; border-radius: 15px; shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); overflow: hidden; border: 1px solid #e2e8f0;">
            <div style="background: var(--primary); color: white; padding: 1rem; text-align: center; font-weight: bold;">
                ¡OFERTA ESPECIAL: PLAN TURÍSTICO CON 20% DE DESCUENTO!
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; padding: 1.5rem;">
                <div style="padding: 1rem; border-left: 4px solid #6366f1; background: #f5f3ff;">
                    <small style="color: #6366f1; font-weight: bold; text-transform: uppercase;">Evento</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['evento']->nombre }}</p>
                </div>
                <div style="padding: 1rem; border-left: 4px solid #f97316; background: #fff7ed;">
                    <small style="color: #f97316; font-weight: bold; text-transform: uppercase;">Gastronomía</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['gastronomia']->nombre }}</p>
                </div>
                <div style="padding: 1rem; border-left: 4px solid #22c55e; background: #f0fdf4;">
                    <small style="color: #22c55e; font-weight: bold; text-transform: uppercase;">Hotel</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['hotel']->nombre }}</p>
                </div>
                <div style="padding: 1rem; border-left: 4px solid #3b82f6; background: #eff6ff;">
                    <small style="color: #3b82f6; font-weight: bold; text-transform: uppercase;">Lugar</small>
                    <p style="margin: 0; font-weight: bold;">{{ $plan['lugar']->nombre }}</p>
                </div>
            </div>

            <div style="background: #1e293b; color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="text-decoration: line-through; color: #94a3b8; margin-right: 1rem;">Antes: ${{ number_format($plan['subtotal'], 0, ',', '.') }}</span>
                    <span style="background: #ef4444; padding: 2px 8px; border-radius: 5px; font-size: 0.8rem;">-20% DCTO</span>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 0.9rem; color: #94a3b8;">Precio Total Plan:</p>
                    <p style="margin: 0; font-size: 1.8rem; font-weight: bold; color: #fbbf24;">${{ number_format($plan['precioFinal'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @else
        <p style="text-align: center; color: #64748b; padding: 1rem;">Haz clic en el botón para generar una combinación aleatoria con descuento.</p>
    @endif
</div>
{{-- Tabla empresas --}}
{{-- TABLA --}}
<div class="admin-section">

    <div class="admin-section-header">
        <h2>Empresas Registradas</h2>
        <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
              <span class="badge badge-info">{{ $empresas->total() }} total</span>
            <a href="{{ route('admin.empresas.export.excel') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.empresas.export.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            @include('partials.import_modal', [
                'importRoute' => 'admin.empresas.import.excel',
                'sampleFile'  => 'ejemplo_.xlsx',
                'modalId'     => 'importEventos', 
                'columns'     => [
                    'nombre'      => 'Nombre del evento (requerido)',
                    'descripcion' => 'Descripción del evento (requerido)',
                    'fecha'       => 'Fecha en formato YYYY-MM-DD (requerido)',
                    'ubicacion'   => 'Lugar donde se realiza',
                    'categoria'   => 'Categoría (Cultural, Deportivo...)',
                    'precio'      => 'Precio en COP (0 = gratuito)',
                    'organizador' => 'Nombre del organizador',
                    'contacto'    => 'Teléfono o email de contacto',
                ],
            ])
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Empresa</th>
                    <th>Responsable</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
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
                        @if($e->aprobado)
                            <span class="badge badge-success">Aprobada</span>
                        @else
                            <span class="badge badge-warning">Pendiente</span>
                        @endif
                    </td>

                    <td>
                        @unless($e->aprobado)
                            <form method="POST" action="{{ route('admin.empresas.aprobar', $e) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn-small btn-success">Aprobar</button>
                            </form>

                            <form method="POST" action="{{ route('admin.empresas.rechazar', $e) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn-small btn-delete">Rechazar</button>
                            </form>
                        @endunless

                        <a href="{{ route('admin.empresas.edit', $e) }}" class="btn-small btn-edit">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('admin.empresas.destroy', $e) }}" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn-small btn-delete">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        No hay empresas registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        
        <div style="display:flex; gap:.5rem; margin-bottom:1rem;">
    <a href="{{ route('admin.empresas.export.excel') }}" class="btn btn-success btn-sm">
        Excel
    </a>

    <a href="{{ route('admin.empresas.export.pdf') }}" class="btn btn-danger btn-sm">
        PDF
    </a>

    <form action="{{ route('admin.empresas.import.excel') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        <input type="file" name="archivo" required>
        <button type="submit" class="btn btn-primary btn-sm">
            Importar
        </button>
    </form>
</div>

    </div>

    {{-- Paginación --}}
    @if($empresas->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $empresas->firstItem() }}</strong>–<strong>{{ $empresas->lastItem() }}</strong>
            de <strong>{{ $empresas->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($empresas->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $empresas->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($empresas->getUrlRange(max(1,$empresas->currentPage()-2), min($empresas->lastPage(),$empresas->currentPage()+2)) as $page => $url)
                @if($page == $empresas->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($empresas->hasMorePages())
                <a href="{{ $empresas->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
            @else
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-right fa-xs"></i></span>
            @endif
        </div>

        <form method="GET" class="per-page-form">
            @foreach(request()->except(['page','per_page']) as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endforeach
            <label class="per-page-label">Filas:</label>
            <select name="per_page" class="per-page-select" onchange="this.form.submit()">
                @foreach([5,10,25,50,100] as $n)
                    <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @endif

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
    if (e.key === 'Escape') cerrarModal();
});

@isset($empresa)
    document.getElementById('modal-empresa').style.display = 'block';
    document.body.style.overflow = 'hidden';
@endisset
</script>
@endpush

@endsection