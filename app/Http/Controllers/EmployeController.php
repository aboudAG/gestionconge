<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Structure;
use App\Models\Employe;
use App\Models\User;
use App\Models\DroitConge;
use App\Models\Demande;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\EmployeCreated;

class EmployeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }
        $employe = Employe::where('MATRICULE', $user->MATRICULE)->firstOrFail();
        if($employe->role->NOM == 'Employe'){
            return redirect()->route('demandes.create')->withErrors('Vous ne pouvez pas accéder à cette page.');
        }

        $employes = Employe::where('STRUCTURE_ID', $employe->STRUCTURE_ID)->get();
        $structures = Structure::all();
        $roles = Role::all();

        return view('employes.index', ['employes' => $employes, 'structures' => $structures, 'roles' => $roles]);
    }

    public function create()
    {
        $structures = Structure::all();
        $roles = Role::all();
        return view('employes.create', ['structures' => $structures, 'roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOM' => 'required',
            'PRENOM' => 'required',
            'POSTE' => 'required',
            'STRUCTURE_ID' => 'required',
            'ROLE_ID' => 'required',
            'DATE_EMBAUCHE' => 'required|date',
        ]);

        $structure = Structure::findOrFail($validatedData['STRUCTURE_ID']);
        $year = date('Y', strtotime($validatedData['DATE_EMBAUCHE']));
        $lastEmploye = Employe::where('MATRICULE', 'like', "{$year}-%")->orderBy('MATRICULE', 'desc')->first();
        
    // Extract the sequence number, ensure to handle the case correctly
    $sequence = 1; // Default sequence number
    if ($lastEmploye) {
        // Get the part after the dash
        $lastSequence = substr($lastEmploye->MATRICULE, strlen($year) + 1 );
        
        // Increment the sequence number
        $sequence = (int)$lastSequence + 1;
        
    }
    $sequence = str_pad($sequence, 5, '0', STR_PAD_LEFT);

    $matricule = "{$year}-{$sequence}";

        $employe = new Employe();
        $employe->MATRICULE = $matricule;
        $employe->NOM = $validatedData['NOM'];
        $employe->PRENOM = $validatedData['PRENOM'];
        $employe->POSTE = $validatedData['POSTE'];
        $employe->STRUCTURE_ID = $validatedData['STRUCTURE_ID'];
        $employe->ROLE_ID = $validatedData['ROLE_ID'];
        $employe->DATE_EMBAUCHE = $validatedData['DATE_EMBAUCHE'];
        $employe->save();

        $userValidatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                Password::defaults()
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'MATRICULE' => $matricule,
        ]);

        event(new Registered($user));

        return redirect()->route('admindash')->with('success', 'Employé créé avec succès.');
    }

    public function edit($ID)
    {
        $employe = Employe::findOrFail($ID);
        $roles = Role::all();
        $structures = Structure::all();
        return view('employes.edit', compact('employe', 'roles', 'structures'));
    }

    public function update(Request $request, $ID)
    {
        $validatedData = $request->validate([
            'NOM' => 'required|string|max:255',
            'PRENOM' => 'required|string|max:255',
            'POSTE' => 'required|string|max:255',
            'STRUCTURE_ID' => 'required|integer',
            'ROLE_ID' => 'required|integer',
            'DATE_EMBAUCHE' => 'required|date',
        ]);

        $employe = Employe::findOrFail($ID);
        $employe->update([
            'NOM' => $request->NOM,
            'PRENOM' => $request->PRENOM,
            'POSTE' => $request->POSTE,
            'STRUCTURE_ID' => $request->STRUCTURE_ID,
            'ROLE_ID' => $request->ROLE_ID,
            'DATE_EMBAUCHE' => $request->DATE_EMBAUCHE,
        ]);

        return redirect()->route('employes.index')->with('success', 'Employé mis à jour avec succès.');
    }

    public function destroy($ID)
    {
        DB::transaction(function () use ($ID) {
            $employe = Employe::findOrFail($ID);
            $user = User::where('MATRICULE', $employe->MATRICULE)->first();
            $droitConge = DroitConge::where('EMPLOYE_ID', $employe->MATRICULE)->first();
            $demandeconge = Demande::where('EMPLOYE_ID', $employe->MATRICULE)->first();
            if($demandeconge){
                $demandeconge->delete();
            }
            if($user){
                $user->delete();
            }

            if ($droitConge) {
                $droitConge->delete();
            }

            $employe->delete();
        });

        return redirect()->route('employes.index')->with('success', 'Employé et utilisateur associé supprimés avec succès.');
    }
}