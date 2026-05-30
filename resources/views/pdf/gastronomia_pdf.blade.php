<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }

        .header {
            background: #2D6A4F;
            color: #fff;
            padding: 18px 24px 14px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 20px; font-weight: 700; letter-spacing: .5px; margin-bottom: 3px; }
        .header p  { font-size: 10px; opacity: .8; }

        .meta {
            display: flex;
            justify-content: space-between;
            padding: 0 24px;
            margin-bottom: 16px;
            font-size: 10px;
            color: #555;
        }

        table {
            width: calc(100% - 48px);
            margin: 0 24px;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        thead tr {
            background: #40916C;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 700;
            letter-spacing: .3px;
        }
        tbody tr:nth-child(even) { background: #f0faf4; }
        tbody tr:nth-child(odd)  { background: #fff; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #d1fae5; vertical-align: top; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            background: #d1fae5;
            color: #065f46;
        }

        .footer {
            margin-top: 18px;
            padding: 10px 24px 0;
            border-top: 1px solid #d1fae5;
            font-size: 9px;
            color: #888;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>&#127374; Platos Registrados</h1>
    <p>{{ $empresa->nombre }} &mdash; FlowZone</p>
</div>

<div class="meta">
    <span>Total de registros: <strong>{{ $items->count() }}</strong></span>
    <span>Generado el {{ now()->format('d/m/Y H:i') }}</span>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Precio (COP)</th>
            <th>Descripción</th>
            <th>Dirección</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $item->nombre }}</strong></td>
            <td>
                @if($item->tipo)
                    <span class="badge">{{ $item->tipo }}</span>
                @else
                    —
                @endif
            </td>
            <td>{{ $item->precio_promedio ? '$'.number_format($item->precio_promedio,0) : '—' }}</td>
            <td>{{ Str::limit($item->descripcion, 60) ?? '—' }}</td>
            <td>{{ $item->direccion ?? '—' }}</td>
            <td>{{ $item->telefono ?? '—' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:20px;color:#888;">Sin registros</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    FlowZone &mdash; Panel Empresa &mdash; {{ now()->format('Y') }}
</div>

</body>
</html>