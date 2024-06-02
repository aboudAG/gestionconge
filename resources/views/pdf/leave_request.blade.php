<!DOCTYPE html>
<html>
<head>
    <title>Demande de Congé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            position: relative;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        .header img {
            width: 100px;
            height: auto;
        }
        .header .title {
            flex: 1;
            text-align: center;
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
        .qr-code {
            position: absolute;
            bottom: 80px; /* Espace au-dessus du pied de page */
            right: 20px;
            width: 150px; /* Taille ajustée pour 150% */
            height: 150px; /* Taille ajustée pour 150% */
        }
        .qr-code img {
            width: 100%;
            height: 100%;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <p>ENTREPRISE PORTUAIRE D'ALGER</p>
        </div>
        <div class="title">
            <h1>Demande de Congé</h1>
        </div>
        <div>
            <img src="{{ public_path('images/logo.jpg') }}" alt="Logo">
        </div>
    </div>
    <div class="content">
        <h3>Informations sur la demande</h3>
        <p><strong>Numero de la demande:</strong> {{ $demande->ID }}</p>
        <p><strong>Date de la demande:</strong> {{ $demande->DATE_CREATION }}</p>
        <p><strong>Titre de la demande:</strong> {{ $demande->TITRE }}</p>
        <p><strong>Employé:</strong> {{ $demande->employe->NOM }} {{ $demande->employe->PRENOM }}</p>
        <p><strong>Structure:</strong> {{ $demande->employe->structure->NOM }} ( {{ $demande->employe->structure->CODE }} )</p>
        <p><strong>Poste:</strong> {{ $demande->employe->POSTE }}</p>
        <p><strong>Congé:</strong> {{ $demande->type->NOM }}</p>
        <p><strong>Date de début du congé:</strong> {{ $demande->DATE_DEBUT->format('Y-m-d') }}</p>
        <p><strong>Date de fin du congé:</strong> {{ $demande->DATE_FIN->format('Y-m-d') }}</p>
        <p><strong>Remplacant:</strong> {{ $demande->remplacant->NOM }} {{ $demande->remplacant->PRENOM }} ( {{ $demande->remplacant->POSTE }} )</p>


        <h3>Historique d'approbation</h3>
        @foreach($demande->statuts as $statut)
            <p>{{ $statut->STATUT }} au niveau du <strong>{{ $statut->etape->NOM ?? 'N/A' }}</strong> Par <strong>{{ $statut->approbateur->NOM }} {{ $statut->approbateur->PRENOM }}</strong> Le <strong>{{ $statut->DATE_DECISION }}</strong></p>
            <hr>
        @endforeach
    </div>

    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code">
    </div>

    <div class="footer">
        <p>Veuillez déposer cette demande au service comptable relié à votre structure</p>
    </div>
</body>
</html>
