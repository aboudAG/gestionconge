<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>role</title>
</head>
<body>



<div class="container">
    <h1>Ajouter un nouveau rôle</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf {{-- Token CSRF pour la sécurité de votre formulaire --}}

        <div class="mb-3">
            <label for="NOM" class="form-label">Nom du rôle</label>
            <input type="text" class="form-control" id="NOM" name="NOM" value="{{ old('NOM') }}" required autofocus>
            @error('NOM')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ajoutez d'autres champs si nécessaire --}}

        <button type="submit" class="btn btn-primary">Créer le rôle</button>
    </form>
</div>


</body>
</html>
