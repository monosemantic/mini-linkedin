<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Cree un compte utilisateur puis retourne un token JWT. */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'sometimes|in:candidat,recruteur' // Un admin ne peut pas s auto inscrire.
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'candidat'
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token, $user, 201);
    }

    /** Authentifie un utilisateur et retourne un token JWT. */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
        }

        return $this->respondWithToken($token, auth()->user());
    }

    /** Retourne le profil de l utilisateur connecte. */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /** Invalide le token JWT courant. */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /** Renouvelle le token JWT de l utilisateur connecte. */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), auth()->user());
    }

    private function respondWithToken(string $token, User $user, int $status = 200)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => $user,
        ], $status);
    }
}
