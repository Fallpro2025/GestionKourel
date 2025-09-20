<?php

namespace App\Services;

use App\Models\Membre;
use App\Models\Role;
use App\Repositories\MembreRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MembreService
{
    protected MembreRepository $membreRepository;

    public function __construct(MembreRepository $membreRepository)
    {
        $this->membreRepository = $membreRepository;
    }

    /**
     * Obtenir les membres avec pagination et filtres
     */
    public function getMembresPaginated(array $filters = []): LengthAwarePaginator
    {
        return $this->membreRepository->getPaginated($filters);
    }

    /**
     * Créer un nouveau membre
     */
    public function createMembre(array $data): Membre
    {
        DB::beginTransaction();
        
        try {
            // Créer le membre
            $membre = $this->membreRepository->create($data);
            
            // Assigner un rôle par défaut si non spécifié
            if (!isset($data['role_id'])) {
                $roleDefault = Role::where('nom', 'Membre')->first();
                if ($roleDefault) {
                    $membre->role_id = $roleDefault->id;
                    $membre->save();
                }
            }
            
            // Générer un code membre unique
            $membre->code_membre = $this->generateCodeMembre($membre);
            $membre->save();
            
            DB::commit();
            
            return $membre->load('role');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtenir un membre avec ses relations
     */
    public function getMembreWithRelations(int $membreId): Membre
    {
        return $this->membreRepository->findWithRelations($membreId);
    }

    /**
     * Mettre à jour un membre
     */
    public function updateMembre(int $membreId, array $data): Membre
    {
        DB::beginTransaction();
        
        try {
            $membre = $this->membreRepository->update($membreId, $data);
            
            DB::commit();
            
            return $membre->load('role');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Supprimer un membre
     */
    public function deleteMembre(int $membreId): bool
    {
        DB::beginTransaction();
        
        try {
            // Vérifier si le membre a des cotisations ou présences
            $membre = Membre::findOrFail($membreId);
            
            if ($membre->assignationsCotisation()->count() > 0) {
                throw new \Exception('Impossible de supprimer un membre avec des cotisations en cours');
            }
            
            if ($membre->presences()->count() > 0) {
                throw new \Exception('Impossible de supprimer un membre avec un historique de présences');
            }
            
            $result = $this->membreRepository->delete($membreId);
            
            DB::commit();
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Rechercher des membres
     */
    public function searchMembres(string $query): Collection
    {
        return $this->membreRepository->search($query);
    }

    /**
     * Obtenir les statistiques des membres
     */
    public function getMembreStatistics(): array
    {
        $totalMembres = Membre::count();
        $membresActifs = Membre::where('statut', 'actif')->count();
        $membresInactifs = Membre::where('statut', 'inactif')->count();
        
        // Statistiques par rôle
        $statistiquesParRole = Role::withCount('membres')->get()->map(function ($role) {
            return [
                'role' => $role->nom,
                'count' => $role->membres_count
            ];
        });
        
        // Moyenne des présences
        $moyennePresence = Membre::avg('taux_presence') ?? 0;
        
        // Membres avec cotisations en retard
        $cotisationsEnRetard = Membre::whereHas('assignationsCotisation', function ($query) {
            $query->where('statut_paiement', 'en_retard');
        })->count();
        
        return [
            'total_membres' => $totalMembres,
            'membres_actifs' => $membresActifs,
            'membres_inactifs' => $membresInactifs,
            'moyenne_presence' => round($moyennePresence, 2),
            'cotisations_en_retard' => $cotisationsEnRetard,
            'statistiques_par_role' => $statistiquesParRole,
            'evolution_mensuelle' => $this->getEvolutionMensuelle(),
        ];
    }

    /**
     * Exporter les membres
     */
    public function exportMembres(string $format = 'pdf'): string
    {
        $membres = Membre::with('role')->get();
        
        if ($format === 'pdf') {
            return $this->exportToPdf($membres);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($membres);
        }
        
        throw new \Exception('Format d\'export non supporté');
    }

    /**
     * Générer un code membre unique
     */
    private function generateCodeMembre(Membre $membre): string
    {
        $prefix = 'MEM';
        $year = date('Y');
        $month = date('m');
        
        // Compter les membres créés ce mois
        $count = Membre::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count();
        
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return $prefix . $year . $month . $sequence;
    }

    /**
     * Obtenir l'évolution mensuelle des membres
     */
    private function getEvolutionMensuelle(): array
    {
        $evolution = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Membre::whereYear('created_at', $date->year)
                          ->whereMonth('created_at', $date->month)
                          ->count();
            
            $evolution[] = [
                'mois' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        return $evolution;
    }

    /**
     * Exporter vers PDF
     */
    private function exportToPdf(Collection $membres): string
    {
        // TODO: Implémenter l'export PDF avec DomPDF ou TCPDF
        $fileName = 'membres_' . date('Y-m-d_H-i-s') . '.pdf';
        $filePath = storage_path('app/exports/' . $fileName);
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        // Pour l'instant, créer un fichier temporaire
        file_put_contents($filePath, 'Export PDF des membres - À implémenter');
        
        return $filePath;
    }

    /**
     * Exporter vers Excel
     */
    private function exportToExcel(Collection $membres): string
    {
        // TODO: Implémenter l'export Excel avec Laravel Excel
        $fileName = 'membres_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/exports/' . $fileName);
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        // Pour l'instant, créer un fichier temporaire
        file_put_contents($filePath, 'Export Excel des membres - À implémenter');
        
        return $filePath;
    }
}
