<style>
    p {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
}

/* Style du formulaire */
form {
    background-color: #fff;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 10px;
    color: #333;
}

textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none;
    box-sizing: border-box; /* Ajoute le padding dans le calcul de la largeur/hauteur */
}

/* Style des boutons */
button {
    padding: 10px 20px;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
}

button[type="submit"][name="decision"][value="Accepter"] {
    background-color: #4CAF50;
}

button[type="submit"][name="decision"][value="Refuser"] {
    background-color: #f44336;
}
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Decision') }}
        </h2>
    </x-slot>

    <h3 class="text-lg font-semibold mb-4">Tous les Employes</h3>
    <p><strong>Title:</strong> {{ $demande->TITRE }}</p>
    <p><strong>Nom:</strong> {{ $demande->employe->NOM}}</p>
    <p><strong>Poste:</strong> {{ $demande->employe->POSTE}}</p>
    <!-- Add other fields as needed -->

    <!-- Show blade for displaying demande details -->
<form action="{{ route('demandes.decide', $demande->ID) }}" method="POST">
    @csrf
    <div>
        <label for="comment">Commentaire:</label>
        <textarea id="comment" name="comment"></textarea>
    </div>
    <button type="submit" name="decision" value="Accepter"> Approuver</button>
    <button type="submit" name="decision" value="Refuser">Refuser</button>
</form>
</x-app-layout>
