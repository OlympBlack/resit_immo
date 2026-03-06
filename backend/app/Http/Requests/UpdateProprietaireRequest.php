<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProprietaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ignorer l'email du propriétaire en cours de modification (unique)
        $proprietaireId = $this->route('proprietaire');

        return [
            'nom' => 'sometimes|required|string|max:100',
            'prenom' => 'sometimes|required|string|max:100',
            'email' => "nullable|email|max:255|unique:proprietaires,email,{$proprietaireId}",
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.email' => 'L\'adresse email est invalide.',
            'email.unique' => 'Cet email est déjà utilisé par un autre propriétaire.',
        ];
    }
}
