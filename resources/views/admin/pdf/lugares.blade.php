<h1>Listado de Lugares</h1>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Ubicación</th>
    </tr>

    @foreach($lugares as $lugar)
    <tr>
        <td>{{ $lugar->id }}</td>
        <td>{{ $lugar->nombre }}</td>
        <td>{{ $lugar->descripcion }}</td>
        <td>{{ $lugar->ubicacion }}</td>
    </tr>
    @endforeach
</table>