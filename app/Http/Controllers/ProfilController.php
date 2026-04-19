<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profil;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;
use App\Http\Requests\StoreCompetenceRequest;

class ProfilController extends Controller
{
    public function store(StoreProfilRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // vérifier que le profil existe
        if ($user->profil) {
            return response()->json([
                'message' => 'Le profil existe déjà'
            ], 409);
        }

        // valider en utilisant une requête de formulaire
        $data = $request->validated();

        // remplir les données du profil
        $profilData = [
            "user_id" => $user->id,
            "titre" => $data["titre"],
            "bio" => $data["bio"] ?? null,
            "localisation" => $data["localisation"] ?? null,
        ];

        if (array_key_exists("disponible", $data)) {
            $profilData["disponible"] = $data["disponible"];
        }
        // créer le profil
        $profil = Profil::create($profilData);

        return response()->json([
            'message' => 'Profil créé avec succès',
            'profil' => $profil
        ], 201);
    }
    public function show()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié',
            ], 401);
        }

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
    public function update(UpdateProfilRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié',
            ], 401);
        }
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
    public function addCompetence(StoreCompetenceRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

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
    public function removeCompetence($competenceId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

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
