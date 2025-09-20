<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembreRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête
     */
    public function authorize(): bool
    {
        return true; // TODO: Ajouter la logique d'autorisation
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête
     */
    public function rules(): array
    {
        return [
            'nom' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u'
            ],
            'prenom' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u'
            ],
            'telephone' => [
                'required',
                'string',
                'max:20',
                'unique:membres,telephone',
                'regex:/^(\+221|221)?[0-9]{9}$/'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:membres,email'
            ],
            'date_naissance' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01'
            ],
            'lieu_naissance' => [
                'nullable',
                'string',
                'max:100'
            ],
            'adresse' => [
                'nullable',
                'string',
                'max:255'
            ],
            'profession' => [
                'nullable',
                'string',
                'max:100'
            ],
            'role_id' => [
                'nullable',
                'integer',
                'exists:roles,id'
            ],
            'statut' => [
                'required',
                'string',
                Rule::in(['actif', 'inactif', 'suspendu'])
            ],
            'date_adhesion' => [
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB max
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes',
            'prenom.required' => 'Le prénom est obligatoire',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, espaces, tirets et apostrophes',
            'telephone.required' => 'Le numéro de téléphone est obligatoire',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
            'telephone.regex' => 'Le numéro de téléphone doit être au format sénégalais (+221XXXXXXXXX)',
            'email.email' => 'L\'adresse email doit être valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui',
            'date_naissance.after' => 'La date de naissance doit être postérieure à 1900',
            'statut.required' => 'Le statut est obligatoire',
            'statut.in' => 'Le statut doit être actif, inactif ou suspendu',
            'date_adhesion.before_or_equal' => 'La date d\'adhésion ne peut pas être future',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB',
            'role_id.exists' => 'Le rôle sélectionné n\'existe pas',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères'
        ];
    }

    /**
     * Obtenir les attributs personnalisés pour les erreurs de validation
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom',
            'prenom' => 'prénom',
            'telephone' => 'numéro de téléphone',
            'email' => 'adresse email',
            'date_naissance' => 'date de naissance',
            'lieu_naissance' => 'lieu de naissance',
            'adresse' => 'adresse',
            'profession' => 'profession',
            'role_id' => 'rôle',
            'statut' => 'statut',
            'date_adhesion' => 'date d\'adhésion',
            'photo' => 'photo',
            'notes' => 'notes'
        ];
    }

    /**
     * Préparer les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer et formater les données
        if ($this->has('telephone')) {
            $telephone = $this->input('telephone');
            // Supprimer les espaces et caractères spéciaux
            $telephone = preg_replace('/[^0-9+]/', '', $telephone);
            
            // Ajouter le préfixe +221 si nécessaire
            if (strpos($telephone, '+221') !== 0 && strpos($telephone, '221') !== 0) {
                if (strlen($telephone) === 9) {
                    $telephone = '+221' . $telephone;
                }
            }
            
            $this->merge(['telephone' => $telephone]);
        }

        // Formater le nom et prénom (première lettre en majuscule)
        if ($this->has('nom')) {
            $this->merge(['nom' => ucwords(strtolower(trim($this->input('nom'))))]);
        }

        if ($this->has('prenom')) {
            $this->merge(['prenom' => ucwords(strtolower(trim($this->input('prenom'))))]);
        }

        // Formater l'email en minuscules
        if ($this->has('email') && $this->input('email')) {
            $this->merge(['email' => strtolower(trim($this->input('email')))]);
        }
    }
}
