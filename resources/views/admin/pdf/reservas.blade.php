<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reservas PDF</title>
</head>
<body>
    <h2>Listado de Reservas</h2>

    <table border="1" width="100%" cellspacing="0" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Hotel</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Estado</th>
        </tr>

        @foreach($reservas as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->usuario->name }}</td>
                <td>{{ $r->hotel->nombre }}</td>
                <td>{{ $r->fecha_entrada }}</td>
                <td>{{ $r->fecha_salida }}</td>
                <td>{{ $r->estado }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>