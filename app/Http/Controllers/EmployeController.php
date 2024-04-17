<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Structure;
use App\Models\Employe;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    public function index()
    {
        $employes = Employe::all();

        return view('employes.index', ['employes' => $employes ]);
    }

    public function create()
    {
        $structures = Structure::all();
        $roles = Role::all();
        return view('employes.create' , ['structures' => $structures , 'roles' => $roles] );
    }

    public function store(Request $request)
    {
        $request->validate([
            'MATRICULE' => 'required|unique:employes',
            'NOM' => 'required',
            'PRENOM' => 'required',
            'POSTE' => 'required',
            'STRUCTURE_ID' => 'required',
            'ROLE_ID' => 'required',
            'DATE_EMBAUCHE' => 'required|date',
        ]);

        // Crée un nouvel employé avec les données validées
        Employe::create($request->all());

        // Redirige l'utilisateur vers une page appropriée après la création
        return redirect()->route('employes.index')->with('success', 'Employé créé avec succès.');
    }

    public function edit($ID)
{
    // Trouve l'employé à éditer dans la base de données
    $employe = Employe::findOrFail($ID);

    // Récupère la liste des rôles et des structures pour les options de sélection
    $roles = Role::all();
    $structures = Structure::all();

    // Retourne la vue d'édition avec les données de l'employé et les options de sélection
    return view('employes.edit', compact('employe', 'roles', 'structures'));
}

public function update(Request $request, $ID)
{
    // Valider les données reçues du formulaire
    $validatedData = $request->validate([
        'MATRICULE' => 'required|string|max:255',
        'NOM' => 'required|string|max:255',
        'PRENOM' => 'required|string|max:255',
        'POSTE' => 'required|string|max:255',
        'STRUCTURE_ID' => 'required|integer',
        'ROLE_ID' => 'required|integer',
        'DATE_EMBAUCHE' => 'required|date',
    ]);

    // Met à jour l'employé dans la base de données
    Employe::where('MATRICULE', $ID)->update([
        'MATRICULE' => $request->MATRICULE,
        'NOM' => $request->NOM,
        'PRENOM' => $request->PRENOM,
        'POSTE' => $request->POSTE,
        'STRUCTURE_ID' => $request->STRUCTURE_ID,
        'ROLE_ID' => $request->ROLE_ID,
        'DATE_EMBAUCHE' => $request->DATE_EMBAUCHE,
    ]);

    // Redirige l'utilisateur vers une page appropriée après la mise à jour
    return redirect()->route('employes.index')->with('success', 'Employé mis à jour avec succès.');
}

public function destroy($ID)
{
    // Trouve l'employé à supprimer dans la base de données
    $employe = Employe::where('MATRICULE', $ID)->firstOrFail();

    // Supprime l'employé
    $employe->delete();

    // Redirige l'utilisateur vers une page appropriée après la suppression
    return redirect()->route('employes.index')->with('success', 'Employé supprimé avec succès.');
}

}
