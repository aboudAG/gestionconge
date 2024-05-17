

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
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles') }}
            </h2>
        </x-slot>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
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
                        <h3 class="text-lg font-semibold mb-4">Liste des Roles</h3>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200" id="table">
                        <thead class="bg-gray-50">

                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td class = " px-6 py-4 whitespace-nowrap">{{ $role->ID }} </td>
                        <td class = " px-6 py-4 whitespace-nowrap">{{ $role->NOM }}</td>
                        {{-- <td class = " px-6 py-4 whitespace-nowrap">
                            {{-- Ici, ajoutez les liens pour modifier ou supprimer les rôles
                            <a href="{{ route('roles.edit', $role->ID) }}" class="btn btn-primary">Éditer</a>
                            <a href="{{url('roles/'.$role->ID.'/delete')}}">supprimer</a> </td> --}}
                    </tr>
                    @endforeach
                </tbody>
        </table>

        </div>
    </x-app-layout>

