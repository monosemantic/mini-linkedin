<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompetenceRequest extends FormRequest
{
    /** Autorise la requete d ajout de competence au profil. */
    public function authorize(): bool
    {
        return true;
    }

    /** Retourne les regles de validation pour l ajout de competence. */
    public function rules(): array
    {
        return [
            'competence_id' => 'required|exists:competences,id',
            'niveau' => 'required|in:debutant,intermediaire,expert',
        ];
    }
}
