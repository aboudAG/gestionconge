
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Decision Demande de Congé</title>
</head>
<body>
    <h1>Demande de Congé Détails</h1>
    <p><strong>Title:</strong> {{ $demande->TITRE }}</p>
    <!-- Add other fields as needed -->
    
    <!-- Show blade for displaying demande details -->
<form action="{{ route('demandes.decide', $demande->ID) }}" method="POST">
    @csrf
    <div>
        <label for="comment">Commentaire:</label>
        <textarea id="comment" name="comment"></textarea>
    </div>
    <button type="submit" name="decision" value="Accepter">Approuver</button>
    <button type="submit" name="decision" value="Refuser">Refuser</button>
</form>
</body>
</html>