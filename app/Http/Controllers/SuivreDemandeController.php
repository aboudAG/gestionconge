<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\StatutConge;
use Illuminate\Support\Facades\Auth;

class SuivreDemandeController extends Controller
{
    public function track()
    {
        $userMatricule = Auth::user()->MATRICULE;

        // Récupérer la demande de congé en cours
        $currentLeaveRequest = Demande::where('EMPLOYE_ID', $userMatricule)
            ->whereHas('statuts', function($query) {
                $query->where('STATUT', 'En Attente');
            })
            ->with(['statuts.etape', 'statuts.approbateur'])
            ->first();

        if (!$currentLeaveRequest) {
            return view('suivredemande', ['message' => 'VOUS N\'AVEZ PAS DE DEMANDE EN COURS']);
        }

        // Filtrer les étapes en attente et terminées
        $pendingStages = $currentLeaveRequest->statuts->filter(function($statut) {
            return $statut->STATUT == 'En Attente';
        });

        $completedStages = $currentLeaveRequest->statuts->filter(function($statut) {
            return $statut->STATUT == 'Accepter';
        });

        return view('suivredemande', [
            'currentLeaveRequest' => $currentLeaveRequest,
            'pendingStages' => $pendingStages,
            'completedStages' => $completedStages,
        ]);
    }
}