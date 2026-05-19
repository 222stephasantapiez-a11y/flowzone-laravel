<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE RESERVAS</h1><p>Historial completo de reservas del sistema</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($reservas) }} reservas</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $confirmadas = collect($reservas)->where('estado','confirmada')->count();
    $pendientes  = collect($reservas)->where('estado','pendiente')->count();
    $canceladas  = collect($reservas)->where('estado','cancelada')->count();
    $ingresos    = collect($reservas)->where('estado','confirmada')->sum('precio_total');
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($reservas) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $confirmadas }}</div><div class="stat-lbl">Confirmadas</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#f57f17;">{{ $pendientes }}</div><div class="stat-lbl">Pendientes</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#b71c1c;">{{ $canceladas }}</div><div class="stat-lbl">Canceladas</div></td>
        <td class="stat-cell"><div class="stat-num" style="font-size:13px;">${{ number_format($ingresos,0,',','.') }}</div><div class="stat-lbl">Ingresos COP</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de reservas</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Usuario</th><th>Hotel</th><th>Entrada</th><th>Salida</th><th>Personas</th><th>Total</th><th>Pago</th><th>Estado</th>
        </tr></thead>
        <tbody>
        @foreach($reservas as $i => $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->usuario?->name ?? '—' }}</td>
            <td>{{ $r->hotel?->nombre ?? '—' }}</td>
            <td>{{ $r->fecha_entrada instanceof \Carbon\Carbon ? $r->fecha_entrada->format('d/m/Y') : $r->fecha_entrada }}</td>
            <td>{{ $r->fecha_salida instanceof \Carbon\Carbon ? $r->fecha_salida->format('d/m/Y') : $r->fecha_salida }}</td>
            <td style="text-align:center;">{{ $r->num_personas }}</td>
            <td>${{ number_format($r->precio_total,0,',','.') }}</td>
            <td>{{ ucfirst(str_replace('_',' ',$r->metodo_pago ?? '—')) }}</td>
            <td>
                @if($r->estado==='confirmada') <span class="badge bg">Confirmada</span>
                @elseif($r->estado==='pendiente') <span class="badge by">Pendiente</span>
                @else <span class="badge br">Cancelada</span>
                @endif
            </td>
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
