{{-- <style>
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
        text-align: center;
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
</style> --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class = "container">
                        <h3 class="text-lg font-semibold mb-4">Liste des demandes de congés</h3>
                </div>

                <table class="min-w-full divide-y divide-gray-200" id="table">
                    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Début</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Fin</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poste</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code Structure</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom Structure</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type Structure</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($demandes as $demande)
        <tr>
            <td class = " px-6 py-4 whitespace-nowrap">{{ $demande->TITRE }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ $demande->employe->NOM ?? 'Non disponible' }} {{ $demande->employe->PRENOM ?? '' }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ $demande->employe->POSTE ?? 'Non disponible' }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ optional($demande->employe->structure)->CODE ?? 'Non spécifié' }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ optional($demande->employe->structure)->NOM ?? 'Non spécifié' }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">{{ optional($demande->employe->structure)->TYPE ?? 'Non spécifié' }}</td>
            <td class = " px-6 py-4 whitespace-nowrap">
               <a href="{{ route('decisiondemande.show', $demande->ID) }}" data-bs-toggle="modal" data-bs-target="#decisionModal{{ $demande->ID }}" class="btn btn-primary" style="background-color : black;">Consulter</a>
            </td>

        </tr>

        <div class="modal fade" id="decisionModal{{ $demande->ID }}" tabindex="-1" aria-labelledby="modalLabel{{ $demande->ID }}" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel{{ $demande->ID }}" >Décision Demande de Congé</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <h1>Demande de Congé Détails</h1>
                   <p><strong>Titre:</strong> {{ $demande->TITRE }}</p>

                   <p><strong>Nom:</strong> {{ $demande->employe->NOM}}</p>
                   <p><strong>Poste:</strong> {{ $demande->employe->POSTE}}</p>
                  <form action="{{ route('demandes.decide', $demande->ID) }}" method="POST">
                     @csrf
                    <div class="mb-3">
                      <label for="comment" class="form-label">Commentaire:</label>
                      <textarea id="comment" name="comment" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="decision" value="Accepter" class="btn btn-success" style="background-color: black;">Approuver</button>
                    <button type="submit" name="decision" value="Refuser" class="btn btn-danger" style="background-color: rgb(212, 205, 205); color: rgb(133, 16, 16);">Refuser</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @endforeach
    </tbody>

</table>

</div>
</div>
</div>
</div>
</div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>




  <script>
      $(document).ready(function() {
      $('#table').DataTable({
          "language": {
              "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"  // Charger la traduction en Français
          }
      });
      });
</script>
