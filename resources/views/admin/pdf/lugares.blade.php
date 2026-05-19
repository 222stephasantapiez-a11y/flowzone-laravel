<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE LUGARES</h1><p>Destinos turísticos registrados</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($lugares) }} lugares</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $gratuitos = collect($lugares)->filter(fn($l) => ($l->precio_entrada ?? 0) == 0)->count();
    $pagos     = count($lugares) - $gratuitos;
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($lugares) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $gratuitos }}</div><div class="stat-lbl">Gratuitos</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#f57f17;">{{ $pagos }}</div><div class="stat-lbl">Con costo</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de lugares</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Nombre</th><th>Categoría</th><th>Ubicación</th><th>Precio entrada</th><th>Horario</th>
        </tr></thead>
        <tbody>
        @foreach($lugares as $i => $l)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $l->nombre }}</strong></td>
            <td>{{ $l->categoria ?? '—' }}</td>
            <td>{{ $l->ubicacion ?? '—' }}</td>
            <td>{{ ($l->precio_entrada ?? 0) > 0 ? '$'.number_format($l->precio_entrada,0,',','.') : 'Gratuito' }}</td>
            <td>{{ $l->horario ?? '—' }}</td>
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
