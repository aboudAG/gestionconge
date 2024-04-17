<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('types.index', compact('types'));
    }

    public function create()
    {
        return view('types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOM' => 'required|string|max:50',

        ]);

        Type::create($request->all());

        return redirect()->route('types.index')->with('success', 'Type créé avec succès.');
    }

    public function edit($id)
    {
        $type = Type::where('ID', $id)->firstOrFail();
        return view('types.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'NOM' => 'required|max:50',
            // Ajoutez d'autres règles de validation si nécessaire
        ]);

        $type = Type::findOrFail($id);
        Type::where('id' , $id)->update([
            'NOM' => $request->NOM,
        ]);

        return redirect()->route('types.index')->with('success', 'Type mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id); // Trouver la structure ou échouer si non trouvée
        Type::where('ID', $id)->delete();

        return redirect()->route('types.index')->with('success', 'Type supprimé avec succès.');
    }
}
