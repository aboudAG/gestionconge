<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Structure;
use App\Models\Employe;
use App\Models\User;
use App\Models\DroitConge;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



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
        $validatedData = $request->validate([
            'MATRICULE' => 'required|unique:employes,MATRICULE',
            'NOM' => 'required',
            'PRENOM' => 'required',
            'POSTE' => 'required',
            'STRUCTURE_ID' => 'required',
            'ROLE_ID' => 'required',
            'DATE_EMBAUCHE' => 'required|date',
        ]);
        // Crée un nouvel employé avec les données validées
        $employe = new Employe();
        $employe->MATRICULE = $validatedData['MATRICULE'];
        $employe->NOM = $validatedData['NOM'];
        $employe->PRENOM = $validatedData['PRENOM'];
        $employe->POSTE = $validatedData['POSTE'];
        $employe->STRUCTURE_ID = $validatedData['STRUCTURE_ID'];
        $employe->ROLE_ID = $validatedData['ROLE_ID'];
        $employe->DATE_EMBAUCHE = $validatedData['DATE_EMBAUCHE'];
        $employe->save();

        $request->validate([
            'MATRICULE' => 'required',
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                Password::defaults() // This applies the default password rules
            ],
        ]);



         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'MATRICULE' => $request->MATRICULE,
         ]);

        event(new Registered($user));

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

        'NOM' => 'required|string|max:255',
        'PRENOM' => 'required|string|max:255',
        'POSTE' => 'required|string|max:255',
        'STRUCTURE_ID' => 'required|integer',
        'ROLE_ID' => 'required|integer',
        'DATE_EMBAUCHE' => 'required|date',
    ]);

    // Met à jour l'employé dans la base de données
    Employe::where('ID', $ID)->update([
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
    DB::transaction(function () use ($ID) {
        // Trouve l'employé et l'utilisateur associé dans la base de données
        $employe = Employe::findOrFail($ID);
        $user = User::where('MATRICULE', $employe->MATRICULE)->firstOrFail();
        $droitConge = DroitConge::where('EMPLOYE_ID', $employe->MATRICULE)->firstOrFail();
        // Supprime l'utilisateur associé
        $user->delete();
        $droitConge->delete();

        // Supprime ensuite l'employé
        $employe->delete();
    });

    // Redirige l'utilisateur vers une page appropriée après la suppression
    return redirect()->route('employes.index')->with('success', 'Employé et utilisateur associé supprimés avec succès.');
}


}
