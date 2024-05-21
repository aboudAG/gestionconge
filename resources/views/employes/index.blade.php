




    <style>
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

    .form-control {
    position: relative; /* Assure que la position relative est appliquée au champ de saisie */
}

.field-icon {
    position: absolute;
    right: 25px; /* Ajustez selon la marge désirée du bord droit du champ */
    top: 81.5%;
    transform: translateY(-50%); /* Centrage vertical de l'icône */
    cursor: pointer;
    z-index: 2; /* S'assure que l'icône est au-dessus des autres éléments mais pas trop élevé pour éviter les conflits de z-index */
}

.btn:hover{
    background-color: gray;
}
    </style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(' Employes') }}
        </h2>
    </x-slot>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <!-- Lien CSS pour Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <!-- Script JavaScript pour Choices.js -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    {{-- resources/views/employes/index.blade.php --}}

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


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class = "container">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="text-lg font-semibold mb-4">Liste des Employes</h3>
                    {{-- <button type="button" class="btn btn-black" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" style="margin-bottom: 10px; background-color : gray; color:white;  ">
                        Ajouter un Employé
                    </button> --}}
                        </div>

                    </div>

                    <table class="min-w-full divide-y divide-gray-200" id="table">
                        <thead class="bg-gray-50">

                         <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poste</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'embauche</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Structure</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($employes as $employe)
            <tr>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->MATRICULE }}</td>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->NOM }}</td>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->PRENOM }}</td>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->POSTE }}</td>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->DATE_EMBAUCHE }}</td>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->structure->NOM }}</td>
                <td class = " px-6 py-4 whitespace-nowrap">{{ $employe->role->NOM }}</td>
                {{-- <td class = " px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('employes.edit', $employe->MATRICULE) }}" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateEmployeeModal{{$employe->MATRICULE}}" >Modifier</a>
                    <form action="{{ route('employes.destroy', $employe->MATRICULE) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td> --}}
            </tr>
{{--
        <div class="modal fade" id="updateEmployeeModal{{$employe->MATRICULE}}" tabindex="-1" aria-labelledby="updateEmployeeModalLabel{{$employe->MATRICULE}}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateEmployeeModalLabel{{$employe->MATRICULE}}">Modifier Employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employes.store' , $employe->MATRICULE) }}" method="POST" class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="MATRICULE" class="form-label">Matricule</label>
                            <input type="text" class="form-control" id="MATRICULE" name="MATRICULE" value="{{$employe->MATRICULE}}">
                        </div>
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM" value="{{$employe->NOM}}">
                        </div>
                        <div class="col-md-6">
                            <label for="PRENOM" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="PRENOM" name="PRENOM" value="{{$employe->PRENOM}}">
                        </div>
                        <div class="col-md-6">
                            <label for="POSTE" class="form-label">Poste</label>
                            <input type="text" class="form-control" id="POSTE" name="POSTE" value="{{$employe->POSTE}}">
                        </div>
                        <div class="col-md-6">
                            <label for="DATE_EMBAUCHE" class="form-label">Date d'embauche</label>
                            <input type="date" class="form-control" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE" value="{{$employe->DATE_EMBAUCHE}}">
                        </div>
                        <div class="col-md-6">
                            <label for="ROLE" class="form-label">Rôle</label>
                            <select class="form-select" id="ROLE" name="ROLE_ID">
                                @foreach($roles as $role)
                                <option value="{{ $role->ID }}">{{ $role->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="STRUCTURE" class="form-label">Structure</label>
                            <select class="form-select" id="STRUCTURE" name="STRUCTURE_ID" >
                                @foreach($structures as $structure)
                                <option value="{{ $structure->ID }}">{{ $structure->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="background-color: black;">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
{{--
    <!-- Modale -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Nouvel Employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employes.store') }}" method="POST" class="row">
                        @csrf
                        <div class="col-md-6">
                            <label for="MATRICULE" class="form-label">Matricule</label>
                            <input type="text" class="form-control" id="MATRICULE" name="MATRICULE">
                        </div>
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM" oninput="updateName()">
                        </div>
                        <div class="col-md-6">
                            <label for="PRENOM" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="PRENOM" name="PRENOM" oninput="updateName()">
                        </div>
                        <div class="col-md-6">
                            <label for="POSTE" class="form-label">Poste</label>
                            <input type="text" class="form-control" id="POSTE" name="POSTE">
                        </div>
                        <div class="col-md-6">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="col-md-6">
                            <label for="DATE_EMBAUCHE" class="form-label">Date d'embauche</label>
                            <input type="date" class="form-control" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE">
                        </div>
                        <div class="col-md-6">
                            <label for="ROLE" class="form-label">Rôle</label>
                            <select class="form-select" id="ROLE" name="ROLE_ID">
                                @foreach($roles as $role)
                                <option value="{{ $role->ID }}">{{ $role->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="STRUCTURE" class="form-label">Structure</label>
                            <select class="form-select" id="STRUCTURE" name="STRUCTURE_ID" >
                                @foreach($structures as $structure)
                                <option value="{{ $structure->ID }}">{{ $structure->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        @php
                                $randomPassword = Illuminate\Support\Str::random(10); // Génération d'un mot de passe
                            @endphp
                        <div class="col-md-6">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="form-control"
                                          type="password"
                                          name="password"
                                          value="{{ $randomPassword }}"
                                          required autocomplete="new-password" />
                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="col-md-6">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="form-control"
                                          type="password"
                                          name="password_confirmation"
                                          value="{{ $randomPassword }}"
                                          required autocomplete="new-password" />
                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        <div>
                            <label for="name" class="form-label" style="display: none;">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" style="display: none;">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="background-color: black;">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modale -->
    {{-- <div class="modal fade" id="updateEmployeeModal" tabindex="-1" aria-labelledby="updateEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateEmployeeModalLabel">Nouvel Employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employes.update' , $employe->MATRICULE) }}" method="POST" class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="MATRICULE" class="form-label">Matricule</label>
                            <input type="text" class="form-control" id="MATRICULE" name="MATRICULE" value="{{$employe->MATRICULE}}">
                        </div>
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM" value="{{$employe->NOM}}">
                        </div>
                        <div class="col-md-6">
                            <label for="PRENOM" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="PRENOM" name="PRENOM" value="{{$employe->PRENOM}}">
                        </div>
                        <div class="col-md-6">
                            <label for="POSTE" class="form-label">Poste</label>
                            <input type="text" class="form-control" id="POSTE" name="POSTE" value="{{$employe->POSTE}}">
                        </div>
                        <div class="col-md-6">
                            <label for="DATE_EMBAUCHE" class="form-label">Date d'embauche</label>
                            <input type="date" class="form-control" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE" value="{{$employe->DATE_EMBAUCHE}}">
                        </div>
                        <div class="col-md-6">
                            <label for="ROLE" class="form-label">Rôle</label>
                            <select class="form-select" id="ROLE" name="ROLE_ID">
                                @foreach($roles as $role)
                                <option value="{{ $role->ID }}">{{ $role->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="STRUCTURE" class="form-label">Structure</label>
                            <select class="form-select" id="STRUCTURE" name="STRUCTURE_ID">
                                @foreach($structures as $structure)
                                <option value="{{ $structure->ID }}">{{ $structure->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="background-color: black;">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


</x-app-layout>

<script>
    $(document).ready(function() {
    $('#table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"  // Charger la traduction en Français
        }
    });
    });
    document.addEventListener("DOMContentLoaded", function () {
      // Fonction pour basculer la visibilité du mot de passe
      document.querySelectorAll('.toggle-password').forEach(function (element) {
        element.addEventListener('click', function () {
          const input = document.querySelector(element.getAttribute('toggle'));
          if (input.type === 'password') {
            input.type = 'text';
            element.classList.add('fa-eye-slash');
            element.classList.remove('fa-eye');
          } else {
            input.type = 'password';
            element.classList.remove('fa-eye-slash');
            element.classList.add('fa-eye');
          }
        });
      });
    });

        document.addEventListener('DOMContentLoaded', function () {
            const nomInput = document.getElementById('NOM');
            const prenomInput = document.getElementById('PRENOM');
            const fullNameInput = document.getElementById('name');

            function updateFullName() {
                fullNameInput.value = nomInput.value + ' ' + prenomInput.value;
            }

            nomInput.addEventListener('input', updateFullName);
            prenomInput.addEventListener('input', updateFullName);
        });

        document.addEventListener('DOMContentLoaded', function() {
    var elements = document.getElementsByClassName('form-select');
    Array.prototype.forEach.call(elements, function(element) {
        var choices = new Choices(element, {
            searchEnabled: true,
            shouldSort: false,
            placeholderValue: 'Sélectionnez une structure',
            itemSelectText: '',
        });
    });
});

function updateName() {
        const nom = document.getElementById('NOM').value;
        const prenom = document.getElementById('PRENOM').value;
        const name = document.getElementById('name');
        name.value = `${nom} ${prenom}`;
    }

        </script>


