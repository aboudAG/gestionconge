
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div class="container">
            <h1>Liste des Rôles</h1>
            <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">Ajouter un nouveau rôle</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->ID }} </td>
                        <td>{{ $role->NOM }}</td>
                        <td>
                            {{-- Ici, ajoutez les liens pour modifier ou supprimer les rôles --}}
                            <a href="{{ route('roles.edit', $role->ID) }}" class="btn btn-primary">Éditer</a>
                            <form action="{{ route('roles.destroy', $role->ID) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">Supprimer le Rôle</button>
                            </form>
                    </tr>
                    @endforeach
                </tbody>
        </table>

        </div>
    </body>
    </html>


