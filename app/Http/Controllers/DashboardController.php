<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employe;
use App\Models\DroitConge; 
use App\Models\Demande;
use App\Models\Etape;
use App\Models\JourFerie;
use App\Models\StatutConge;
use Carbon\Carbon;



class DashboardController extends Controller
{


    public function index()
    {


        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }
        $employe = Employe::find($user->MATRICULE);
        $holidays = JourFerie::all();

        //SOLDE RESTANT ANNEE COURANTE:
        $currentYear = now()->year;
        $userMatricule = Auth::user()->MATRICULE;
        $soldeCongeRestant = DroitConge::where('EMPLOYE_ID', $userMatricule)
                                          ->where('ANNEE', $currentYear)
                                          ->value('JOURS_RESTANT');
        //SOLDE RESTANT GLOBALE:
        $totalSoldeCongeRestant = DroitConge::where('EMPLOYE_ID', $userMatricule)
                                              ->sum('JOURS_RESTANT');

        //jours pris cette année
        $joursCongePrisCetteAnnee = DroitConge::where('EMPLOYE_ID', $userMatricule)
                                                ->where('ANNEE', $currentYear)
                                                ->value('JOURS_PRIS');
        // nombre totale de demande de congé:
        $nombreDemandesCongeTotal = Demande::where('EMPLOYE_ID', $userMatricule)
        ->count();


        $soldeAnnee = DroitConge::where('EMPLOYE_ID', $user->MATRICULE)->get();
        //nombre totale de demande de congé acecpter:
        $etapeRHId = Etape::where('nom', 'RH')->value('ID');

        // Count the number of accepted leave requests
        $nombreDemandesCongeAccepte = Demande::where('EMPLOYE_ID', $userMatricule)
            ->whereHas('statuts', function ($query) use ($etapeRHId) {
                $query->where('ETAPE_ID', $etapeRHId)
                      ->where('STATUT', 'Accepter');
            })
            ->count();



        //count the number of denied leave requests:
        $nombreDemandesCongeRefuse = Demande::where('EMPLOYE_ID', $userMatricule)
        ->whereHas('statuts', function ($query) {
            $query->where('STATUT', 'Refuser');
        })
        ->count();
        //count the number of pending leave requests:
        $nombreDemandesCongeEnAttente = Demande::where('EMPLOYE_ID', $userMatricule)
        ->whereHas('statuts', function ($query) {
            $query->where('STATUT', 'En attente');
        })
        ->count();




        //GET TEAM LEAVE OR PLANNED LEAVE:
        $teamMembersOnLeave = Employe::where('STRUCTURE_ID', $employe->STRUCTURE_ID)
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

//  GET HISTROY WITH STATUT AND ETAPE
        $userDemandesConges = Demande::with(['statuts' => function($query) {
            // Fetch the latest status
            $query->latest()->take(1);
        }, 'statuts.etape']) // Eager load Etape related to the latest status
        ->where('EMPLOYE_ID', $userMatricule)
        ->get();

        // Additional processing to filter or manipulate data if necessary
        $userDemandesConges->each(function ($demande) {
        $latestStatut = $demande->statuts->first(); // Assuming 'statuts' are ordered latest first
        if ($latestStatut && $latestStatut->STATUT == 'En Attente') {
            $demande->latestEtape = $latestStatut->etape;
        } else {
            $demande->latestEtape = null;
        }
        });
        


        
        // Assume you want to pass some data to the dashboard view
     

        // Return the dashboard view and pass the data array
        return view('dashboard', [
            'soldeCongeRestant' => $soldeCongeRestant,
            'totalSoldeCongeRestant' => $totalSoldeCongeRestant,
            'joursCongePrisCetteAnnee' => $joursCongePrisCetteAnnee,
            'nombreDemandesCongeTotal' => $nombreDemandesCongeTotal,
            'nombreDemandesCongeAccepte' => $nombreDemandesCongeAccepte,
            'nombreDemandesCongeRefuse' => $nombreDemandesCongeRefuse,
            'nombreDemandesCongeEnAttente' => $nombreDemandesCongeEnAttente,
            'userDemandesConges' => $userDemandesConges,
            'teamMembersOnLeave' =>  $teamMembersOnLeave,
            'holidays' => $holidays,
            'soldeAnnee' => $soldeAnnee
        ]);
    
    }
}