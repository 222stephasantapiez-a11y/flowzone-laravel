<h1>Listado de Usuarios</h1>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>rol</th>
        <th>Estado</th>
        <th>Telefono</th>
    </tr>

    @foreach($usuarios as $u)
    <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->rol}}</td>
        <td>{{ $u->estado}}</td>
        <td>{{ $u->telefono }}</td>
    </tr>
    @endforeach
</table>