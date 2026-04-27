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