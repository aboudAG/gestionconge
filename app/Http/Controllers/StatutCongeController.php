<?php

namespace App\Http\Controllers;

use App\Models\StatutConge;
use App\Models\Demande;
use App\Models\User;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatutCongeController extends Controller
{
    public function index()
{
    // Récupérer l'utilisateur connecté
    $userMatricule = Auth::user()->MATRICULE;

    $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }

        $employe = Employe::find($user->MATRICULE);

        if($employe->role->NOM == 'Admin'){
            return redirect()->route('admindash')->withErrors('Vous ne pouvez pas acceder a cette page.');
        }

    $demandes = Demande::where('EMPLOYE_ID', $userMatricule)
        ->with('statuts.etape')
        ->orderBy('DATE_DEBUT', 'desc')
        ->get();


            return view('statutsconges.index', compact('demandes'));
        }
    }

