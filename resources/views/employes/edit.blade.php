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
    <form action="{{ route('employes.update', $employe->MATRICULE) }}" method="POST">
        @csrf

        @method('PUT')
        <div class="form-group">
            <label for="MATRICULE">Matricule</label>
            <input type="text" class="form-control" id="MATRICULE" name="MATRICULE" value="{{$employe->MATRICULE}}">
        </div>

        <div class="form-group">
            <label for="NOM">Nom</label>
            <input type="text" class="form-control" id="NOM" name="NOM"  value="{{$employe->NOM}}">
        </div>

        <div class="form-group">
            <label for="PRENOM">Preom</label>
            <input type="text" class="form-control" id="PRENOM" name="PRENOM"  value="{{$employe->PRENOM}}">
        </div>

        <div class="form-group">
            <label for="POSTE">Poste</label>
            <input type="text" class="form-control" id="POSTE" name="POSTE"  value="{{$employe->POSTE}}">
        </div>

        <div class="form-group">
            <label for="DATE_EMBAUCHE">Date d'embauche</label>
            <input type="date" class="form-control" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE"  value="{{$employe->DATE_EMBAUCHE}}"required>
        </div>

        <!-- Ajoute d'autres champs selon tes besoins -->

        <div class="form-group">
            <label for="ROLE">Rôle</label>
            <select class="form-control" id="ROLE" name="ROLE_ID"  >
                @foreach($roles as $role)
                    <option value="{{ $role->ID }}">{{ $role->NOM }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="STRUCTURE">Structure</label>
            <select class="form-control" id="STRUCTURE" name="STRUCTURE_ID">
                @foreach($structures as $structure)
                    <option value="{{ $structure->ID }}">{{ $structure->NOM }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>

        </form>
    </div>
</body>
</html>

