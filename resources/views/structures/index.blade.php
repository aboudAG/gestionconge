<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="container">
    <h1>Liste des Structures</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Parent ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($structures as $structure)
            <tr>
                <td>{{ $structure->ID }}</td>
                <td>{{ $structure->CODE }}</td>
                <td>{{ $structure->NOM }}</td>
                <td>{{ $structure->TYPE }}</td>
                <td>{{ $structure->PARENT_ID }}</td>
                <td>
                    <a href="{{ route('structures.edit', $structure->ID) }}">Modifier</a>
                    {{-- Bouton ou lien pour supprimer une structure --}}
                    <form action="{{ route('structures.destroy', $structure->ID) }}" method="POST">
                        @csrf
                      @method('DELETE')
                     <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette structure ?');">Supprimer</button>
                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



</body>
</html>
