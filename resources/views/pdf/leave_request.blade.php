<!DOCTYPE html>
<html>
<head>
    <title>Demande de Congé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }
        .content {
            margin: 20px;
        }
        .content h3 {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .content p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        
        <h1>Demande de Congé</h1>
    </div>
    <div class="content">
        <h3>Informations sur la demande</h3>
        <p><strong>Numero de la demande:</strong> {{ $demande->ID }}</p>
        <p><strong>Type du Conge:</strong> {{ $demande->type->NOM }}</p>
        <p><strong>Date de Début:</strong> {{ $demande->DATE_DEBUT->format('Y-m-d') }}</p>
        <p><strong>Date de Fin:</strong> {{ $demande->DATE_FIN->format('Y-m-d') }}</p>

        <h3>Historique des Statuts</h3>
        @foreach($demande->statuts as $statut)
            <p>{{ $statut->STATUT }} au niveau du <strong>{{ $statut->etape->NOM ?? 'N/A' }}</strong> Par <strong>{{ $statut->approbateur->NOM }} {{ $statut->approbateur->PRENOM }}</strong> Le <strong>{{ $statut->DATE_DECISION }}</strong></p>
            <hr>
        @endforeach
    </div>
</body>
</html>
