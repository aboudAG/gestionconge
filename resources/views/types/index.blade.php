<!-- resources/views/types/index.blade.php -->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Liste des Types de Congés</div>
                    <a href="{{route ('types.create')}}">creer un type</a>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $type)
                                    <tr>
                                        <td>{{ $type->ID }}</td>
                                        <td>{{ $type->NOM }}</td>
                                        <td>
                                            <!-- Ajoute ici les liens pour modifier ou supprimer chaque type -->
                                            <a href="{{ route('types.edit', $type->ID) }}" class="btn btn-primary">Modifier</a>
                                            <form action="{{ route('types.destroy', $type->ID) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
</html>
