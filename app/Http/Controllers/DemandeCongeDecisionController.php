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
use Illuminate\Support\Facades\DB;
class DemandeCongeDecisionController extends Controller
{

public function show($id)
    {
        $demande = Demande::with(['employe.structure', 'statuts'])
                        ->findOrFail($id);
    
        return view('decisiondemande.show', compact('demande'));
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

        DB::transaction(function () use ($demande, $currentStatut, $decision, $commentaire, $approvuerId) {
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
                            'APPROUVEUR_ID' => $approvuerId,
                            'DATE_DECISION' => now(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('listedemandes.index')->with('success', 'Décision enregistrée avec succès.');
    }
}
