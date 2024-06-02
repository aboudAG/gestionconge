<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demande History') }}
        </h2>
    </x-slot>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="container mt-4">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Demande History</span>
            </div>
            <div class="card-body">
                @if($demandes->isEmpty())
                    <p class="text-muted">No history available.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Numero demande</th>
                                    <th>Date creation </th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Date debut</th>
                                    <th>Date fin</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $demande)
                                    <tr>
                                        <td>{{ $demande->ID }}</td>
                                        <td>{{ $demande->created_at->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>{{ $demande->TITRE ?? 'N/A' }}</td>
                                        <td>{{ $demande->type->NOM ?? 'N/A' }}</td>
                                        <td>{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
                                        <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge
                                                @if($demande->statuts->first()->STATUT == 'Accepter') bg-success
                                                @elseif($demande->statuts->first()->STATUT == 'En Attente') bg-warning
                                                @elseif($demande->statuts->first()->STATUT == 'Refuser') bg-danger
                                                @endif">
                                                {{ $demande->statuts->first()->STATUT ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#statusModal{{ $demande->ID }}">Details</button>
                                            <form action="{{ route('demandes.destroy', $demande->ID) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="statusModal{{ $demande->ID }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $demande->ID }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $demande->ID }}">Status History for Request #{{ $demande->ID }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group">
                                                        @foreach($demande->statuts as $statut)
                                                            <li class="list-group-item">
                                                                <strong>Status:</strong> {{ $statut->STATUT }}<br>
                                                                <strong>Étape:</strong> {{ $statut->etape->NOM ?? 'N/A' }}<br>
                                                                <strong>Date:</strong> {{ $statut->DATE_DECISION }}<br>
                                                                @if(isset($statut->approbateur))
    <strong>Approbateur:</strong> {{ $statut->approbateur->NOM }} {{ $statut->approbateur->PRENOM }}
@endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    @if($demande->statuts->first()->STATUT == 'Accepter')
                                                        <a href="{{ route('leave-request.download', ['id' => $demande->ID]) }}" class="btn btn-primary">Télécharger la demande</a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    .alert {
        border-radius: 0.4rem;
        padding: 10px 20px;
        margin-bottom: 20px;
        border: none;

    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    /* Animation pour attirer l'attention sur les messages */
    .alert {
        animation: fadeIn 0.5s;
    }

    /* Keyframes pour l'animation fadeIn */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

</style>
