<style>
    .alert {
        padding: 15px;
        background-color: #f4f4f4;
        border: 1px solid #ccc;
        margin-bottom: 20px;
    }
    .alert-success {
        background-color: #dff0d8;
        border-color: #d6e9c6;
        color: #3c763d;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statuts') }}
        </h2>
    </x-slot>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Statut des Demandes</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Début</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Etape</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($demandes as $demande)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $demande->ID }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $demande->TITRE }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $demande->DATE_DEBUT }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $demande->DATE_FIN }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($demande->statuts->count() > 0)
                                        {{ $demande->statuts->last()->STATUT }}
                                    @else
                                        En attente
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($demande->statuts->count() > 0)
                                        {{ $demande->statuts->last()->ETAPE }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
