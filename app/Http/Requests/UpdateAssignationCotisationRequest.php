<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignationCotisationRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // TODO: Ajouter la logique d'autorisation
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête.
     */
    public function rules(): array
    {
        return [
            'montant_assigné' => 'required|numeric|min:0.01',
            'date_echeance' => 'required|date',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés pour les règles de validation.
     */
    public function messages(): array
    {
        return [
            'montant_assigné.required' => 'Le montant assigné est requis.',
            'montant_assigné.numeric' => 'Le montant assigné doit être un nombre.',
            'montant_assigné.min' => 'Le montant assigné doit être supérieur à 0.',
            
            'date_echeance.required' => 'La date d\'échéance est requise.',
            'date_echeance.date' => 'La date d\'échéance doit être une date valide.',
        ];
    }

    /**
     * Obtenir les attributs personnalisés pour les messages d'erreur de validation.
     */
    public function attributes(): array
    {
        return [
            'montant_assigné' => 'montant assigné',
            'date_echeance' => 'date d\'échéance',
        ];
    }
}
