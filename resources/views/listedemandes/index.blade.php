<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liste des Demandes de Congés</title>
</head>
<body>
    @if(isset($demandes))
    <h1>Demandes de Congés </h1>
    @include('listedemandes.table', ['demandes' => $demandes])
    @endif

</body>
</html>