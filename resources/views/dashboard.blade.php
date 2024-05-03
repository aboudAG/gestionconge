<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" style="display:flex; flex-direction:column;">
                    
                    <a href="{{ route('employes.create') }}" class="btn btn-primary">Ajouter un nouvel employé</a>
                    <a href="{{ route('employes.index') }}" class="btn btn-primary">Liste employé</a>
                    <a href="{{ route('demandes.create') }}" class="btn btn-primary">Demande Congé</a>
                    <a href="{{ route('listedemandes.index') }}" class="btn btn-primary">Liste demandes</a>
                    
                </div>
                
            </div>
        </div>
    </div>





 
</x-app-layout>
