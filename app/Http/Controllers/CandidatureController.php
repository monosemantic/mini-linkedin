<?php

namespace App\Http\Controllers;

use App\Events\CandidatureDeposee;
use App\Events\StatutCandidatureMis;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    /** Permet a un candidat de postuler a une offre active. */
    public function postuler(Request $request, Offre $offre)
    {
        $user = auth()->user();

        // Seuls les candidats peuvent postuler.
        if ($user->role !== 'candidat') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        // Le profil est requis pour rattacher la candidature.
        if (!$user->profil) {
            return response()->json(['message' => 'Créez votre profil d\'abord'], 422);
        }

        // Une offre inactive ne doit plus accepter de candidature.
        if (!$offre->actif) {
            return response()->json(['message' => 'Cette offre n\'est plus active'], 422);
        }

        // Le couple offre/profil doit rester unique.
        $exists = Candidature::where('offre_id', $offre->id)
            ->where('profil_id', $user->profil->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Vous avez déjà postulé à cette offre'], 422);
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

        // Declenche l event pour journaliser la candidature via le listener.
        event(new CandidatureDeposee($candidature));

        return response()->json($candidature->load('offre'), 201);
    }

    /** Liste les candidatures du candidat connecte. */
    public function mesCandidatures()
    {
        $user = auth()->user();

        // Retour vide si le compte n a pas encore de profil candidat.
        if (!$user->profil) {
            return response()->json([]);
        }

        $candidatures = Candidature::with('offre')
            ->where('profil_id', $user->profil->id)
            ->latest()
            ->get();

        return response()->json($candidatures);
    }

    /** Liste les candidatures recues pour une offre du recruteur. */
    public function candidaturesRecues(Offre $offre)
    {
        $user = auth()->user();

        // Regle d ownership: seul le proprietaire de l offre peut consulter.
        if ($offre->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $candidatures = Candidature::with('profil.user')
            ->where('offre_id', $offre->id)
            ->latest()
            ->get();

        return response()->json($candidatures);
    }

    /** Modifie le statut d une candidature par le recruteur proprietaire. */
    public function changerStatut(Request $request, Candidature $candidature)
    {
        $user = auth()->user();

        // Regle d ownership: seul le proprietaire de l offre peut modifier.
        if ($candidature->offre->user_id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        $ancienStatut = $candidature->statut;
        $candidature->update(['statut' => $request->statut]);

        // Declenche l event pour tracer le changement de statut.
        event(new StatutCandidatureMis($candidature, $ancienStatut));

        return response()->json($candidature);
    }
}
