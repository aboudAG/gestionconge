<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Affiche la liste des rôles
    public function index()
    {
        $roles = Role::all();

        return view('roles.index', ['roles' => $roles ]);
    }

    // Affiche le formulaire de création d'un nouveau rôle
    public function create()
    {
        return view('roles.create');
    }

    // Enregistre le nouveau rôle dans la base de données
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOM' => 'required|max:255',
            // Ajoutez d'autres règles de validation si nécessaire
        ]);

        $role = Role::create($validatedData);

        return redirect()->route('roles.index')->with('success', 'Rôle ajouté avec succès.');
    }

    public function edit($id)
{
    // Trouver le rôle par son ID
    $role = Role::findOrFail($id);

    // Retourner la vue d'édition avec le rôle à modifier
    return view('roles.edit', compact('role'));
}

public function update(Request $request, $id)
{

    $request->validate([
        'NOM' => 'required|string|max:255',
    ]);

    $role = Role::findOrFail($id);
    $role->update([
        'NOM' => $request->NOM,
    ]);

        // Redirection avec un message de succès
        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour avec succès.');

}

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
