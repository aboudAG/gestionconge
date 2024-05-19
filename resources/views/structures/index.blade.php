<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

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
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Structures') }}
    </h2>
</x-slot>

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
                        <h3 class="text-lg font-semibold mb-4">Liste des Structures</h3>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStructureModal" style="margin-bottom: 10px; background-color : black;  ">
                    Ajouter une structure
                </button>
                    </div>

                </div>
                <table class="min-w-full divide-y divide-gray-200" id="table">
                    <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($structures as $structure)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $structure->ID }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $structure->CODE }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $structure->NOM }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $structure->TYPE }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $structure->PARENT_ID }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('structures.edit', $structure->ID) }}" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateStructureModal{{$structure->ID}}"  >Modifier</a>
                    {{-- Bouton ou lien pour supprimer une structure --}}
                    <form action="{{ route('structures.destroy', $structure->ID) }}" method="POST" style="display: inline;">
                        @csrf
                      @method('DELETE')
                     <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette structure ?');" class="btn btn-danger">Supprimer</button>
                    </form>

                </td>
            </tr>
            <div class="modal fade" id="updateStructureModal{{$structure->ID}}" tabindex="-1" aria-labelledby="updatemodalLabel{{$structure->ID}}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="updatemodalLabel{{$structure->ID}}">Modifier structure</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="POST" action="{{ route('structures.update', $structure->ID) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                          <label for="CODE" class="form-label">Code</label>
                          <input type="text" class="form-control" id="CODE" name="CODE" value="{{$structure->CODE}}" required>
                        </div>
                        <div class="mb-3">
                          <label for="NOM" class="form-label">Nom</label>
                          <input type="text" class="form-control" id="NOM" name="NOM" value="{{$structure->NOM}}" required>
                        </div>
                        <div class="mb-3">
                          <label for="TYPE" class="form-label">Type</label>
                          <input type="text" class="form-control" id="TYPE" name="TYPE" value="{{$structure->TYPE}}" required>
                        </div>
                        <div class="mb-3">
                          <label for="PARENT_ID" class="form-label">Structure Parente (Optionnel)</label>
                          <input type="number" class="form-control" id="PARENT_ID" name="PARENT_ID" value="{{$structure->PARENT_ID}}">
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: black;">Enregistrer</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
        </tbody>

    </table>
</div>
</div>
</div>
</div>
{{-- modal --}}
 <!-- Modal -->
 <div class="modal fade" id="createStructureModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Créer une nouvelle structure</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('structures.store') }}">
            @csrf
            <div class="mb-3">
              <label for="CODE" class="form-label">Code</label>
              <input type="text" class="form-control" id="CODE" name="CODE" required>
            </div>
            <div class="mb-3">
              <label for="NOM" class="form-label">Nom</label>
              <input type="text" class="form-control" id="NOM" name="NOM" required>
            </div>
            <div class="mb-3">
              <label for="TYPE" class="form-label">Type</label>
              <input type="text" class="form-control" id="TYPE" name="TYPE" required>
            </div>
            <div class="mb-3">
              <label for="PARENT_ID" class="form-label">Structure Parente (Optionnel)</label>
              <input type="number" class="form-control" id="PARENT_ID" name="PARENT_ID" value="{{$structure->PARENT_ID}}">
            </div>
            <button type="submit" class="btn btn-primary" style="background-color: black;">Enregistrer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- <div class="modal fade" id="updateStructureModal" tabindex="-1" aria-labelledby="updatemodalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatemodalLabel">Créer une nouvelle structure</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('structures.store') }}">
            @csrf
            <div class="mb-3">
              <label for="CODE" class="form-label">Code</label>
              <input type="text" class="form-control" id="CODE" name="CODE" value="{{$structure->CODE}}" required>
            </div>
            <div class="mb-3">
              <label for="NOM" class="form-label">Nom</label>
              <input type="text" class="form-control" id="NOM" name="NOM" value="{{$structure->NOM}}" required>
            </div>
            <div class="mb-3">
              <label for="TYPE" class="form-label">Type</label>
              <input type="text" class="form-control" id="TYPE" name="TYPE" value="{{$structure->TYPE}}" required>
            </div>
            <div class="mb-3">
              <label for="PARENT_ID" class="form-label">Structure Parente (Optionnel)</label>
              <input type="number" class="form-control" id="PARENT_ID" name="PARENT_ID">
            </div>
            <button type="submit" class="btn btn-primary" style="background-color: black;">Enregistrer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
</script>


