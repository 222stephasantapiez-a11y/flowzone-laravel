<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE USUARIOS</h1><p>Usuarios registrados en la plataforma</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($usuarios) }} usuarios</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $activos   = collect($usuarios)->where('estado','activo')->count();
    $pendientes = collect($usuarios)->where('estado','pendiente')->count();
    $bloqueados = collect($usuarios)->where('estado','bloqueado')->count();
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($usuarios) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $activos }}</div><div class="stat-lbl">Activos</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#f57f17;">{{ $pendientes }}</div><div class="stat-lbl">Pendientes</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#b71c1c;">{{ $bloqueados }}</div><div class="stat-lbl">Bloqueados</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de usuarios</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Teléfono</th><th>Estado</th><th>Registro</th>
        </tr></thead>
        <tbody>
        @foreach($usuarios as $i => $u)
        <tr>
            <td>{{ $u->id }}</td>
            <td><strong>{{ $u->name }}</strong></td>
            <td>{{ $u->email }}</td>
            <td>{{ ucfirst($u->rol ?? '—') }}</td>
            <td>{{ $u->telefono ?? '—' }}</td>
            <td>
                @if($u->estado==='activo') <span class="badge bg">Activo</span>
                @elseif($u->estado==='pendiente') <span class="badge by">Pendiente</span>
                @else <span class="badge br">Bloqueado</span>
                @endif
            </td>
            <td>{{ $u->created_at?->format('d/m/Y') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="footer">
    <table class="footer-table"><tr>
        <td>© {{ date('Y') }} FlowZone — Todos los derechos reservados</td>
        <td style="text-align:right;">Página <span class="pagenum"></span></td>
    </tr></table>
</div>
</body>
</html>
