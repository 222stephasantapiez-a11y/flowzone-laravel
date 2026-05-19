<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE EVENTOS</h1><p>Eventos culturales y turísticos registrados</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($eventos) }} eventos</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $proximos = collect($eventos)->filter(fn($e) => \Carbon\Carbon::parse($e->fecha)->isFuture())->count();
    $pasados  = count($eventos) - $proximos;
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($eventos) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $proximos }}</div><div class="stat-lbl">Próximos</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#555;">{{ $pasados }}</div><div class="stat-lbl">Pasados</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de eventos</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Nombre</th><th>Fecha</th><th>Ubicación</th><th>Categoría</th><th>Precio</th>
        </tr></thead>
        <tbody>
        @foreach($eventos as $i => $e)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $e->nombre }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($e->fecha)->format('d/m/Y') }}</td>
            <td>{{ $e->ubicacion ?? '—' }}</td>
            <td>{{ $e->categoria ?? '—' }}</td>
            <td>{{ $e->precio > 0 ? '$'.number_format($e->precio,0,',','.') : 'Gratuito' }}</td>
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
