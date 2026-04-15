<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Offre;

class AdminController extends Controller
{
     public function listUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    }

    public function toggleOffre(Offre $offre)
    {
        $offre->actif = !$offre->actif;
        $offre->save();

        return response()->json([
            'message' => 'Statut de l\'offre mis à jour',
            'actif'   => $offre->actif
        ], 200);
    }
    
}
