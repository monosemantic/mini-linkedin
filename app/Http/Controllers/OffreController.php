<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Http\Requests\StoreOffreRequest;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    // get liste
    public function index(Request $request){

        $query = Offre::where('actif', true)
            ->orderBy('created_at', 'desc');

        if ($request->localisation) {
            $query->where('localisation', 'like', '%' . $request->localisation . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        return response()->json($query->paginate(10));
    }

    // get detail de l'offre
    public function show(Offre $offre){
        if (!$offre->actif && $offre->user_id !== auth()->id()) {
            abort(404);
        }
        return response()->json($offre);
    }

    // post cree un offre
    public function store(StoreOffreRequest $request){
        $offre = Offre::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'actif'   => true,
        ]);
        return response()->json($offre, 201);
    }

    // put modifier une offre
    public function update(StoreOffreRequest $request, Offre $offre){
        if ($offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $offre->update($request->validated());
        return response()->json($offre);
    }

    // delete supprimer une offre
    public function destroy(Offre $offre){
        if ($offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $offre->delete();

        return response()->json(['message' => 'Offre supprimée avec succès']);
    }
}