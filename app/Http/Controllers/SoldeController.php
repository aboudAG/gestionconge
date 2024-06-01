<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solde;

class SoldeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'EMPLOYE_ID' => 'required|exists:employes,MATRICULE',
            'ANNEE' => [
                'required',
                'integer',
                'min:2000',
                'max:' . date('Y'),
                function ($attribute, $value, $fail) use ($request) {
                    // Vérifier si l'année n'existe pas déjà pour l'employé donné
                    $exists = \DB::table('droit_au_conges')
                        ->where('EMPLOYE_ID', $request->EMPLOYE_ID)
                        ->where('ANNEE', $value)
                        ->exists();

                    if ($exists) {
                        $fail('L\'année sélectionnée existe déjà pour cet employé.');
                    }
                },
            ],
            'JOURS_PRIS' => 'required|integer|min:0|max:30',
            'JOURS_RESTANT' => 'required|integer|min:0|max:30',
        ], [
            'EMPLOYE_ID.required' => 'L\'identifiant de l\'employé est requis.',
            'EMPLOYE_ID.exists' => 'L\'employé sélectionné n\'existe pas.',
            'ANNEE.required' => 'L\'année est requise.',
            'ANNEE.integer' => 'L\'année doit être un nombre entier.',
            'ANNEE.min' => 'L\'année doit être au moins 2000.',
            'ANNEE.max' => 'L\'année ne peut pas dépasser l\'année en cours.',
            'ANNEE.exists' => 'L\'année sélectionnée existe déjà pour cet employé.',
            'JOURS_PRIS.required' => 'Le nombre de jours pris est requis.',
            'JOURS_PRIS.integer' => 'Le nombre de jours pris doit être un nombre entier.',
            'JOURS_PRIS.min' => 'Le nombre de jours pris doit être au moins 0.',
            'JOURS_PRIS.max' => 'Le nombre de jours pris ne peut pas dépasser 30.',
            'JOURS_RESTANT.required' => 'Le nombre de jours restants est requis.',
            'JOURS_RESTANT.integer' => 'Le nombre de jours restants doit être un nombre entier.',
            'JOURS_RESTANT.min' => 'Le nombre de jours restants doit être au moins 0.',
            'JOURS_RESTANT.max' => 'Le nombre de jours restants ne peut pas dépasser 30.',
        ]);

        if ($request->JOURS_PRIS + $request->JOURS_RESTANT !== 30) {
            return back()->withErrors(['JOURS_PRIS' => 'La somme des jours pris et des jours restants doit être égale à 30.'])->withInput();
        }
        // Création d'une nouvelle entrée dans la table 'droit_au_conges'
        Solde::create([
            'EMPLOYE_ID' => $request->EMPLOYE_ID,
            'ANNEE' => $request->ANNEE,
            'JOURS_PRIS' => $request->JOURS_PRIS,
            'JOURS_RESTANT' => $request->JOURS_RESTANT,
        ]);

        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Le solde de congé a été ajouté avec succès.');
    }

    public function update(Request $request, $id)
{
    // Validation des données du formulaire
    $request->validate([
        'EMPLOYE_ID' => 'required',
        'ANNEE' => 'required|integer|min:2000|max:' . date('Y'),
        'JOURS_PRIS' => 'required|integer|min:0',
        'JOURS_RESTANT' => 'required|integer|min:0',
    ]);

    // Trouver et mettre à jour l'entrée existante
    $solde = Solde::findOrFail($id);
    Solde::where('ID',$id)->update([
        'EMPLOYE_ID' => $request->EMPLOYE_ID,
        'ANNEE' => $request->ANNEE,
        'JOURS_PRIS' => $request->JOURS_PRIS,
        'JOURS_RESTANT' => $request->JOURS_RESTANT,
    ]);

    // Redirection avec message de succès
    return redirect()->back()->with('success', 'Le solde de congé a été mis à jour avec succès.');
}

}
