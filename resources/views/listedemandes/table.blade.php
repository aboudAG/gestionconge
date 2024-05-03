<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    .btn {
        padding: 5px 10px;
        color: white;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Date de Début</th>
            <th>Date de Fin</th>
            <th>Nom de l'Employé</th>
            <th>Poste de l'Employé</th>
            <th>Code de la Structure</th>
            <th>Nom de la Structure</th>
            <th>Type de la Structure</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($demandes as $demande)
        <tr>
            <td>{{ $demande->TITRE }}</td>
            <td>{{ $demande->DATE_DEBUT }}</td>
            <td>{{ $demande->DATE_FIN }}</td>
            <td>{{ $demande->employe->NOM ?? 'Non disponible' }} {{ $demande->employe->PRENOM ?? '' }}</td>
            <td>{{ $demande->employe->POSTE ?? 'Non disponible' }}</td>
            <td>{{ optional($demande->employe->structure)->CODE ?? 'Non spécifié' }}</td>
            <td>{{ optional($demande->employe->structure)->NOM ?? 'Non spécifié' }}</td>
            <td>{{ optional($demande->employe->structure)->TYPE ?? 'Non spécifié' }}</td>
            <td> 
               <a href="{{ route('demandes.show', $demande->ID) }}" class="btn btn-primary">Consulter</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>