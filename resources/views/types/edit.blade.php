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
                    <div class="card-header">Créer un nouveau type de congé</div>

                    <div class="card-body">
                        <form action="{{ route('types.update', $type->ID) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="nom">Nom du type de congé</label>
                                <input type="text" class="form-control" id="nom" name="NOM" value="{{$type->NOM}}" required>
                            </div>

                            <!-- Ajoute d'autres champs du formulaire si nécessaire -->

                            <button type="submit" class="btn btn-primary">Créer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
