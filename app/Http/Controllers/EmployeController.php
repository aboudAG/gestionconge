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
        dd($validatedData = $request->validate([
            // Valide les entrées. Adaptez les règles à vos besoins.
            'MATRICULE' => 'required|string|unique:employes,matricule',
            'NOM' => 'required|string|max:255',
            'PRENOM' => 'required|string|max:255',
            'POSTE' => 'required|string|max:255',
            'DATE_EMBAUCHE' => 'required|date',
            'STRUCTURE_ID' => 'required|exists:structures,id',
            'ROLE_ID' => 'required|exists:roles,id',

        ]));

        dd($employe = Employe::create($validatedData));

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
