<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\Employe;
use App\Models\Demande;
use App\Models\StatutConge;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Exception;


class DemandeController extends Controller
{
    public function create()
    {
           // Récupérer l'utilisateur connecté via l'authentification
    $user = Auth::user();

    // Assurez-vous que l'utilisateur est bien un employé et qu'il a un matricule associé
    if (!$user || !$user->MATRICULE) {
        return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
    }

    // Récupérer l'employé à partir de son matricule
    $employe = Employe::with('droitConges')->find($user->MATRICULE);
    // dd($employe = Employe::find($user->MATRICULE));
    // $droitConge = $employe->droitConges ?? null;

    // S'assurer que l'employé existe
    if (!$employe) {
        return redirect()->route('login')->withErrors('Employé non trouvé.');
    }

    // Récupérer les types de congé disponibles
    $employes = Employe::all();
    $types = Type::all(); // Assurez-vous que le modèle Type et la table sont correctement configurés

    // Passer les données à la vue
    return view('demandes.create', [
        'types' => $types,
        'employes' => $employes, // vous pouvez choisir de passer l'employé entier
        'droitConges' => $employe->droitConges // passer les droits de congé associés à l'employé
    ]);
    }



    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'TYPE_ID' => 'required', // Assurez-vous que le type de congé existe
            'TITRE' => 'required|string|max:50', // Titre de la demande
            'DATE_DEBUT' => 'required|date|before:DATE_FIN', // Date de début doit être avant ou égale à la date de fin
            'DATE_FIN' => 'required|date|after:DATE_DEBUT', // Date de fin doit être après ou égale à la date de début
            'EMPLOYE_REMPLACEMENT_ID' => 'nullable', // ID de l'employé de remplacement, nullable si aucun remplacement n'est prévu
        ]);

        // Récupérer l'employé connecté
        $user = Auth::user();

        // Vérifier si l'objet Employe a été récupéré avec succès
        if ($user) {

              $employe = Employe::find($user->MATRICULE);
              $structureType = $employe->structure->TYPE ?? null;

              $etape = match ($structureType) {
                  'SERVICE' => 'service',
                  'DZPARTEMENT' => 'departement',
                  'DIRECTION' => 'direction',
                  'RH' => 'rh',
                  default => throw new Exception('Type de structure inconnu'),
              };
            // Commencer une transaction de base de données
            DB::beginTransaction();

             try {
                // Créer une nouvelle demande avec l'ID de l'employé récupéré
                $demande = Demande::create([
                    'EMPLOYE_ID' => $user->MATRICULE, // Utiliser l'ID de l'utilisateur connecté
                    'TYPE_ID' => $request->input('TYPE_ID'),
                    'TITRE' => $request->input('TITRE'),
                    'DATE_DEBUT' => $request->input('DATE_DEBUT'),
                    'DATE_FIN' => $request->input('DATE_FIN'),
                    'EMPLOYE_REMPLACEMENT_ID' => $request->input('EMPLOYE_REMPLACEMENT_ID'),
                    'DATE_CREATION' => now(),
                    'ETAPE' => $etape ,
                ]);

                // Créer une nouvelle entrée dans la table statut_conges
                $statutConge = StatutConge::create([
                    'STATUT_ID' => null,
                    'ETAPE' => $etape, // Définir le statut initial comme "EN_ATTENTE"
                    'DEMANDE_CONGE_ID' => $demande->id,
                    'STATUT' => 'En Attente',
                    'APPROUVEUR_ID' => null,
                    'DATE_DECISION' => now(), // Utiliser la date actuelle pour la date de décision
                ]);

                // Valider la transaction de base de données
                DB::commit();

                return redirect()->route('statutsconges.index')->with('success', 'La demande de congé a été enregistrée avec succès.');
            } catch (Exception $e) {
                 // En cas d'erreur, annuler la transaction et rediriger avec un message d'erreur
                 DB::rollBack();
                 return redirect()->back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la création de la demande de congé. Veuillez réessayer.']);
             }
        } else {
            // Gérer le cas où l'employé n'est pas trouvé
            return redirect()->intended('login');
        }
    }

}
