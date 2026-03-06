<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bien_id' => 'required|integer|exists:biens,id',
            'locataire_id' => 'required|integer|exists:locataires,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'montant_mensuel' => 'required|numeric|min:0',
            'statut' => 'sometimes|in:actif,termine,resilie',
        ];
    }

    public function messages(): array
    {
        return [
            'bien_id.required' => 'Le bien est obligatoire.',
            'bien_id.exists' => 'Le bien sélectionné n\'existe pas.',
            'locataire_id.required' => 'Le locataire est obligatoire.',
            'locataire_id.exists' => 'Le locataire sélectionné n\'existe pas.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'montant_mensuel.required' => 'Le montant mensuel est obligatoire.',
            'montant_mensuel.numeric' => 'Le montant mensuel doit être un nombre.',
            'statut.in' => 'Le statut doit être "actif", "termine" ou "resilie".',
        ];
    }
}
