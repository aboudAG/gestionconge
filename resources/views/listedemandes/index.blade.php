<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Demandes de Congés') }}</h2>
    </x-slot>

    <!-- Content here -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($demandes))
        <h1>Demandes de Congés</h1>
        @include('listedemandes.table', ['demandes' => $demandes])
    @endif
</x-app-layout>