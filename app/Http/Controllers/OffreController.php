<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Http\Requests\StoreOffreRequest;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    /** Liste les offres actives avec filtres et pagination. */
    public function index(Request $request)
    {

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

    /** Affiche le detail d une offre visible par l utilisateur courant. */
    public function show(Offre $offre)
    {
        // Un recruteur ne voit une offre inactive que si elle lui appartient.
        if (!$offre->actif && $offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Offre introuvable'], 404);
        }
        return response()->json($offre);
    }

    /** Cree une nouvelle offre rattachee au recruteur connecte. */
    public function store(StoreOffreRequest $request)
    {
        $offre = Offre::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
            'actif'   => true,
        ]);
        return response()->json($offre, 201);
    }

    /** Met a jour une offre si le recruteur en est proprietaire. */
    public function update(StoreOffreRequest $request, Offre $offre)
    {
        // Regle d ownership: seul le proprietaire peut modifier son offre.
        if ($offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
        $offre->update($request->validated());
        return response()->json($offre);
    }

    /** Supprime une offre si le recruteur en est proprietaire. */
    public function destroy(Offre $offre)
    {
        // Regle d ownership: seul le proprietaire peut supprimer son offre.
        if ($offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $offre->delete();

        return response()->json(['message' => 'Offre supprimée avec succès']);
    }
}
