<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocataireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locataireId = $this->route('locataire');

        return [
            'nom' => 'sometimes|required|string|max:100',
            'prenom' => 'sometimes|required|string|max:100',
            'email' => "nullable|email|max:255|unique:locataires,email,{$locataireId}",
            'telephone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.email' => 'L\'adresse email est invalide.',
            'email.unique' => 'Cet email est déjà utilisé par un autre locataire.',
        ];
    }
}
