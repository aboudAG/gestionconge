<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
         .alert {
        border-radius: 0.4rem;
        padding: 10px 20px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    /* Animation pour attirer l'attention sur les messages */
    .alert {
        animation: fadeIn 0.5s;
    }

    /* Keyframes pour l'animation fadeIn */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    </style>
</head>
<body>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
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
