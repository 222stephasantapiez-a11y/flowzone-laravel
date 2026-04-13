@extends('layouts.admin')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administra usuarios registrados')

@section('content')

<div class="admin-section">

    <div class="admin-section-header">
        <h2>Usuarios registrados</h2>

        <div style="display:flex; gap:.5rem;">
            <a href="{{ route('admin.usuarios.export.excel') }}" class="btn btn-success btn-sm">
                Excel
            </a>

            <a href="{{ route('admin.usuarios.export.pdf') }}" class="btn btn-danger btn-sm">
                PDF
            </a>

            <span class="badge badge-info">
                {{ $usuarios->total() }} total
            </span>
        </div>
    </div>
              @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif
    <form action="{{ route('admin.usuarios.import.excel') }}"
          method="POST"
          enctype="multipart/form-data"
          style="margin-bottom:1rem;">
        @csrf

        <div style="display:flex; gap:.5rem;">
            <input type="file" name="archivo" required>

            <button type="submit" class="btn btn-primary btn-sm">
                Importar Excel
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>rol</th>
                    <th>estado</th>
                    <th>telefono</th>
                    <th>Fecha registro</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->rol }}</td>
                        <td>{{ $u->estado}}</td>
                        <td>{{ $u->telefono}}</td>
                        <td>{{ $u->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay usuarios</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($usuarios->hasPages())
    <div class="pagination-bar">
        <div class="pagination-info">
            Mostrando <strong>{{ $usuarios->firstItem() }}</strong>–<strong>{{ $usuarios->lastItem() }}</strong>
            de <strong>{{ $usuarios->total() }}</strong> registros
        </div>

        <div class="pagination-links">
            @if($usuarios->onFirstPage())
                <span class="page-btn page-btn--disabled"><i class="fa-solid fa-chevron-left fa-xs"></i></span>
            @else
                <a href="{{ $usuarios->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left fa-xs"></i></a>
            @endif

            @foreach($usuarios->getUrlRange(max(1,$usuarios->currentPage()-2), min($usuarios->lastPage(),$usuarios->currentPage()+2)) as $page => $url)
                @if($page == $usuarios->currentPage())
                    <span class="page-btn page-btn--active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($usuarios->hasMorePages())
                <a href="{{ $usuarios->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right fa-xs"></i></a>
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

@endsection