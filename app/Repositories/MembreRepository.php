<?php

namespace App\Repositories;

use App\Models\Membre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MembreRepository
{
    protected Membre $model;

    public function __construct(Membre $model)
    {
        $this->model = $model;
    }

    /**
     * Obtenir tous les membres avec pagination et filtres
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['role', 'assignationsCotisation', 'presences']);
        
        // Appliquer les filtres
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('telephone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if (isset($filters['role']) && !empty($filters['role'])) {
            $query->whereHas('role', function ($q) use ($filters) {
                $q->where('nom', $filters['role']);
            });
        }
        
        if (isset($filters['statut']) && !empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }
        
        // Appliquer le tri
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        
        $allowedSortFields = ['nom', 'prenom', 'created_at', 'taux_presence', 'statut'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Obtenir tous les membres
     */
    public function getAll(): Collection
    {
        return $this->model->with(['role'])->get();
    }

    /**
     * Trouver un membre par ID
     */
    public function find(int $id): ?Membre
    {
        return $this->model->find($id);
    }

    /**
     * Trouver un membre par ID avec ses relations
     */
    public function findWithRelations(int $id): ?Membre
    {
        return $this->model->with([
            'role',
            'assignationsCotisation.projetCotisation',
            'presences.activite',
            'evenements'
        ])->find($id);
    }

    /**
     * Créer un nouveau membre
     */
    public function create(array $data): Membre
    {
        return $this->model->create($data);
    }

    /**
     * Mettre à jour un membre
     */
    public function update(int $id, array $data): Membre
    {
        $membre = $this->model->findOrFail($id);
        $membre->update($data);
        return $membre;
    }

    /**
     * Supprimer un membre
     */
    public function delete(int $id): bool
    {
        $membre = $this->model->findOrFail($id);
        return $membre->delete();
    }

    /**
     * Rechercher des membres
     */
    public function search(string $query): Collection
    {
        return $this->model->with(['role'])
            ->where(function ($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('prenom', 'LIKE', "%{$query}%")
                  ->orWhere('telephone', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('code_membre', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();
    }

    /**
     * Obtenir les membres par rôle
     */
    public function getByRole(string $roleName): Collection
    {
        return $this->model->with(['role'])
            ->whereHas('role', function ($q) use ($roleName) {
                $q->where('nom', $roleName);
            })
            ->get();
    }

    /**
     * Obtenir les membres actifs
     */
    public function getActive(): Collection
    {
        return $this->model->with(['role'])
            ->where('statut', 'actif')
            ->get();
    }

    /**
     * Obtenir les membres avec cotisations en retard
     */
    public function getWithLatePayments(): Collection
    {
        return $this->model->with(['role', 'assignationsCotisation'])
            ->whereHas('assignationsCotisation', function ($q) {
                $q->where('statut_paiement', 'en_retard');
            })
            ->get();
    }

    /**
     * Obtenir les statistiques des présences
     */
    public function getPresenceStatistics(): array
    {
        $stats = DB::table('membres')
            ->select([
                DB::raw('AVG(taux_presence) as moyenne_presence'),
                DB::raw('MIN(taux_presence) as min_presence'),
                DB::raw('MAX(taux_presence) as max_presence'),
                DB::raw('COUNT(*) as total_membres')
            ])
            ->where('statut', 'actif')
            ->first();
        
        return [
            'moyenne_presence' => round($stats->moyenne_presence ?? 0, 2),
            'min_presence' => $stats->min_presence ?? 0,
            'max_presence' => $stats->max_presence ?? 0,
            'total_membres' => $stats->total_membres ?? 0,
        ];
    }

    /**
     * Obtenir l'évolution des membres par mois
     */
    public function getMonthlyEvolution(int $months = 12): array
    {
        $evolution = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = $this->model->whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count();
            
            $evolution[] = [
                'mois' => $date->format('M Y'),
                'count' => $count,
                'date' => $date->format('Y-m')
            ];
        }
        
        return $evolution;
    }

    /**
     * Obtenir les membres avec le plus faible taux de présence
     */
    public function getLowestPresence(int $limit = 10): Collection
    {
        return $this->model->with(['role'])
            ->where('statut', 'actif')
            ->orderBy('taux_presence', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les membres avec le plus haut taux de présence
     */
    public function getHighestPresence(int $limit = 10): Collection
    {
        return $this->model->with(['role'])
            ->where('statut', 'actif')
            ->orderBy('taux_presence', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Compter les membres par statut
     */
    public function countByStatus(): array
    {
        return $this->model->select('statut')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();
    }

    /**
     * Compter les membres par rôle
     */
    public function countByRole(): array
    {
        return $this->model->join('roles', 'membres.role_id', '=', 'roles.id')
            ->select('roles.nom as role')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('roles.nom')
            ->pluck('count', 'role')
            ->toArray();
    }
}
