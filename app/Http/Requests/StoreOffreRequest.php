<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'localisation' => 'required|string|max:255',
            'type'         => 'required|in:CDI,CDD,stage',
        ];
    }

    public function messages()
    {
        return [
            'titre.required'        => 'Le titre est obligatoire.',
            'description.required'  => 'La description est obligatoire.',
            'localisation.required' => 'La localisation est obligatoire.',
            'type.required'         => 'Le type est obligatoire.',
            'type.in'               => 'Le type doit être CDI, CDD ou stage.',
        ];
    }
}
