<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Decision') }} pour la demande {{ $demande->ID }}
            </h2>
            <div>
                <form id="decisionForm" action="{{ route('demandes.decide', $demande->ID) }}" method="POST">
                    @csrf
                    <input type="hidden" name="decision" id="decisionInput" value="">
                    <div class="d-flex align-items-center">
                        <button type="button" onclick="setDecision('Accepter')" class="btn btn-success me-2">Approuver</button>
                        <button type="button" onclick="setDecision('Refuser')" class="btn btn-danger">Refuser</button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="container mt-4">

        <!-- Flex Container for Cards -->
        <div class="d-flex flex-row justify-content-between mb-4">
            <!-- Détails de la demande de congé -->
            <div class="card flex-fill mr-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    
                    <h5>Détails de la demande de congé</h5>
                    
                    @if($exerciceData->isNotEmpty())
                    
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exerciceModal">
                            Voir Exercice Choisi
                        </button>
                        <button type="button" class="btn btn-primary btn-sm " data-toggle="modal" data-target="#exerciceModal">
                            Justificatif
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <p><strong>Numero de la demande:</strong> {{ $demande->ID }}</p>
                    <p><strong>Type de congé:</strong> {{ $demande->TYPE->NOM }}</p>
                    <p><strong>Créer le:</strong> {{ $demande->DATE_CREATION }}</p>
                    <p><strong>Date de début:</strong> {{ $demande->DATE_DEBUT->format('d/m/Y') }}</p>
                    <p><strong>Date de fin:</strong> {{ $demande->DATE_FIN->format('d/m/Y') }}</p>
                    <p><strong>Durée du congé:</strong> {{ $demande->DATE_DEBUT->diffInDays($demande->DATE_FIN) + 1 }} jours</p>
                    <p><strong>Titre de la demande:</strong> {{ $demande->TITRE }}</p>
                    <p><strong>Remplaçant:</strong> {{ $demande->remplacant->NOM }} {{ $demande->remplacant->PRENOM }} ({{ $demande->remplacant->POSTE }})</p>
                </div>
            </div>

            <!-- Informations sur l'employé -->
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Informations sur l'employé</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#soldeModal">
                        Voir solde de congé
                    </button>
                </div>
                <div class="card-body">
                    <div><strong>Matricule:</strong> {{ $demande->employe->MATRICULE }}</div>
                    <p><strong>Nom et prénom:</strong> {{ $demande->employe->NOM }} {{ $demande->employe->PRENOM }}</p>
                    <p><strong>Poste:</strong> {{ $demande->employe->POSTE }}</p>
                    <p><strong>Date d'embauche:</strong> {{ $demande->employe->DATE_EMBAUCHE }}</p>
                    <p><strong>Structure:</strong> {{ $demande->employe->structure->NOM }}</p>
                </div>
            </div>
        </div>

        <!-- Historique des demandes de congé -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Historique des demandes de congé</h5>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @if($userDemandesConges->isEmpty())
                    <p class="text-muted">No history available.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <>
                                    <th>Numero demande</th>
                                    <th>Type</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Nombre jours</th>
                                    <th>Statut</th>
                                    <th>Étape</th>
                                    <th>Actions:</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userDemandesConges as $historique)
                                    <tr>
                                        <td>{{ $historique->ID ?? 'N/A' }}</td>
                                        <td>{{ $historique->type->NOM ?? 'N/A' }}</td>
                                        <td>{{ $historique->DATE_DEBUT->format('Y-m-d') }}</td>
                                        <td>{{ $historique->DATE_FIN->format('Y-m-d') }}</td>
                                        <td>{{ $historique->DATE_DEBUT->diffInDays($historique->DATE_FIN) + 1 }} jours</td>
                                        <td>{{ $historique->latestStatut ?? 'Terminé' }}</td>
                                        <td>{{ $historique->latestEtape ?? '----' }}</td>
                                        <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exerciceModal">
                                            Détails
                                        </button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Plan de congé de l'équipe -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Plan de congé de l'équipe</h5>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exerciceModal">
                    Liste des employés:
                </button>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @php
                    $hasFutureLeaves = false;
                @endphp
                
                @foreach($teamMembersOnLeave as $member)
                    @foreach($member->demandes as $demande)
                        @if($demande->DATE_FIN->isFuture())
                            @php
                                $hasFutureLeaves = true;
                            @endphp
                        @endif
                    @endforeach
                @endforeach

                @if(!$hasFutureLeaves)
                    <p class="text-muted">No team members are currently on leave or have upcoming leave planned.</p>
                @else
                    @foreach($teamMembersOnLeave as $member)
                        @foreach($member->demandes as $demande)
                            @if($demande->DATE_FIN->isFuture())
                                <div class="row mb-3 pb-3 border-bottom">
                                    <div class="col-6">
                                        <div><strong>Matricule:</strong> {{ $member->MATRICULE }}</div>
                                        <div><strong>Employé:</strong> {{ $member->NOM }} {{ $member->PRENOM }}</div>
                                        <div><strong>Poste:</strong> {{ $member->POSTE }}</div>
                                        <div><strong>Date début:</strong> {{ $demande->DATE_DEBUT->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div><strong>Statut:</strong> 
                                            @if (now()->between($demande->DATE_DEBUT, $demande->DATE_FIN))
                                                En cours
                                                ({{ now()->diffInDays($demande->DATE_FIN) + 1 }} jours restants)
                                            @else
                                                Prévue
                                            @endif
                                        </div>
                                        <div><strong>Date fin:</strong> {{ $demande->DATE_FIN->format('Y-m-d') }}</div>
                                        <div><strong>Congé:</strong> {{ $demande->type->NOM }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Modal for Refuser Comment -->
        <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Motif de refus pour la demande ({{ $demande->ID }}):</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="commentForm">
                            <div class="form-group">
                                <label for="comment">Commentaire:</label>
                                <textarea id="comment" name="comment" class="form-control" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" onclick="submitDecisionForm()">Soumettre</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Solde de Congé -->
        <div class="modal fade" id="soldeModal" tabindex="-1" role="dialog" aria-labelledby="soldeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="soldeModalLabel">Solde de congé pour {{ $demande->employe->NOM }} {{ $demande->employe->PRENOM }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @foreach($soldeCongeRestant as $solde)
                            <p><strong>Solde de congé restant pour {{ $solde->ANNEE }}:</strong> {{ $solde->JOURS_RESTANT }} jours</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Exercice Choisi -->
        <div class="modal fade" id="exerciceModal" tabindex="-1" role="dialog" aria-labelledby="exerciceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exerciceModalLabel">Exercice Choisi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            @foreach($exerciceData as $data)
                                <li><strong>Année:</strong> {{ $data->droitConge->ANNEE }}, <strong>Jours pris:</strong> {{ $data->JOURS_PRIS }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Bootstrap JS and jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        
        <script>
            function setDecision(decision) {
                document.getElementById('decisionInput').value = decision;
                if (decision === 'Refuser') {
                    $('#commentModal').modal('show');
                } else {
                    document.getElementById('decisionForm').submit();
                }
            }

            function submitDecisionForm() {
                const decision = document.getElementById('decisionInput').value;
                if (decision === 'Refuser') {
                    const comment = document.getElementById('comment').value;
                    if (!comment) {
                        alert('Veuillez entrer un commentaire.');
                        return;
                    }
                }
                document.getElementById('decisionForm').submit();
            }
        </script>
    </div>
</x-app-layout>