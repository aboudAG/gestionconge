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
class DemandeCongeListeController extends Controller
{

public function show($id)
    {
        $demande = Demande::with(['employe.structure', 'statuts'])
                        ->findOrFail($id);
    
        return view('demandes.show', compact('demande'));
    }



    public function decide(Request $request, $id)
    {
        $demande = Demande::findOrFail($id);
        $currentEtape = $demande->ETAPE; // Store current etape before it's updated
        $decision = $request->input('decision');  // 'Accepter' or 'Refuser'
        $commentaire = $request->input('comment');
        $approvuerId = 1;//auth()->id();  // Assuming you're using Laravel's authentication to get the user ID
    
        // Determine the next stage based on the decision
        $nextEtape = $decision === 'Accepter' ? $this->determineNextEtape($currentEtape) : 'terminé';
        Log::info('Next etape determined:', ['nextEtape' => $nextEtape]);
    
        // Create a new record in STATUT_CONGE reflecting the current stage and decision
        StatutConge::create([
            'ETAPE' => $currentEtape,  // Use the stored current etape
            'STATUT' => $decision,
            'DEMANDE_CONGE_ID' => $id,
            'COMMENTAIRE' => $commentaire,
            'APPROUVEUR_ID' => $approvuerId,
            'DATE_DECISION' => now(),
        ]);
        

        
    
    

        Demande::where('ID', $id)
      ->update(['ETAPE' => $nextEtape]);
  


    
        return redirect()->route('demandes-conges.index')->with('success', 'Décision enregistrée avec succès.');
    }

private function determineNextEtape($currentEtape)
{
    $etapeProgression = [
        'service' => 'departement',
        'departement' => 'direction',
        'direction' => 'rh',
        'rh' => 'terminé'
    ];

    return $etapeProgression[$currentEtape] ?? 'terminé';  // Default to 'terminé' if no next step defined
}
}