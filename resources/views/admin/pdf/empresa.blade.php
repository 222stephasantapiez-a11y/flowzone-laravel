<h2>Listado de Empresas</h2>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>
        @foreach($empresas as $empresa)
            <tr>
                <td>{{ $empresa->id }}</td>
                <td>{{ $empresa->name }}</td>
                <td>{{ $empresa->email }}</td>
                <td>{{ $empresa->telefono }}</td>
            </tr>
        @endforeach
    </tbody>
</table>