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
            <td>{{ $demande->employe->NOM }} {{ $demande->employe->PRENOM }}</td>
            <td>{{ $demande->employe->POSTE }}</td>
            <td>{{ $demande->employe->structure->CODE }}</td>
            <td>{{ $demande->employe->structure->NOM }}</td>
            <td>{{ $demande->employe->structure->TYPE }}</td>
            <td> 
               <a href="{  route('demandes.show', $demande->ID) }}" class="btn btn-primary">Consulter</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>