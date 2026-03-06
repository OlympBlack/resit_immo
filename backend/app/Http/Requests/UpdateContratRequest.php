<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bien_id' => 'sometimes|required|integer|exists:biens,id',
            'locataire_id' => 'sometimes|required|integer|exists:locataires,id',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'montant_mensuel' => 'sometimes|required|numeric|min:0',
            'statut' => 'sometimes|in:actif,termine,resilie',
        ];
    }

    public function messages(): array
    {
        return [
            'bien_id.exists' => 'Le bien sélectionné n\'existe pas.',
            'locataire_id.exists' => 'Le locataire sélectionné n\'existe pas.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'montant_mensuel.numeric' => 'Le montant mensuel doit être un nombre.',
            'statut.in' => 'Le statut doit être "actif", "termine" ou "resilie".',
        ];
    }
}
