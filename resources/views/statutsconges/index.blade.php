<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demande History') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <div class="card border">
            <div class="card-header">
                Demande History
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
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $demande)
                                    <tr>
                                        <td>{{ $demande->ID }}</td>
                                        <td>{{ $demande->type->NOM ?? 'N/A' }}</td>
                                        <td>{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
                                        <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                        <td>{{ $demande->statuts->last()->STATUT ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#statusModal{{ $demande->ID }}">Details</button>
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
                                                                <strong>Etape:</strong> {{ $statut->etape->NOM ?? 'N/A' }}<br>
                                                                <strong>Date:</strong> {{ $statut->DATE_DECISION }}<br>
                                                                <strong>Approbateur:</strong> {{ $statut->approbateur->NOM }} {{ $statut->approbateur->PRENOM }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
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