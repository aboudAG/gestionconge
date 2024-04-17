<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\Employe;



class DemandeController extends Controller
{

    public function create()
    {
        $types = Type::all();
        $employes = Employe::all();
        return view('demandes.create' , ['types' => $types , 'employes' => $employes] );
    }


   public function store(Request $request): RedirectResponse
   {
       $request->validate([
           // Valider les champs de la demande
           // ...
       ]);

       // Récupérer l'ID de l'employé à partir du matricule de l'utilisateur connecté
       $employe = User::where('MATRICULE', Auth::user()->MATRICULE)->first();

       // Créer une nouvelle demande avec l'ID de l'employé récupéré
       $demande = Demande::create([
           'EMPLOYE_ID' => $employe->ID, // ID de l'employé
           'TYPE_ID' => $request->input('TYPE_ID'),
           'TITRE' => $request->input('TITRE'),
           'DATE_DEBUT' => $request->input('DATE_DEBUT'),
           'DATE_FIN' => $request->input('DATE_FIN'),
           'EMPLOYE_REMPLACEMENT_ID' => $request->input('EMPLOYE_REMPLACEMENT_ID'),
           'DATE_CREATION' => now(),

       ]);

    //    // Envoyer la demande au chef hiérarchique de l'utilisateur
    //    $chefHierarchique = $this->getChefHierarchique(Auth::user());

    //    // Ici, tu enverras la demande au chef hiérarchique via un e-mail, une notification, etc.
    //    // Exemple :
    //    // $chefHierarchique->sendDemandeNotification($demande);

    //    // Rediriger l'utilisateur vers la page de tableau de bord après la création de la demande
    //    return redirect()->route('dashboard')->with('success', 'Demande de congé créée avec succès.');
   }
}
