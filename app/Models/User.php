<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Retourne l identifiant principal utilise dans le token JWT.
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Ajoute le role dans les claims pour simplifier les controles d acces.
    public function getJWTCustomClaims(): array
    {
        return ['role' => $this->role];
    }

    // Relations Eloquent.
    public function profil()
    {
        return $this->hasOne(Profil::class);
    }

    public function offres()
    {
        return $this->hasMany(Offre::class);
    }
}
