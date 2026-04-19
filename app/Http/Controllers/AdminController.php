<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Offre;

class AdminController extends Controller
{
    // get /api/admin/users
    public function listUsers()
    {
        return response()->json(User::select('id', 'name', 'email', 'role', 'created_at')->get());
    }

    // delete /api/admin/users
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer votre propre compte'], 403);
        }

        if ($user->role === 'admin') {
            return response()->json(['message' => 'Impossible de supprimer un autre admin'], 403);
        }

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
