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
    {{-- resources/views/employes/index.blade.php --}}

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
    <h1>Liste des Employés</h1>
    <a href="{{ route('employes.create') }}" class="btn btn-primary mb-3">Ajouter un nouvel employé</a>
    <table class="table">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste</th>
                <th>Date d'embauche</th>
                <th>Structure</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employes as $employe)
            <tr>
                <td>{{ $employe->MATRICULE }}</td>
                <td>{{ $employe->NOM }}</td>
                <td>{{ $employe->PRENOM }}</td>
                <td>{{ $employe->POSTE }}</td>
                <td>{{ $employe->DATE_EMBAUCHE}}</td>
                <td>{{ $employe->structure->NOM }}</td>
                <td>{{ $employe->role->NOM }}</td>
                <td>
                    <a href="{{ route('employes.edit', $employe->MATRICULE) }}" class="btn btn-primary">Modifier</a>
                    <form action="{{ route('employes.destroy', $employe->MATRICULE) }}" method="POST" style="display: inline;">
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


</body>
</html>
