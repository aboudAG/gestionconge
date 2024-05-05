<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">


    <title>Document</title>

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
    </style>
</head>
<body>
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

<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Liste des Employés</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            Ajouter un Employé
        </button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste</th>
                <th>Date d'embauche</th>
                <th>Structure</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employes as $employe)
            <tr>
                <td>{{ $employe->MATRICULE }}</td>
                <td>{{ $employe->NOM }}</td>
                <td>{{ $employe->PRENOM }}</td>
                <td>{{ $employe->POSTE }}</td>
                <td>{{ $employe->DATE_EMBAUCHE }}</td>
                <td>{{ $employe->structure->NOM }}</td>
                <td>{{ $employe->role->NOM }}</td>
                <td>
                    <a href="{{ route('employes.edit', $employe->MATRICULE) }}" class="btn btn-primary">Modifier</a>
                    <form action="{{ route('employes.destroy', $employe->MATRICULE) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <!-- Modale -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Nouvel Employé</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employes.store') }}" method="POST" class="row">
                        @csrf
                        @csrf
                        <div class="col-md-6">
                            <label for="MATRICULE" class="form-label">Matricule</label>
                            <input type="text" class="form-control" id="MATRICULE" name="MATRICULE">
                        </div>
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM">
                        </div>
                        <div class="col-md-6">
                            <label for="PRENOM" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="PRENOM" name="PRENOM">
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
                            <select class="form-select" id="STRUCTURE" name="STRUCTURE_ID">
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
                            <x-input-label for="name" :value="__('Name')" style="display : none;" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" style="display : none;"  />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('employes.create') }}" class="btn btn-primary">Agrandir</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>


</body>


<script>
    $(document).ready(function() {
    $('.table').DataTable({
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
         var element = document.getElementById('STRUCTURE');
         var choices = new Choices(element, {
         searchEnabled: true,
         shouldSort: false,
         placeholderValue: 'Sélectionnez une structure',
         itemSelectText: '',
         });
        });

        </script>

</html>
