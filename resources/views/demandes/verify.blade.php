<!DOCTYPE html>
<html>
<head>
    <title>Vérification de la Demande</title>
</head>
<body>
    <h1>Vérification de la Demande de Congé</h1>
    <p><strong>Numero de la demande:</strong> {{ $demande->ID }}</p>
    <p><strong>Type du Conge:</strong> {{ $demande->type->NOM }}</p>
    <p><strong>Date de Début:</strong> {{ $demande->DATE_DEBUT->format('Y-m-d') }}</p>
    <p><strong>Date de Fin:</strong> {{ $demande->DATE_FIN->format('Y-m-d') }}</p>

    <h3>Historique des Statuts</h3>
    @foreach($demande->statuts as $statut)
        <p>{{ $statut->STATUT }} au niveau du <strong>{{ $statut->etape->NOM ?? 'N/A' }}</strong> Par <strong>{{ $statut->approbateur->NOM }} {{ $statut->approbateur->PRENOM }}</strong> Le <strong>{{ $statut->DATE_DECISION }}</strong></p>
        <hr>
    @endforeach
</body>
</html>