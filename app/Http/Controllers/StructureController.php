<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    // Affiche la liste des structures
    public function index()
    {
        $structures = Structure::all();

        return view('structures.index', compact('structures'));
    }

    // Affiche le formulaire de création d'une nouvelle structure
    public function create()
    {
        return view('structures.create');
    }

    // Enregistre la nouvelle structure dans la base de données
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'CODE' => 'required|unique:structures,code',
            'NOM' => 'required|max:255',
            'TYPE' => 'required',
            'PARENT_ID' => 'nullable|exists:structures,id', // Ensure PARENT_ID exists in the database if provided
        ]);
    
        // Create the structure without chemin first
        $structure = Structure::create($validatedData);
    
        // Determine the chemin based on the parent (if there's a parent)
        $parentChemin = '';
        if (isset($validatedData['PARENT_ID'])) {
            $parentStructure = Structure::find($validatedData['PARENT_ID']);
            $parentChemin = $parentStructure->CHEMIN ?? '';
        }
    
        // Update chemin with its own ID appended to parent's chemin
        $structure->CHEMIN = trim($parentChemin . $structure->id, '/');
        $structure->save();
    
        return redirect()->route('structures.index')->with('success', 'Structure ajoutée avec succès.');
    }
    /**
 * Show the form for editing the specified role.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */


    public function edit($id)
    {
        // Trouver la structure par son ID
        $structure = Structure::findOrFail($id);

        // Retourner la vue d'édition avec la structure à modifier
        return view('structures.edit', compact('structure'));
    }

    /**
 * Update the specified role in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $ID
 * @return \Illuminate\Http\Response
 */


    // Méthode pour mettre à jour la structure dans la base de données
    public function update(Request $request, $ID)
    {
        // Valider les données reçues du formulaire
        $validatedData = $request->validate([
            'CODE' => 'required|string|max:255',
            'NOM' => 'required|string|max:255',
            'TYPE' => 'required|string|max:255',
            'PARENT_ID' => 'nullable|integer',
        ]);



        $structure = Structure::findOrFail($ID);
            Structure::where('ID',$ID)->update([
                'CODE' => $request->CODE,
                'NOM' => $request->NOM,
                'TYPE' => $request->TYPE,
                'PARENT_ID' => $request->PARENT_ID,
            ]);


        return redirect()->route('structures.index')->with('success', 'Structure mise à jour avec succès.');
    }

    public function destroy(string $ID)
{
    $structure = Structure::findOrFail($ID); // Trouver la structure ou échouer si non trouvée
    Structure::where('ID', $ID)->delete();
 // Supprimer la structure

    // Redirection vers l'index avec un message flash de succès
    return redirect()->route('structures.index')->with('success', 'Structure supprimée avec succès.');
}
}

