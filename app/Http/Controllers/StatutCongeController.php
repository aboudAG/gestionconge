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
    $user = Auth::user();

    // Vérifier si l'utilisateur est connecté
    if ($user) {
        // Récupérer l'employé correspondant à l'utilisateur connecté
        $employe = $user->employe;

        // Vérifier si l'employé existe
        if ($employe) {
            // Récupérer toutes les demandes de congé de l'employé avec leurs statuts
            $demandes = $employe->demandes()->with('statuts')->get();

            return view('statutsconges.index', compact('demandes'));
        }
    }
}
}
