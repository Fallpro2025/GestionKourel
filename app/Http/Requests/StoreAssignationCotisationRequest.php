<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignationCotisationRequest extends FormRequest
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
            'membre_id' => 'required|exists:membres,id',
            'montant_assigné' => 'required|numeric|min:0.01',
            'date_echeance' => 'required|date|after_or_equal:today',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés pour les règles de validation.
     */
    public function messages(): array
    {
        return [
            'membre_id.required' => 'Le membre est requis.',
            'membre_id.exists' => 'Le membre sélectionné n\'existe pas.',
            
            'montant_assigné.required' => 'Le montant assigné est requis.',
            'montant_assigné.numeric' => 'Le montant assigné doit être un nombre.',
            'montant_assigné.min' => 'Le montant assigné doit être supérieur à 0.',
            
            'date_echeance.required' => 'La date d\'échéance est requise.',
            'date_echeance.date' => 'La date d\'échéance doit être une date valide.',
            'date_echeance.after_or_equal' => 'La date d\'échéance doit être aujourd\'hui ou plus tard.',
        ];
    }

    /**
     * Obtenir les attributs personnalisés pour les messages d'erreur de validation.
     */
    public function attributes(): array
    {
        return [
            'membre_id' => 'membre',
            'montant_assigné' => 'montant assigné',
            'date_echeance' => 'date d\'échéance',
        ];
    }
}
