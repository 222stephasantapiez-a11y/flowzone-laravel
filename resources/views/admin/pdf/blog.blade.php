<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8">@include('admin.pdf._styles')</head>
<body>
<div class="header-bar">
    <table class="header-table"><tr>
                <td class="brand-cell"><div class="brand-name">🌿 FlowZone</div><div class="brand-sub">Plataforma de Turismo</div></td>

        <td class="title-cell"><h1>REPORTE DE BLOG</h1><p>Publicaciones del blog de la plataforma</p></td>
        <td class="meta-cell">Generado: {{ now()->format('d/m/Y H:i') }}<br>Total: {{ count($blogs) }} publicaciones</td>
    </tr></table>
</div>
<div class="accent-bar"></div>

@php
    $publicados = collect($blogs)->where('publicado', true)->count();
    $borradores = count($blogs) - $publicados;
@endphp
<div class="stats-wrap">
    <table class="stats-table"><tr>
        <td class="stat-cell"><div class="stat-num">{{ count($blogs) }}</div><div class="stat-lbl">Total</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#1b5e20;">{{ $publicados }}</div><div class="stat-lbl">Publicados</div></td>
        <td class="stat-cell"><div class="stat-num" style="color:#f57f17;">{{ $borradores }}</div><div class="stat-lbl">Borradores</div></td>
    </tr></table>
</div>

<div class="section-title">Listado de publicaciones</div>
<div class="content">
    <table class="dt">
        <thead><tr>
            <th>#</th><th>Título</th><th>Tipo</th><th>Slug</th><th>Estado</th><th>Publicación</th>
        </tr></thead>
        <tbody>
        @foreach($blogs as $i => $b)
        <tr>
            <td>{{ $i+1 }}</td>
            <td><strong>{{ \Illuminate\Support\Str::limit($b->titulo, 50) }}</strong></td>
            <td>{{ $b->tipo ?? '—' }}</td>
            <td style="font-size:8px;color:#666;">{{ $b->slug }}</td>
            <td>
                @if($b->publicado) <span class="badge bg">Publicado</span>
                @else <span class="badge by">Borrador</span>
                @endif
            </td>
            <td>{{ $b->fecha_publicacion ? \Carbon\Carbon::parse($b->fecha_publicacion)->format('d/m/Y') : '—' }}</td>
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
