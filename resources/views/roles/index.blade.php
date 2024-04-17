
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

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
                            <a href="{{url('roles/'.$role->ID.'/delete')}}">supprimer</a>
                    </tr>
                    @endforeach
                </tbody>
        </table>

        </div>
    </body>
    </html>


