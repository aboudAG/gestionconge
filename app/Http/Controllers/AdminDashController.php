<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Structure;
use App\Models\Employe;
use Illuminate\Support\Facades\Auth;

class AdminDashController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->MATRICULE) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour accéder à cette page.');
        }
        // $employe = Employe::where('MATRICULE', $user->MATRICULE)->firstOrFail();
        // if($employe->role->NOM != 'Admin'){
        //     return redirect()->route('demandes.create')->withErrors('Vous ne pouvez pas acceder a cette page.');
        // }
        $employes = Employe::all();
        $structures = Structure::all();
        $roles = Role::all();


        return view('admindash', ['employes' => $employes,'structures' => $structures , 'roles' => $roles ]);
    }
}
