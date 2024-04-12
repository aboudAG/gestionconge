<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


<form action="{{ route('structures.update', $structure->ID) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="CODE">Code:</label>
        <input type="text" name="CODE" id="CODE" value="{{  $structure->CODE }}" required>
    </div>

    <div>
        <label for="NOM">Nom:</label>
        <input type="text" name="NOM" id="NOM" value="{{  $structure->NOM }}" required>
    </div>

    <div>
        <label for="TYPE">Type:</label>
        <input type="text" name="TYPE" id="TYPE" value="{{  $structure->TYPE }}" required>
    </div>

    <div>
        <label for="PARENT_ID">ID Parent (facultatif):</label>
        <input type="number" name="PARENT_ID" id="PARENT_ID" value="{{  $structure->PARENT_ID }}">
    </div>

    <button type="submit">Mettre à jour</button>
</form>

</body>
</html>
