{{-- resources/views/employes/create.blade.php --}}

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
        <h1>Inscription d'un nouvel employé</h1>
        <form  action="{{ route('employes.store') }}" method="POST">
            @csrf {{-- Protection contre les attaques CSRF --}}

            {{-- Champs du formulaire pour l'inscription d'un nouvel employé --}}
            <div class="mb-3">
                <label for="MATRICULE" class="form-label">Matricule</label>
                <input type="text" class="form-control" id="MATRICULE" name="MATRICULE" value="{{ old('MATRICULE') }}" required>
            </div>

            <div class="mb-3">
                <label for="NOM" class="form-label">Nom</label>
                <input type="text" class="form-control" id="NOM" name="NOM" value="{{ old('NOM') }}" required>
            </div>

            <div class="mb-3">
                <label for="PRENOM" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="PRENOM" name="PRENOM" value="{{ old('PRENOM') }}" required>
            </div>

            <div class="mb-3">
                <label for="ROLE_ID" class="form-label">Role</label>
                <select class="form-select" id="ROLE_ID" name="ROLE_ID" required>
                    <option value="">Choisir...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->NOM }}" {{ old('ROLE_ID') == $role->NOM ? 'selected' : '' }}>
                            {{ $role->NOM  }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="DATE_EMBAUCHE" class="form-label">Date d'embauche</label>
                <input type="date" class="form-control" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE" value="{{ old('DATE_EMBAUCHE') }}" required>
            </div>

            <div class="mb-3">
                <label for="STRUCTURE_ID" class="form-label">Structure</label>
                <select class="form-select" id="STRUCTURE_ID" name="STRUCTURE_ID" required>
                    <option value="">Choisir...</option>
                    @foreach($structures as $structure)
                        <option value="{{ $structure->NOM }}" {{ old('STRUCTURE_ID') == $structure->NOM ? 'selected' : '' }}>
                            {{ $structure->NOM }}
                        </option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>
</body>
</html>

