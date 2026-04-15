<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Offre;

class OffreController extends Controller {
    public function index(Request $request)
    {
        $query = Offre::where('actif',true);
        if ($request->has('localisation')) {
            $query->where('localisation', $request->localisation);
        }
        if ($request->has('localisation')) {
            $query->where('localisation', $request->localisation);
        }
        $offres = $query->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($offres, 200);
    }
    public function show(Offre $offre){
        return response()->json($offre, 200);
    }
    public function store(Request $request){
        $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'localisation' => 'required|string|max:255',
            'type'         => 'required|in:CDI,CDD,stage',
        ]);

        $offre = Offre::create([
            'titre'        => $request->titre,
            'description'  => $request->description,
            'localisation' => $request->localisation,
            'type'         => $request->type,
            'user_id'      => auth()->id(),
            'actif'        => true,
        ]);

        return response()->json($offre, 201);
    }
    public function update(Request $request, Offre $offre){
        if ($offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'titre'        => 'sometimes|string|max:255',
            'description'  => 'sometimes|string',
            'localisation' => 'sometimes|string|max:255',
            'type'         => 'sometimes|in:CDI,CDD,stage',
        ]);

        $offre->update($request->only(['titre', 'description', 'localisation', 'type']));

        return response()->json($offre, 200);
    }
    public function destroy(Offre $offre){
        if ($offre->user_id !== auth()->id()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $offre->delete();

        return response()->json(['message' => 'Offre supprimée avec succès'], 200);
    }
}