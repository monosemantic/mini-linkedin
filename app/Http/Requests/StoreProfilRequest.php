<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // already handled by auth-api middleware
    }

    public function rules(): array
    {
        return [
            "titre" => "required|string|max:50",
            "bio" => "nullable|string|max:200",
            "localisation" => "nullable|string|max:100",
            "disponible" => "sometimes|boolean",
        ];
    }
}