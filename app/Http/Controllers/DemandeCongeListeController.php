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
use App\Models\DelegationRole;
use App\Services\StructureService;

class DemandeCongeListeController extends Controller
{
    protected $structureService;

    public function __construct(StructureService $structureService)
    {
        $this->structureService = $structureService;
    }

    public function index($delegated = false)
    {
        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer l'employé à partir de son matricule
        $employe = Employe::find($user->MATRICULE);

        // S'assurer que l'employé existe et a une structure associée
        if (!$employe || !$employe->STRUCTURE_ID) {
            return redirect()->route('login')->withErrors('Employé non trouvé ou structure non assignée.');
        }

        $structure = Structure::find($employe->STRUCTURE_ID);
        $role = $employe->role->NOM;

        // If viewing delegated demandes
        if ($delegated) {
            $delegationRole = DelegationRole::where('EMPLOYE_ID', $employe->MATRICULE)->first();
            if ($delegationRole) {
                $role = $delegationRole->role->NOM;
            } else {
                return redirect()->route('dashboard')->withErrors('Aucune délégation de rôle trouvée.');
            }
        }

        switch ($role) {
            case 'Chef de service':
                $demandes = $this->getDemandesForStructure($structure, 'Service');
                break;

            case 'Chef de departement':
                $demandes = $this->getDemandesForStructureHierarchy($structure, 'Departement');
                break;

            case 'Directeur':
                $demandes = $this->getDemandesForStructureHierarchy($structure, 'Direction');
                break;

            case 'RH':
                $demandesrh = Demande::with(['employe.structure'])->get();
                $demandes = $demandesrh->filter(function ($demande) {
                    $currentEtape = $demande->currentEtape();
                    return $currentEtape && $currentEtape->NOM === 'RH';
                });
                break;

            case 'Employe':
                return redirect()->route('demandes.create')->withErrors('Vous ne pouvez pas accéder à cette page.');
        }

        return view('listedemandes.index', [
            'demandes' => $demandes,
            'delegated' => $delegated,
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