<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\Etape;
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

    // S'assurer que l'employé existe et a une structure associée
    if (!$employe || !$employe->STRUCTURE_ID) {
        return redirect()->route('login')->withErrors('Employé non trouvé ou structure non assignée.');
    }

    // Récupérer les employés qui sont dans la même structure que l'employé connecté
    $employes = Employe::where('STRUCTURE_ID', $employe->STRUCTURE_ID)
                            ->where('MATRICULE', '!=', $user->MATRICULE)
                            ->get();

    // Récupérer les types de congé disponibles
    $types = Type::all(); // Assurez-vous que le modèle Type et la table sont correctement configurés

    // Passer les données à la vue
    return view('demandes.create', [
        'types' => $types,
        'employes' => $employes, // Liste des employés de la même structure que l'utilisateur connecté
        'droitConges' => $employe->droitConges // Passer les droits de congé associés à l'employé
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

              $etapesPermis = ['Service', 'Departement', 'Direction', 'RH']; // Utilisez les noms avec la première lettre en majuscule
              $structureTypeFormate = ucfirst(strtolower($structureType)); // Convertir en format avec la première lettre en majuscule

              if (!in_array($structureTypeFormate, $etapesPermis)) {
                  throw new Exception('Type de structure inconnu');
              }

              // Récupérer l'ID de l'étape
              $etape = Etape::where('nom', $structureTypeFormate)->first();
              if (!$etape) {
                  throw new Exception("Aucune étape trouvée pour le type de structure spécifié");
              }
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

                ]);

                // Créer une nouvelle entrée dans la table statut_conges
                $statutConge = StatutConge::create([
                    'STATUT_ID' => null,
                    'DEMANDE_CONGE_ID' => $demande->id,
                    'STATUT' => 'En Attente',
                    'APPROUVEUR_ID' => null,
                    'DATE_DECISION' => now(),
                    'ETAPE_ID' => $etape->ID, // Utiliser la date actuelle pour la date de décision
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
