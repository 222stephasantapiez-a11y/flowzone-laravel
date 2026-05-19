<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE EMPRESAS</h1><p>Empresas registradas en la plataforma</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($empresas) }} empresas</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $aprobadas  = collect($empresas)->where('rol','empresa')->count();
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($empresas) }}</div><div class="stat-lbl">Total</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de empresas</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Estado</th><th>Registro</th>
        </tr></thead>
        <tbody>
        @foreach($empresas as $i => $e)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $e->name ?? $e->nombre ?? '—' }}</strong></td>
            <td>{{ $e->email ?? '—' }}</td>
            <td>{{ $e->telefono ?? '—' }}</td>
            <td>
                @php $estado = $e->estado ?? ($e->aprobado ? 'activo' : 'pendiente'); @endphp
                @if($estado==='activo') <span class="badge bg">Activo</span>
                @elseif($estado==='pendiente') <span class="badge by">Pendiente</span>
                @else <span class="badge br">Bloqueado</span>
                @endif
            </td>
            <td>{{ isset($e->created_at) ? \Carbon\Carbon::parse($e->created_at)->format('d/m/Y') : '—' }}</td>
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
