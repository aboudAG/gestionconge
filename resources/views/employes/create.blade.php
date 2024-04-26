<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f0f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 800px;
        }

        .card {
            background-color: #2a3f54;
            border: none;
            border-radius: 8px;
        }

        .card .card-body {
            padding: 30px;
        }

        .card-title {
            font-weight: 300;
            margin-bottom: 2rem;
            color: #fff;
        }

        .btn {
            background-color: #4a90e2;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
        }

        .btn:hover {
            background-color: #357abd;
        }

        .form-control {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: #2a3f54;
        }

        .form-label {
            margin-bottom: .5rem;
            font-size: 0.9rem;
            color: #c7d5e0;
        }

        .form-select {
            display: block;
            width: 100%;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #2a3f54;
            background-color: #fff;
            background-repeat: no-repeat;
            border: 1px solid #ccc;
            border-radius: 4px;
            appearance: none;
        }
        .position-relative {
  position: relative;
}

.field-icon {
  position: absolute;
  right: 10px;
  top: 72%;
  transform: translateY(-50%);
  cursor: pointer;
  z-index: 2;
}

    </style>
</head>
<body class="bg-dark text-white">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card bg-secondary">
                    <div class="card-body">
                        <form action="{{ route('employes.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label for="MATRICULE" class="form-label">Matricule</label>
                                <input type="text" class="form-control" id="MATRICULE" name="MATRICULE">
                            </div>
                            <div class="col-md-6">
                                <label for="NOM" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="NOM" name="NOM">
                            </div>
                             {{-- <!-- Name -->
                             <div>
                               <x-input-label for="name" :value="__('Name')" />
                               <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                               <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div> --}}
                               <div class="col-md-6">
                                <label for="PRENOM" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="PRENOM" name="PRENOM">
                            </div>
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"  />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
                            <!-- Password -->

                            @php
                                $randomPassword = Illuminate\Support\Str::random(10); // Génération d'un mot de passe
                            @endphp
                            <div class="col-md-6 position-relative">
                                <x-input-label for="password" :value="__('Password')" />

                                <x-text-input id="password" class="form-control"
                                                type="password"
                                                name="password"
                                                value="{{ $randomPassword }}"
                                                required autocomplete="new-password" />
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 position-relative">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                <x-text-input id="password_confirmation" class="form-control"
                                                type="password"
                                                name="password_confirmation"
                                                value="{{ $randomPassword }}"
                                                required autocomplete="new-password" />
                                <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Ajouter l'employé</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            </script>

</body>
</html>
