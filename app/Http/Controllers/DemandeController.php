<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\Employe;
use App\Models\Demande;
use App\Providers\RouteServiceProvider;


class DemandeController extends Controller
{
    public function create()
    {
        $types = Type::all();
        $employes = Employe::all();
        return view('demandes.create', ['types' => $types, 'employes' => $employes]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'TYPE_ID' => 'required|', // Assurez-vous que le type de congé existe
            'TITRE' => 'required|string|max:255', // Titre de la demande
            'DATE_DEBUT' => 'required|date|before_or_equal:DATE_FIN', // Date de début doit être avant ou égale à la date de fin
            'DATE_FIN' => 'required|date|after_or_equal:DATE_DEBUT', // Date de fin doit être après ou égale à la date de début
            'EMPLOYE_REMPLACEMENT_ID' => 'nullable', // ID de l'employé de remplacement, nullable si aucun remplacement n'est prévu
        ]);
        $user = Auth::user();
        // Récupérer l'employé connecté


    // Vérifiez si l'objet Employe a été récupéré avec succès
    if ($user) {
        // Créer une nouvelle demande avec l'ID de l'employé récupéré
        $demande = Demande::create([
            'EMPLOYE_ID' => $user->MATRICULE, // Utilisez l'ID de l'utilisateur connecté
            'TYPE_ID' => $request->input('TYPE_ID'),
            'TITRE' => $request->input('TITRE'),
            'DATE_DEBUT' => $request->input('DATE_DEBUT'),
            'DATE_FIN' => $request->input('DATE_FIN'),
            'EMPLOYE_REMPLACEMENT_ID' => $request->input('EMPLOYE_REMPLACEMENT_ID'),
            'DATE_CREATION' => now(),
        ]);

        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'La demande de congé a été enregistrée avec succès.');
    } else {
        // Gérer le cas où l'employé n'est pas trouvé
        throw new Exception('Employé non trouvé pour l\'utilisateur connecté.');
    }

    // ... Vous pouvez ajouter d'autres méthodes si nécessaire ...
}
}
