<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste de demande de congés en attente') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mt-4">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Liste des demandes de congés</span>
            </div>
            <div class="card-body">
                @if(isset($demandes))
                    <div class="table-responsive">
                        <table class="table table-striped" id="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Nom</th>
                                    <th>Poste</th>
                                    <th>Code Structure</th>
                                    <th>Nom Structure</th>
                                    <th>Type Structure</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $demande)
                                    <tr>
                                        <td>{{ $demande->TITRE }}</td>
                                        <td>{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
                                        <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                        <td>{{ $demande->employe->NOM ?? 'Non disponible' }} {{ $demande->employe->PRENOM ?? '' }}</td>
                                        <td>{{ $demande->employe->POSTE ?? 'Non disponible' }}</td>
                                        <td>{{ optional($demande->employe->structure)->CODE ?? 'Non spécifié' }}</td>
                                        <td>{{ optional($demande->employe->structure)->NOM ?? 'Non spécifié' }}</td>
                                        <td>{{ optional($demande->employe->structure)->TYPE ?? 'Non spécifié' }}</td>
                                        <td>
                                            <a href="{{ route('decisiondemande.show', $demande->ID) }}" data-bs-toggle="modal" data-bs-target="#decisionModal{{ $demande->ID }}" class="btn btn-primary btn-sm">Consulter</a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="decisionModal{{ $demande->ID }}" tabindex="-1" aria-labelledby="modalLabel{{ $demande->ID }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $demande->ID }}">Décision Demande de Congé</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h1>Demande de Congé Détails</h1>
                                                    <p><strong>Titre:</strong> {{ $demande->TITRE }}</p>
                                                    <p><strong>Nom:</strong> {{ $demande->employe->NOM }}</p>
                                                    <p><strong>Poste:</strong> {{ $demande->employe->POSTE }}</p>
                                                    <form action="{{ route('demandes.decide', $demande->ID) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="comment" class="form-label">Commentaire:</label>
                                                            <textarea id="comment" name="comment" class="form-control"></textarea>
                                                        </div>
                                                        <button type="submit" name="decision" value="Accepter" class="btn btn-success">Approuver</button>
                                                        <button type="submit" name="decision" value="Refuser" class="btn btn-danger">Refuser</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"
            }
        });
    });
</script>

<style>
    .bg-success {
        background-color: #28a745 !important;
        color: white;
    }
    .bg-warning {
        background-color: #ffc107 !important;
        color: black;
    }
    .bg-danger {
        background-color: #dc3545 !important;
        color: white;
    }
</style>