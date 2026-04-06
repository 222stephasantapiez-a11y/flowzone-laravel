<h1>Listado de Eventos</h1>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Fecha</th>
        <th>Ubicación</th>
    </tr>

    @foreach($eventos as $evento)
    <tr>
        <td>{{ $evento->id }}</td>
        <td>{{ $evento->nombre }}</td>
        <td>{{ $evento->descripcion }}</td>
        <td>{{ $evento->fecha }}</td>
        <td>{{ $evento->ubicacion }}</td>
    </tr>
    @endforeach
</table>