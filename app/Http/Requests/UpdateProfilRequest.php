<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
{
    /** Autorise la requete de mise a jour du profil. */
    public function authorize(): bool
    {
        return true;
    }

    /** Retourne les regles de validation de la mise a jour profil. */
    public function rules()
    {
        return [
            'titre' => 'sometimes|string',
            'bio' => 'sometimes|string',
            'localisation' => 'sometimes|string',
            'disponible' => 'sometimes|boolean',
        ];
    }
}
