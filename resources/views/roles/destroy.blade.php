<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Éditer le Rôle</h1>

        <form action="{{ route('roles.update', $role->ID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nom" class="form-label">Nom du Rôle</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ $role->NOM }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Sauvegarder les changements</button>
        </form>
    </div>
</body>
</html>
