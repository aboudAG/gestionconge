<?php
namespace App\Http\Controllers;

use App\Models\DemandeConge;
use Illuminate\Http\Request;
use App\Models\Structure;
use App\Models\Demande;
use App\Models\StatutConge;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employe;
use App\Models\Etape;
use App\Models\Exercice;
use App\Models\DroitConge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DemandeCongeDecisionController extends Controller
{

public function show($id)
    {
        $demande = Demande::with(['employe.structure', 'statuts'])
                        ->findOrFail($id);

        //SOLDE
        $soldeCongeRestant = DroitConge::where('EMPLOYE_ID', $demande->employe->MATRICULE)
                                          ->get();

        $exerciceData = Exercice::where('DEMANDE_CONGE_ID',$demande->ID)
        ->get();




       // dd($exerciceData);

        $userDemandesConges = Demande::with(['type'])
                        ->where('EMPLOYE_ID', $demande->employe->MATRICULE)
                        ->get();

                    $userDemandesConges->each(function ($demande) {
                        $latestStatut = $demande->statuts()->orderBy('created_at', 'desc')->first();
                        $demande->latestStatut = $latestStatut ? $latestStatut->STATUT : null;
                        $demande->latestEtape = $latestStatut && $latestStatut->etape ? $latestStatut->etape->NOM : null;
                    });



        $etapeRHId = Etape::where('nom', 'RH')->value('ID');
        $teamMembersOnLeave = Employe::where('STRUCTURE_ID', $demande->employe->STRUCTURE_ID)
        ->whereHas('demandes.statuts', function($query) use ($etapeRHId) {
            $query->where('ETAPE_ID', $etapeRHId)
                  ->where('STATUT', 'Accepter');
        })
        ->with(['demandes' => function($query) use ($etapeRHId) {
            $query->whereHas('statuts', function($subQuery) use ($etapeRHId) {
                $subQuery->where('ETAPE_ID', $etapeRHId)
                         ->where('STATUT', 'Accepter');
            });
        }])
        ->get();





            return view('decisiondemande.show', [
                'demande' => $demande,
                'teamMembersOnLeave' => $teamMembersOnLeave,
                'userDemandesConges' => $userDemandesConges,
                'soldeCongeRestant' => $soldeCongeRestant,
                'exerciceData' => $exerciceData,
                'justificatifPath' => 'public\justificatifs\\' . $demande->JUSTUFICATIF
            ]);
    }



    public function decide(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }
    
        $demande = Demande::findOrFail($id);
        $currentStatut = $demande->statuts()->where('STATUT', 'En Attente')->firstOrFail();
        $decision = $request->input('decision');
        $commentaire = $request->input('comment');
        $approvuerId = $user->MATRICULE;
    
        DB::transaction(function () use ($demande, $currentStatut, $decision, $commentaire, $approvuerId, $user) {
            // Update the current statut using Query Builder
            DB::table('statut_conge')
                ->where('id', $currentStatut->ID)
                ->update([
                    'STATUT' => $decision,
                    'COMMENTAIRE' => $commentaire,
                    'APPROUVEUR_ID' => $approvuerId,
                    'DATE_DECISION' => now(),
                ]);
    
            if ($decision === 'Accepter') {
                $nextEtapeNom = $demande->getNextEtape($currentStatut->etape->NOM);
                if ($nextEtapeNom) {
                    $nextEtape = Etape::where('NOM', $nextEtapeNom)->first();
                    if ($nextEtape) {
                        // Create a new statut conge record for the next etape
                        DB::table('statut_conge')->insert([
                            'ETAPE_ID' => $nextEtape->ID,
                            'STATUT' => 'En Attente',
                            'DEMANDE_CONGE_ID' => $demande->ID,
                            'COMMENTAIRE' => '',
                            'APPROUVEUR_ID' => null,
                            'DATE_DECISION' => now(),
                        ]);
                    }
                } else {
                    // Final approval step
                    $dateDebut = Carbon::parse($demande->DATE_DEBUT);
                    $dateFin = Carbon::parse($demande->DATE_FIN);
                    $nombreDeJours = $dateDebut->diffInDays($dateFin) + 1;
                    $exercices = Exercice::where('DEMANDE_CONGE_ID', $demande->ID)->orderBy('ID')->get();
                    $nombreExercices = $exercices->count();
                    if ($nombreExercices == 1) {
                        $exercice = $exercices->first();
                        $droitConge = $exercice->droitConge;
                        $JOURS_RESTANT = $droitConge->JOURS_RESTANT - $nombreDeJours;
                        DroitConge::where('ID', $droitConge->ID)->update([
                            'JOURS_RESTANT' => $JOURS_RESTANT,
                            'JOURS_PRIS' => $nombreDeJours,
                        ]);
                    } else {
                        $premierExercice = $exercices[0];
                        $secondExercice = $exercices[1];
    
                        // Récupérer les DroitConge associés
                        $premierDroit = $premierExercice->droitConge;
                        $secondDroit = $secondExercice->droitConge;
                        // $jours = $nombreDeJours;
                        $nombreDeJours -= $premierDroit->JOURS_RESTANT;
                        // $premierDroit->delete();
                        // dd($JOURS_RESTANT = $premierDroit->JOURS_RESTANT - $jours);
                        DroitConge::where('ID', $premierDroit->ID)->update([
                            'JOURS_RESTANT' => 0,
                            'JOURS_PRIS' => 30,
                        ]);
                        $JOURS_RESTANT = $secondDroit->JOURS_RESTANT - $nombreDeJours;
                        DroitConge::where('ID', $secondDroit->ID)->update([
                            'JOURS_RESTANT' => $JOURS_RESTANT,
                            'JOURS_PRIS' => $nombreDeJours,
                        ]);
                    }
    
                    // Check if the employee going on leave has a role that needs delegation
                    $employe = $demande->employe;
                    if (in_array($employe->role->NOM, ['Chef de service', 'Chef de departement', 'Directeur'])) {
                        DB::table('delegation_role')->insert([
                            'EMPLOYE_ID' => $demande->EMPLOYE_REMPLACEMENT_ID,
                            'DATE_DEBUT' => $demande->DATE_DEBUT,
                            'DATE_FIN' => $demande->DATE_FIN,
                            'EMPLOYE_DELEGUEUR_ID' => $employe->MATRICULE,
                            'ROLE_ID' => $employe->ROLE_ID,
                        ]);
                    }
                }
            }
        });
    
        return redirect()->route('listedemandes.index')->with('success', 'Décision enregistrée avec succès.');
    }
}
