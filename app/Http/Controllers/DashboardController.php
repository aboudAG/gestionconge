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
use App\Models\Structure;
use App\Models\Role;
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


        $soldeAnnee = DroitConge::where('EMPLOYE_ID', $userMatricule)
    ->orderBy('ANNEE', 'desc')
    ->get();
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
        ->limit(3)
        ->get();

//  GET HISTROY WITH STATUT AND ETAPE
$userDemandesConges = Demande::with(['type'])
    ->where('EMPLOYE_ID', $userMatricule)
    ->limit(3)
    ->get();

$userDemandesConges->each(function ($demande) {
    $latestStatut = $demande->statuts()->orderBy('created_at', 'desc')->first();
    $demande->latestStatut = $latestStatut ? $latestStatut->STATUT : null;
    $demande->latestEtape = $latestStatut && $latestStatut->etape ? $latestStatut->etape->NOM : null;
});



//

$EMPLOYESTRUCTURE=$employe->STRUCTURE_ID;
$role = $employe->role->NOM;

$chefStructureId = $employe->STRUCTURE_ID;
$chefStructure = Structure::find($chefStructureId);


$directeurStructureId = $employe->STRUCTURE_ID; // ID de la structure dirigée par le directeur
$directeurStructure = Structure::find($directeurStructureId);




switch ($role) {
    case 'Chef de service':
        $demandesservice = Demande::with(['employe.structure', 'statuts.etape'])
        ->whereHas('employe', function ($query) use ($EMPLOYESTRUCTURE) {
            $query->whereHas('structure', function ($query) use ($EMPLOYESTRUCTURE) {
                $query->where('id', $EMPLOYESTRUCTURE);
            });
        })
        ->get();

    // Now, filter these demandes to check the current etape using the loaded data.
    $demandes = $demandesservice->filter(function ($demande) {
        $currentEtape = $demande->currentEtape();
        return $currentEtape && $currentEtape->NOM === 'Service';
    });
    $count = $demandes->count();

        break;




    case 'Chef de departement':
        if ($chefStructure && $chefStructure->CHEMIN) {
            // Retrieve all demandes within the structure hierarchy first
            $demandesdepartement = Demande::with(['employe.structure'])
                ->whereHas('employe', function ($query) use ($chefStructure) {
                    $query->whereHas('structure', function ($query) use ($chefStructure) {
                        $query->where('CHEMIN', 'LIKE', $chefStructure->CHEMIN . '%');
                    });
                })
                ->get();

            // Filter demandes based on the current etape using the loaded data
            $demandes = $demandesdepartement->filter(function ($demande) {
                $currentEtape = $demande->currentEtape();
                return $currentEtape && $currentEtape->NOM === 'Departement';
            });
            $count = $demandes->count();
        }

        break;




    case 'Directeur':
        if ($directeurStructure && $directeurStructure->CHEMIN) {
            // Fetch all demandes within the structure hierarchy
            $demandesdirecteur = Demande::with(['employe.structure'])
                ->whereHas('employe', function ($query) use ($directeurStructure) {
                    $query->whereHas('structure', function ($query) use ($directeurStructure) {
                        $query->where('CHEMIN', 'LIKE', $directeurStructure->CHEMIN . '%');
                    });
                })
                ->get();

            // Filter the demandes based on the current etape
            $demandes = $demandesdirecteur->filter(function ($demande) {
                $currentEtape = $demande->currentEtape();
                return $currentEtape && $currentEtape->NOM === 'Direction';
            });
            $count = $demandes->count();
        }
        break;



    case 'RH':
        $demandesrh = Demande::with(['employe.structure'])
        ->get();

    // Filter these demandes to check the current etape using the loaded data.
        $demandes = $demandesrh->filter(function ($demande) {
        $currentEtape = $demande->currentEtape();
        return $currentEtape && $currentEtape->NOM === 'RH';
        $count = $demandes->count();
    });
        break;
        case 'Employe' :

            $count = NULL ;
        }



        // Return the dashboard view and pass the data array
        return view('dashboard', [
            'employe'=>$employe,
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
            'soldeAnnee' => $soldeAnnee,
            'count' => $count
        ]);

    }
    }

