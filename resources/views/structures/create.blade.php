<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    {{-- resources/views/structures/create.blade.php --}}


<div class="container">
    <h1>Créer une nouvelle structure</h1>
    <form method="POST" action="{{ route('structures.store') }}">
        @csrf
        <div class="mb-3">
            <label for="CODE" class="form-label">Code</label>
            <input type="text" class="form-control" id="CODE" name="CODE" required>
        </div>
        <div class="mb-3">
            <label for="NOM" class="form-label">Nom</label>
            <input type="text" class="form-control" id="NOM" name="NOM" required>
        </div>
        <div class="mb-3">
            <label for="TYPE" class="form-label">Type</label>
            <input TYPE="text" class="form-control" id="TYPE" name="TYPE" required>
        </div>
        <div class="mb-3">
            <label for="PARENT_ID" class="form-label">Structure Parente (Optionnel)</label>
            <input type="number" class="form-control" id="PARENT_ID" name="PARENT_ID">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

</body>
</html>
