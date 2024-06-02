<?php

namespace App\Http\Controllers;

use App\Models\JourFerie;
use Illuminate\Http\Request;

class JourFerieController extends Controller
{
    public function index()
    {
        $jours_feries = JourFerie::all();
        return view('admindash', ['jours_feries' => $jours_feries]);
    }

    public function store(Request $request)
    {
        $validatedData= $request->validate([
            'JOUR_DEBUT' => 'required|date',
            'JOUR_FIN' => 'nullable|date',
            'DESIGNATION' => 'required|string|max:255',
        ]);

        
        $jourferie = new JourFerie();
        $jourferie->DESIGNATION = $validatedData['DESIGNATION'];
        $jourferie->JOUR_DEBUT = $validatedData['JOUR_DEBUT'];
        $jourferie->JOUR_FIN = $validatedData['JOUR_FIN'] ;
        
        $jourferie->save();


        return redirect()->route('admindash')->with('success', 'Jour férié ajouté avec succès.');
    }

    public function edit($id)
    {
        $jour_ferie = JourFerie::findOrFail($id);
        return view('edit_holiday', compact('jour_ferie'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'JOUR_DEBUT' => 'required|date',
            'JOUR_FIN' => 'nullable|date',
            'DESIGNATION' => 'required|string|max:255',
        ]);

        $jour_ferie = JourFerie::findOrFail($id);
        $jour_ferie->update($request->all());

        return redirect()->route('admindash')->with('success', 'Jour férié mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $jour_ferie = JourFerie::findOrFail($id);
        $jour_ferie->delete();

        return redirect()->route('admindash')->with('success', 'Jour férié supprimé avec succès.');
    }
}