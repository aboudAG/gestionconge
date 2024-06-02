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
use App\Services\StructureService;



class DashboardController extends Controller
{
    protected $structureService;

    public function __construct(StructureService $structureService)
    {
        $this->structureService = $structureService;
    }

    public function index()
    {


        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }

        $employe = Employe::find($user->MATRICULE);

        if($employe->role->NOM == 'Admin'){
            return redirect()->route('admindash')->withErrors('Vous ne pouvez pas acceder a cette page.');
        }


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
    ->get();

$userDemandesConges->each(function ($demande) {
    $latestStatut = $demande->statuts()->orderBy('created_at', 'desc')->first();
    $demande->latestStatut = $latestStatut ? $latestStatut->STATUT : null;
    $demande->latestEtape = $latestStatut && $latestStatut->etape ? $latestStatut->etape->NOM : null;
});


        $structure = Structure::find($employe->STRUCTURE_ID);
        $role = $employe->role->NOM;



         switch ($role) {
             case 'Chef de service':
                 $demandes = $this->getDemandesForStructure($structure, 'Service');
                 $count = $demandes->count();
                 break;

             case 'Chef de departement':
                 $demandes = $this->getDemandesForStructureHierarchy($structure, 'Departement');
                 $count = $demandes->count();
                 break;

             case 'Directeur':
                 $demandes = $this->getDemandesForStructureHierarchy($structure, 'Direction');
                 $count = $demandes->count();
                 break;

             case 'RH':
                 $demandesrh = Demande::with(['employe.structure'])->get();
                 $demandes = $demandesrh->filter(function ($demande) {
                     $currentEtape = $demande->currentEtape();
                     return $currentEtape && $currentEtape->NOM === 'RH';
                 });
                 $count = $demandes->count();
                 break;

             case 'Employe':
                $count = null;
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


     private function getDemandesForStructure($structure, $etapeName)
     {
         $demandes = Demande::with(['employe.structure', 'statuts.etape'])
             ->whereHas('employe.structure', function ($query) use ($structure) {
                 $query->where('ID', $structure->ID);
             })
             ->get();

         return $demandes->filter(function ($demande) use ($etapeName) {
             $currentEtape = $demande->currentEtape();
             return $currentEtape && $currentEtape->NOM === $etapeName;
         });
     }

     private function getDemandesForStructureHierarchy($structure, $etapeName)
     {
         $childStructures = $this->structureService->getAllChildStructures($structure);
         $childStructureIds = $childStructures->pluck('ID');
         $childStructureIds->push($structure->ID); // Include the current structure itself
         $demandes = Demande::with(['employe.structure'])
             ->whereHas('employe.structure', function ($query) use ($childStructureIds) {
                 $query->whereIn('ID', $childStructureIds);
             })
             ->get();

         return $demandes->filter(function ($demande) use ($etapeName) {
             $currentEtape = $demande->currentEtape();
             return $currentEtape && $currentEtape->NOM === $etapeName;
         });
     }
}


