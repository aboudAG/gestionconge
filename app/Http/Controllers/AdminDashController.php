<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Structure;
use App\Models\Employe;
use App\Models\Solde;
use App\Models\JourFerie;
use Illuminate\Support\Facades\Auth;

class AdminDashController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }
        $employe = Employe::where('MATRICULE', $user->MATRICULE)->firstOrFail();
        // if($employe->role->NOM != 'Admin'){
        //     return redirect()->route('dashboard')->withErrors('Vous ne pouvez pas acceder a cette page.');
        // }
        $employes = Employe::all();
        $structures = Structure::all();
        $roles = Role::all();
        $soldes = Solde::all();
        $jours_feries = JourFerie::all(); // Include holidays data


        return view('admindash', ['employes' => $employes,'structures' => $structures , 'roles' => $roles,'soldes' => $soldes ]);
    }
}
