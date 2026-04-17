<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profil;
use App\Http\Requests\StoreProfilRequest;
use App\Http\Requests\UpdateProfilRequest;

class ProfilController extends Controller
{
    public function store( StoreProfilRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        // check profile exists
        if ($user->profil) {
            return response()->json([
                'message' => 'Profile already exists'
            ], 409);
        }

        // validate using Form request
        $data = $request->validated();

        // create profile
        $profil = Profil::create([
            "user_id" => $user->id,
            "titre" => $data["titre"],
            "bio" => $data["bio"] ?? null,
            "localisation" => $data["localisation"] ?? null,
            "disponible" => $data["disponible"] ?? false,
        ]);
        return response()->json([
            'message' => 'Profile created successfully',
            'profil' => $profil
        ], 201);
    }
    public function show()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
            'message' => 'Not authenticated'
            ], 401);
        }

        $profil = $user->profil; 

        if (!$profil) {
            return response()->json([
            'message' => 'Profile not found'
            ], 404);
        }

        return response()->json([
            'profil' => $profil
        ]);
    }
    public function update( UpdateProfilRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
            'message' => 'Not authenticated'
            ], 401);
        }
        $profil = $user->profil;

        if (!$profil) {
            return response()->json([
            'message' => 'Profile not found'
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
        'message' => 'Profile updated successfully',
        'profil' => $profil
        ]);
    }
    public function addCompetence(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401);
        }

        $profil = $user->profil;

        if (!$profil) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $data = $request->validate([
            'competence_id' => 'required|exists:competences,id'
        ]);


        $profil->competences()->attach($data['competence_id']);

        return response()->json([
            'message' => 'Competence added successfully'
        ]);
    }
    public function removeCompetence($competenceId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
            'message' => 'Not authenticated'
            ], 401);
        }

        $profil = $user->profil;

        if (!$profil) {
            return response()->json([
            'message' => 'Profile not found'
            ], 404);
        }
    
        $profil->competences()->detach($competenceId);

        return response()->json([
        'message' => 'Competence removed successfully'
        ]);
}
}