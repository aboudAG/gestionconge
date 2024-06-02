
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $delegated ? __('Liste des demandes déléguer en attente') : __('Liste de demande de congés en attente') }}
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
                                    <th>Numero:</th>
                                    <th>Titre</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                    <th>Nom</th>
                                    <th>Poste</th>
                                    <th>Code Structure</th>
                                    <th>Nom Structure</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $demande)
                                    <tr>

                                        <td>{{ $demande->ID }}</td>
                                        <td>{{ $demande->TITRE }}</td>
                                        <td>{{ $demande->DATE_DEBUT->format('Y-m-d') }}</td>
                                        <td>{{ $demande->DATE_FIN->format('Y-m-d') }}</td>
                                        <td>{{ $demande->employe->NOM ?? 'Non disponible' }} {{ $demande->employe->PRENOM ?? '' }}</td>
                                        <td>{{ $demande->employe->POSTE ?? 'Non disponible' }}</td>
                                        <td>{{ optional($demande->employe->structure)->CODE ?? 'Non spécifié' }}</td>
                                        <td>{{ optional($demande->employe->structure)->NOM ?? 'Non spécifié' }}</td>

                                        <td>
                                            <a href="{{ route('decisiondemande.show', $demande->ID) }}" class="btn btn-primary btn-sm">Consulter</a>
                                        </td>
                                    </tr>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

