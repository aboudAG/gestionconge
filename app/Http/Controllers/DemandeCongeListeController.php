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


    public function index()
    {



/*
         = auth()->user()->role;  // Assumons que vous avez un moyen de déterminer le rôle de l'utilisateur

    $demandes = [];


*/
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
                }
                break;



            case 'RH':
                $demandesrh = Demande::with(['employe.structure'])
                ->get();

            // Filter these demandes to check the current etape using the loaded data.
                $demandes = $demandesrh->filter(function ($demande) {
                $currentEtape = $demande->currentEtape();
                return $currentEtape && $currentEtape->NOM === 'RH';
            });
                break;



        case 'Employe' :

            return redirect()->route('demandes.create')->withErrors('Vous ne pouvez pas acceder a cette page. ');

        }




        return view('listedemandes.index', [
    'demandes' => $demandes,
]);
    }

}







