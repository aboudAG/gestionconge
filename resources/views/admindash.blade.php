<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #2c3e50;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #ecf0f1;
            display: block;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            color: white;
            background-color: #34495e;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: #ecf0f1;
        }
        .table th,
        .table td {
            padding: 1rem;
            vertical-align: top;
            border-top: 1px solid #bdc3c7;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #bdc3c7;
            background-color: #2c3e50;
            color: white;
        }
        .table tbody + tbody {
            border-top: 2px solid #bdc3c7;
        }
        .table-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(44, 62, 80, 0.1);
        }
        .table-bordered {
            border: 1px solid #bdc3c7;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #bdc3c7;
        }
        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }
        .search-column input {
            width: 100%;
            box-sizing: border-box;
            border: none;
            background-color: transparent;
            color: inherit;
            padding: 8px;
            transition: background-color 0.3s;
        }
        .search-column input::placeholder {
            color: #999;
        }
        .search-column {
            padding: 0;
        }
        .search-column input:focus {
            outline: none;
            background-color: #dfe6e9;
        }
        .modal-header {
            background-color: #2c3e50;
            color: white;
        }
        .modal-footer .btn {
            background-color: #2c3e50;
            color: white;
        }
        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
        }
        .btn-primary:hover {
            background-color: #34495e;
            border-color: #34495e;
        }
        .action-buttons {
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href="#dashboard">Tableau de Bord</a>
    <a href="#employees">Consulter Employés</a>
    <a href="#addEmployee" data-toggle="modal" data-target="#addEmployeeModal">Ajouter Employé</a>
    <a href="#structures">Consulter Structures</a>
    <a href="#addStructure" data-toggle="modal" data-target="#addStructureModal">Ajouter Structure</a>

</div>

<div class="content">
    <h2 id="dashboard">Tableau de Bord</h2>
    <p>Bienvenue sur le tableau de bord de l'administrateur. Utilisez le menu à gauche pour naviguer entre les différentes fonctionnalités.</p>

    <!-- Consulter Employés -->
    <div id="employees">
        <h3>Liste des Employés</h3>
        <table id="employeesTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Poste</th>
                    <th>Date d'Embauche</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <th class="search-column"><input type="text" placeholder="Rechercher Matricule"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Nom"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Prénom"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Email"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Poste"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Date d'Embauche"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($employes as $employee)
                <tr>
                    <td>{{ $employee->MATRICULE }}</td>
                    <td>{{ $employee->NOM }}</td>
                    <td>{{ $employee->PRENOM }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->POSTE }}</td>
                    <td>{{ $employee->DATE_EMBAUCHE }}</td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editEmployeeModal{{ $employee->MATRICULE }}">Modifier</button>
                        <form action="{{ route('employes.destroy', $employee->MATRICULE) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ajouter Employé Modal -->
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
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="username">
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
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="{{ $randomPassword }}" required autocomplete="new-password">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ $randomPassword }}" required autocomplete="new-password">
                        </div>
                        <div>
                            <label for="name" class="form-label" style="display: none;">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" style="display: none;">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($employes as $employee)
    <!-- Modifier Employé Modal -->
    <div class="modal fade" id="editEmployeeModal{{ $employee->MATRICULE }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel{{ $employee->MATRICULE }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel{{ $employee->MATRICULE }}">Modifier Employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employes.update', $employee->MATRICULE) }}" method="POST" class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="MATRICULE" class="form-label">Matricule</label>
                            <input type="text" class="form-control" id="MATRICULE" name="MATRICULE" value="{{ $employee->MATRICULE }}">
                        </div>
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM" value="{{ $employee->NOM }}">
                        </div>
                        <div class="col-md-6">
                            <label for="PRENOM" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="PRENOM" name="PRENOM" value="{{ $employee->PRENOM }}">
                        </div>
                        <div class="col-md-6">
                            <label for="POSTE" class="form-label">Poste</label>
                            <input type="text" class="form-control" id="POSTE" name="POSTE" value="{{ $employee->POSTE }}">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $employee->email }}" required autocomplete="username">
                        </div>
                        <div class="col-md-6">
                            <label for="DATE_EMBAUCHE" class="form-label">Date d'embauche</label>
                            <input type="date" class="form-control" id="DATE_EMBAUCHE" name="DATE_EMBAUCHE" value="{{ $employee->DATE_EMBAUCHE }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ROLE" class="form-label">Rôle</label>
                            <select class="form-select" id="ROLE_EDIT{{ $employee->MATRICULE }}" name="ROLE_ID">
                                @foreach($roles as $role)
                                <option value="{{ $role->ID }}" {{ $employee->ROLE_ID == $role->ID ? 'selected' : '' }}>{{ $role->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="STRUCTURE" class="form-label">Structure</label>
                            <select class="form-select" id="STRUCTURE_EDIT{{ $employee->MATRICULE }}" name="STRUCTURE_ID">
                                @foreach($structures as $structure)
                                <option value="{{ $structure->ID }}" {{ $employee->STRUCTURE_ID == $structure->ID ? 'selected' : '' }}>{{ $structure->NOM }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Consulter Structures -->
    <div id="structures">
        <h3>Liste des Structures</h3>
        <table id="structuresTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Parent ID</th>
                    <th>Actions</th>
                </tr>
                <tr>
                    <th class="search-column"><input type="text" placeholder="Rechercher ID"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Nom"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Code"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Type"></th>
                    <th class="search-column"><input type="text" placeholder="Rechercher Parent ID"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($structures as $structure)
                <tr>
                    <td>{{ $structure->ID }}</td>
                    <td>{{ $structure->NOM }}</td>
                    <td>{{ $structure->CODE }}</td>
                    <td>{{ $structure->TYPE }}</td>
                    <td>{{ $structure->PARENT_ID }}</td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editStructureModal{{ $structure->ID }}">Modifier</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ajouter Structure Modal -->
    <div class="modal fade" id="addStructureModal" tabindex="-1" aria-labelledby="addStructureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStructureModalLabel">Nouvelle Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('structures.store') }}" method="POST" class="row">
                        @csrf
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM">
                        </div>
                        <div class="col-md-6">
                            <label for="CODE" class="form-label">Code</label>
                            <input type="text" class="form-control" id="CODE" name="CODE">
                        </div>
                        <div class="col-md-6">
                            <label for="TYPE" class="form-label">Type</label>
                            <input type="text" class="form-control" id="TYPE" name="TYPE">
                        </div>
                        <div class="col-md-6">
                            <label for="PARENT_ID" class="form-label">ID du Parent</label>
                            <input type="text" class="form-control" id="PARENT_ID" name="PARENT_ID">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach($structures as $structure)
    <!-- Modifier Structure Modal -->
    <div class="modal fade" id="editStructureModal{{ $structure->ID }}" tabindex="-1" aria-labelledby="editStructureModalLabel{{ $structure->ID }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStructureModalLabel{{ $structure->ID }}">Modifier Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('structures.update', $structure->ID) }}" method="POST" class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="NOM" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="NOM" name="NOM" value="{{ $structure->NOM }}">
                        </div>
                        <div class="col-md-6">
                            <label for="CODE" class="form-label">Code</label>
                            <input type="text" class="form-control" id="CODE" name="CODE" value="{{ $structure->CODE }}">
                        </div>
                        <div class="col-md-6">
                            <label for="TYPE" class="form-label">Type</label>
                            <input type="text" class="form-control" id="TYPE" name="TYPE" value="{{ $structure->TYPE }}">
                        </div>
                        <div class="col-md-6">
                            <label for="PARENT_ID" class="form-label">ID du Parent</label>
                            <input type="text" class="form-control" id="PARENT_ID" name="PARENT_ID" value="{{ $structure->PARENT_ID }}">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialiser DataTables pour le tableau des employés avec recherche par colonne
        var employeesTable = $('#employeesTable').DataTable();
        $('#employeesTable thead tr:eq(1) th').each(function(i) {
            $('input', this).on('keyup change', function() {
                if (employeesTable.column(i).search() !== this.value) {
                    employeesTable.column(i).search(this.value).draw();
                }
            });
        });

        // Initialiser DataTables pour le tableau des structures avec recherche par colonne
        var structuresTable = $('#structuresTable').DataTable();
        $('#structuresTable thead tr:eq(1) th').each(function(i) {
            $('input', this).on('keyup change', function() {
                if (structuresTable.column(i).search() !== this.value) {
                    structuresTable.column(i).search(this.value).draw();
                }
            });
        });

        // Initialiser Choices.js pour les sélections de rôle et de structure
        new Choices('#ROLE');
        new Choices('#STRUCTURE');
        @foreach($employes as $employee)
        new Choices('#ROLE_EDIT{{ $employee->MATRICULE }}');
        new Choices('#STRUCTURE_EDIT{{ $employee->MATRICULE }}');
        @endforeach
    });

    function updateName() {
        const nom = document.getElementById('NOM').value;
        const prenom = document.getElementById('PRENOM').value;
        const name = document.getElementById('name');
        name.value = `${nom} ${prenom}`;
    }
</script>
</body>
</html>
