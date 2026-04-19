<?php

namespace App\Http\Controllers;

use App\Events\CandidatureDeposee;
use App\Events\StatutCandidatureMis;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    // POST /api/offres/{offre}/candidater
    public function postuler(Request $request, Offre $offre)
    {
        $user = auth()->user();

        // Only candidats can apply
        if ($user->role !== 'candidat') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        // Must have a profil
        if (!$user->profil) {
            return response()->json(['message' => 'Créez votre profil d\'abord'], 422);
        }

        // Can't apply twice
        $exists = Candidature::where('offre_id', $offre->id)
            ->where('profil_id', $user->profil->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Vous avez déjà postulé à cette offre'], 422);
        }

        // Offre must be active
        if (!$offre->actif) {
            return response()->json(['message' => 'Cette offre n\'est plus active'], 422);
        }

        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        $candidature = Candidature::create([
            'offre_id'  => $offre->id,
            'profil_id' => $user->profil->id,
            'message'   => $request->message,
            'statut'    => 'en_attente',
        ]);

        event(new CandidatureDeposee($candidature));

        return response()->json($candidature->load('offre'), 201);
    }

    // GET /api/mes-candidatures
    public function mesCandidatures()
    {
        $user = auth()->user();

        if (!$user->profil) {
            return response()->json([]);
        }

        $candidatures = Candidature::with('offre')
            ->where('profil_id', $user->profil->id)
            ->latest()
            ->get();

        return response()->json($candidatures);
    }

    // GET /api/offres/{offre}/candidatures
    public function candidaturesRecues(Offre $offre)
    {
        $user = auth()->user();

        // Only the owner recruteur can see applications
        if ($offre->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $candidatures = Candidature::with('profil.user')
            ->where('offre_id', $offre->id)
            ->latest()
            ->get();

        return response()->json($candidatures);
    }

    // PATCH /api/candidatures/{candidature}/statut
    public function changerStatut(Request $request, Candidature $candidature)
    {
        $user = auth()->user();

        // Only the recruteur who owns the offre
        if ($candidature->offre->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        $ancienStatut = $candidature->statut;
        $candidature->update(['statut' => $request->statut]);

        event(new StatutCandidatureMis($candidature, $ancienStatut));

        return response()->json($candidature);
    }
}
