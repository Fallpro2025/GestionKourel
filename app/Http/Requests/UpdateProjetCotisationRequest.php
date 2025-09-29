<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjetCotisationRequest extends FormRequest
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
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'montant_total' => 'required|numeric|min:0.01',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type_cotisation' => 'required|in:obligatoire,volontaire,evenement',
            'statut' => 'required|in:planifie,actif,suspendu,termine,annule',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés pour les règles de validation.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du projet est requis.',
            'nom.string' => 'Le nom du projet doit être une chaîne de caractères.',
            'nom.max' => 'Le nom du projet ne peut pas dépasser 200 caractères.',
            
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            
            'montant_total.required' => 'Le montant total est requis.',
            'montant_total.numeric' => 'Le montant total doit être un nombre.',
            'montant_total.min' => 'Le montant total doit être supérieur à 0.',
            
            'date_debut.required' => 'La date de début est requise.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            
            'date_fin.required' => 'La date de fin est requise.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            
            'type_cotisation.required' => 'Le type de cotisation est requis.',
            'type_cotisation.in' => 'Le type de cotisation doit être obligatoire, volontaire ou événement.',
            
            'statut.required' => 'Le statut est requis.',
            'statut.in' => 'Le statut doit être planifié, actif, suspendu, terminé ou annulé.',
        ];
    }

    /**
     * Obtenir les attributs personnalisés pour les messages d'erreur de validation.
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom du projet',
            'description' => 'description',
            'montant_total' => 'montant total',
            'date_debut' => 'date de début',
            'date_fin' => 'date de fin',
            'type_cotisation' => 'type de cotisation',
            'statut' => 'statut',
        ];
    }
}
