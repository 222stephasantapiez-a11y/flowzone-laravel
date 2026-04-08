<h1>Listado de Hoteles</h1>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Precio</th>
    </tr>

    @foreach($hoteles as $hotel)
    <tr>
        <td>{{ $hotel->id }}</td>
        <td>{{ $hotel->nombre }}</td>
        <td>{{ $hotel->direccion }}</td>
        <td>{{ $hotel->precio }}</td>
    </tr>
    @endforeach
</table>