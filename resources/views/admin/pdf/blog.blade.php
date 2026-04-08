<h2>Listado de Blogs</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Slug</th>
            <th>Publicado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($blogs as $blog)
            <tr>
                <td>{{ $blog->id }}</td>
                <td>{{ $blog->titulo }}</td>
                <td>{{ $blog->slug }}</td>
                <td>{{ $blog->publicado ? 'Sí' : 'No' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>