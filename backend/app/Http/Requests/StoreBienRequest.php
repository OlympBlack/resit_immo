<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'type' => 'required|in:maison,appartement',
            'adresse' => 'required|string|max:255',
            'ville' => 'nullable|string|max:100',
            'prix' => 'required|numeric|min:0',
            'proprietaire_id' => 'required|integer|exists:proprietaires,id',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre du bien est obligatoire.',
            'type.required' => 'Le type du bien est obligatoire.',
            'type.in' => 'Le type doit être "maison" ou "appartement".',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'proprietaire_id.required' => 'Le propriétaire est obligatoire.',
            'proprietaire_id.exists' => 'Le propriétaire sélectionné n\'existe pas.',
        ];
    }
}
