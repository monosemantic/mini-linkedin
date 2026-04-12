<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    protected $fillable = ['user_id', 'titre', 'description', 'localisation', 'type', 'actif'];

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }
}
