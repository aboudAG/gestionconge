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
    $demandes = Demande::where('EMPLOYE_ID', $userMatricule)
        ->with('statuts.etape')
        ->orderBy('DATE_DEBUT', 'desc')
        ->get();
      

            return view('statutsconges.index', compact('demandes'));
        }
    }

