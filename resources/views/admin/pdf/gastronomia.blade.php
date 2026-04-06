<h1>Listado de Restaurantes</h1>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Ubicación</th>
        <th>Precio</th>
    </tr>

    @foreach($gastronomia as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td>{{ $item->nombre }}</td>
        <td>{{ $item->descripcion }}</td>
        <td>{{ $item->ubicacion }}</td>
        <td>{{ $item->precio }}</td>
    </tr>
    @endforeach
</table>
