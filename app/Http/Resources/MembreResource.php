<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembreResource extends JsonResource
{
    /**
     * Transformer la ressource en tableau
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code_membre' => $this->code_membre,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'nom_complet' => $this->prenom . ' ' . $this->nom,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'date_naissance' => $this->date_naissance?->format('d/m/Y'),
            'age' => $this->date_naissance ? $this->date_naissance->age : null,
            'lieu_naissance' => $this->lieu_naissance,
            'adresse' => $this->adresse,
            'profession' => $this->profession,
            'statut' => $this->statut,
            'statut_label' => $this->getStatutLabel(),
            'date_adhesion' => $this->date_adhesion?->format('d/m/Y'),
            'photo_url' => $this->getPhotoUrl(),
            'notes' => $this->notes,
            
            // Relations
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'nom' => $this->role->nom,
                    'description' => $this->role->description,
                    'permissions' => $this->role->permissions ?? []
                ];
            }),
            
            // Statistiques
            'taux_presence' => $this->taux_presence,
            'total_cotisations' => $this->whenLoaded('assignationsCotisation', function () {
                return $this->assignationsCotisation->sum('montant_paye');
            }),
            'cotisations_en_cours' => $this->whenLoaded('assignationsCotisation', function () {
                return $this->assignationsCotisation->where('statut_paiement', '!=', 'paye')->count();
            }),
            'total_presences' => $this->whenLoaded('presences', function () {
                return $this->presences->count();
            }),
            'presences_recentes' => $this->whenLoaded('presences', function () {
                return $this->presences->take(5)->map(function ($presence) {
                    return [
                        'id' => $presence->id,
                        'date' => $presence->date_activite->format('d/m/Y'),
                        'statut' => $presence->statut,
                        'activite' => $presence->activite->nom ?? null
                    ];
                });
            }),
            
            // Métadonnées
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
            
            // Liens d'action
            'links' => [
                'show' => route('api.membres.show', $this->id),
                'edit' => route('api.membres.update', $this->id),
                'delete' => route('api.membres.destroy', $this->id),
                'profile' => route('api.membres.show', $this->id),
                'cotisations' => route('api.membres.cotisations', $this->id),
                'presences' => route('api.membres.presences', $this->id),
            ]
        ];
    }

    /**
     * Obtenir le label du statut
     */
    private function getStatutLabel(): string
    {
        return match ($this->statut) {
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir l'URL de la photo
     */
    private function getPhotoUrl(): ?string
    {
        if ($this->photo) {
            return asset('storage/photos/' . $this->photo);
        }
        
        // Générer un avatar avec les initiales
        $initiales = strtoupper(substr($this->prenom, 0, 1) . substr($this->nom, 0, 1));
        return "https://ui-avatars.com/api/?name={$initiales}&background=3b82f6&color=ffffff&size=128";
    }

    /**
     * Obtenir les données supplémentaires pour la réponse
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toISOString(),
                'request_id' => $request->header('X-Request-ID', uniqid())
            ]
        ];
    }
}
