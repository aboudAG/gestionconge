<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\Structure;
use App\Models\Role;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employes = Employe::all();
        return view('employes.index', compact('employes'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
       // Récupère toutes les structures et tous les rôles pour les listes déroulantes
       $structures = Structure::all();
       $roles = Role::all();

       // Retourne la vue 'create' en passant les structures et les rôles nécessaires pour les listes déroulantes
       return view('employes.create', [
           'structures' => $structures,
           'roles' => $roles
       ]);
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Valide les entrées. Adaptez les règles à vos besoins.
            'matricule' => 'required|string|unique:employes,matricule',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'poste' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employes,email',
            'date_embauche' => 'required|date',
            'structure_id' => 'required|exists:structures,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employe = new Employe($validatedData);

        $employe->password = bcrypt($request->password);
        $employe = Employe::create($validatedData); // Assurez-vous de hasher le mot de passe avant de le stocker.
        $employe->save(); // Sauvegarde l'employé dans la base de données.
        
        return redirect()->route('employes.index')->with('success', 'Employé créé avec succès.'); // Redirige vers la liste des employés avec un message de succès.
    }
    /**
     * Display the specified resource.
     */
    public function show(Employe $employe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employe $employe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employe $employe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employe $employe)
    {
        //
    }
}
