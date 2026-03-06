<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:maison,appartement',
            'adresse' => 'sometimes|required|string|max:255',
            'ville' => 'nullable|string|max:100',
            'prix' => 'sometimes|required|numeric|min:0',
            'proprietaire_id' => 'sometimes|required|integer|exists:proprietaires,id',
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Le type doit être "maison" ou "appartement".',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'proprietaire_id.exists' => 'Le propriétaire sélectionné n\'existe pas.',
        ];
    }
}
