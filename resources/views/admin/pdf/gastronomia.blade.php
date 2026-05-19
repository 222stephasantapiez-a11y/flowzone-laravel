<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE GASTRONOMÍA</h1><p>Platos y servicios gastronómicos registrados</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($gastronomias) }} registros</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $disponibles = collect($gastronomias)->where('disponible_hoy', true)->count();
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($gastronomias) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $disponibles }}</div><div class="stat-lbl">Disponibles hoy</div></td>
        <td class="stat-cell"><div class="stat-num">${{ number_format(collect($gastronomias)->avg('precio_promedio'),0,',','.') }}</div><div class="stat-lbl">Precio promedio</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de gastronomía</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Nombre</th><th>Tipo</th><th>Precio</th><th>Ubicación</th><th>Disponible</th>
        </tr></thead>
        <tbody>
        @foreach($gastronomias as $i => $g)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $g->nombre }}</strong></td>
            <td>{{ $g->tipo ?? '—' }}</td>
            <td>{{ $g->precio_promedio ? '$'.number_format($g->precio_promedio,0,',','.') : '—' }}</td>
            <td>{{ $g->ubicacion ?? '—' }}</td>
            <td>
                @if($g->disponible_hoy) <span class="badge bg">Sí</span>
                @else <span class="badge br">No</span>
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
