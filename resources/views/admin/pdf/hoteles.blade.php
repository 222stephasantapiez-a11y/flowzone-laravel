<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
        <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>
        <td class="title-cell"><h1>REPORTE DE HOTELES</h1><p>Listado completo de hoteles registrados</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($hoteles) }} hoteles</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $disponibles = collect($hoteles)->where('disponibilidad', true)->count();
    $noDisp = count($hoteles) - $disponibles;
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($hoteles) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $disponibles }}</div><div class="stat-lbl">Disponibles</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#b71c1c;">{{ $noDisp }}</div><div class="stat-lbl">No disponibles</div></td>
        <td class="stat-cell"><div class="stat-num">${{ number_format(collect($hoteles)->avg('precio'),0,',','.') }}</div><div class="stat-lbl">Precio promedio</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de hoteles</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Nombre</th><th>Ubicación</th><th>Precio/noche</th><th>Capacidad</th><th>Servicios</th><th>Estado</th>
        </tr></thead>
        <tbody>
        @foreach($hoteles as $i => $h)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ $h->nombre }}</strong></td>
            <td>{{ $h->ubicacion ?? '—' }}</td>
            <td>${{ number_format($h->precio,0,',','.') }}</td>
            <td>{{ $h->capacidad ?? '—' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($h->servicios ?? '—', 40) }}</td>
            <td>
                @if($h->disponibilidad)
                    <span class="badge bg">Disponible</span>
                @else
                    <span class="badge br">No disponible</span>
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
