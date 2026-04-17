<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Offre;

class AdminController extends Controller
{
    // get /api/admin/users
    public function listUsers()
    {
        return response()->json(User::all());
    }

    // delete /api/admin/users
    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    // patch /api/admin/offres
    public function toggleOffre(Offre $offre)
    {
        $offre->actif = !$offre->actif;
        $offre->save();

        return response()->json([
            'message' => $offre->actif ? 'Offre activée' : 'Offre désactivée',
            'actif'   => $offre->actif,
        ]);
    }
}
