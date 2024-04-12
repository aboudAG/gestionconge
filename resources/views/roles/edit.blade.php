<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Modifier le Rôle</h1>
        <form action="{{ route('roles.update', $role->ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="NOM">Nom du Rôle</label>
                <input type="text" class="form-control" id="NOM" name="NOM" value="{{ old('NOM', $role->NOM) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
