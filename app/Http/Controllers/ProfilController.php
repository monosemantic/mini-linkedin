<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Http\Requests\StoreCompetenceRequest;

class ProfilController extends Controller
{
    /** Cree le profil du candidat connecte une seule fois. */
    public function store(StoreProfilRequest $request)
    {
        $user = auth()->user();

        // Un utilisateur ne peut posseder qu un seul profil.
        if ($user->profil) {
            return response()->json([
                'message' => 'Le profil existe déjà'
            ], 409);
        }

        $data = $request->validated();

        $profilData = [
            "user_id" => $user->id,
            "titre" => $data["titre"],
            "bio" => $data["bio"] ?? null,
            "localisation" => $data["localisation"] ?? null,
        ];

        // array_key_exists preserve le false explicite envoye par le client.
        if (array_key_exists("disponible", $data)) {
            $profilData["disponible"] = $data["disponible"];
        }

        $profil = Profil::create($profilData);

        return response()->json([
            'message' => 'Profil créé avec succès',
            'profil' => $profil
        ], 201);
    }

    /** Retourne le profil du candidat connecte. */
    public function show()
    {
        $user = auth()->user();

        $profil = $user->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Profil introuvable',
            ], 404);
        }

        return response()->json([
            'profil' => $profil
        ]);
    }

    /** Met a jour le profil du candidat connecte. */
    public function update(UpdateProfilRequest $request)
    {
        $user = auth()->user();

        $profil = $user->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Profil introuvable',
            ], 404);
        }

        $data = $request->validated();
        $profil->update([
            "titre" => $data["titre"] ?? $profil->titre,
            "bio" => $data["bio"] ?? $profil->bio,
            "localisation" => $data["localisation"] ?? $profil->localisation,
            "disponible" => $data["disponible"] ?? $profil->disponible,
        ]);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'profil' => $profil
        ]);
    }

    /** Ajoute une competence existante au profil avec son niveau. */
    public function addCompetence(StoreCompetenceRequest $request)
    {
        $user = auth()->user();

        $profil = $user->profil;

        if (!$profil) {
            return response()->json(['message' => 'Profil introuvable'], 404);
        }

        $data = $request->validated();
        $alreadyAdded = $profil->competences()
            ->wherePivot('competence_id', $data['competence_id'])
            ->exists();

        if ($alreadyAdded) {
            return response()->json([
                'message' => 'Compétence déjà ajoutée'
            ], 409);
        }

        $profil->competences()->attach($data['competence_id'] , ['niveau' => $data['niveau']]);

        return response()->json([
            'message' => 'Compétence ajoutée avec succès'
        ]);
    }

    /** Retire une competence du profil du candidat connecte. */
    public function removeCompetence($competenceId)
    {
        $user = auth()->user();

        $profil = $user->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Profil introuvable'
            ], 404);
        }

        $profil->competences()->detach($competenceId);

        return response()->json([
            'message' => 'Compétence supprimée avec succès' 
        ]);
    }
}
