<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suivi de la Demande de Congé') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        @if(isset($message))
            <div class="alert alert-info">{{ $message }}</div>
        @else
            <div class="card mb-4 border">
                <div class="card-header bg-primary text-white">
                    Information de la Demande
                </div>
                <div class="card-body">
                    <p><strong>Type de Demande:</strong> {{ $currentLeaveRequest->type->NOM }}</p>
                    <p><strong>Date de dépot:</strong> {{ $currentLeaveRequest->DATE_CREATION }}</p>
                    <p><strong>Date de Début:</strong> {{ $currentLeaveRequest->DATE_DEBUT->format('Y-m-d') }}</p>
                    <p><strong>Date de Fin:</strong> {{ $currentLeaveRequest->DATE_FIN->format('Y-m-d') }}</p>
                    <p><strong>Statut:</strong> En cours</p>
                </div>
            </div>

            @if($pendingStages->isNotEmpty())
                <div class="card mb-4 border">
                    <div class="card-header bg-warning text-white">
                        Étape en Attente
                    </div>
                    <div class="card-body">
                        @foreach($pendingStages as $stage)
                            <p><strong>Étape:</strong> {{ $stage->etape->NOM }}</p>
                            
                        @endforeach
                    </div>
                </div>
            @endif

            @if($completedStages->isNotEmpty())
                <div class="card mb-4 border">
                    <div class="card-header bg-success text-white">
                        Étapes Terminées
                    </div>
                    <div class="card-body">
                        @foreach($completedStages as $stage)
                            <p><strong>Étape:</strong> {{ $stage->etape->NOM }}</p>
                            <p><strong>Date de Décision:</strong> {{ $stage->DATE_DECISION }}</p>
                            <p><strong>Approbateur:</strong> {{ $stage->approbateur->NOM }} {{ $stage->approbateur->PRENOM }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
