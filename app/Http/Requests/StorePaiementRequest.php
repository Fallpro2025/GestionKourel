<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest
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
            'montant' => 'required|numeric|min:0.01',
            'methode' => 'required|string|in:espèces,virement,chèque,mobile_money',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés pour les règles de validation.
     */
    public function messages(): array
    {
        return [
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            
            'methode.required' => 'La méthode de paiement est requise.',
            'methode.string' => 'La méthode de paiement doit être une chaîne de caractères.',
            'methode.in' => 'La méthode de paiement doit être espèces, virement, chèque ou mobile_money.',
            
            'notes.string' => 'Les notes doivent être une chaîne de caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 500 caractères.',
        ];
    }

    /**
     * Obtenir les attributs personnalisés pour les messages d'erreur de validation.
     */
    public function attributes(): array
    {
        return [
            'montant' => 'montant',
            'methode' => 'méthode de paiement',
            'notes' => 'notes',
        ];
    }
}
