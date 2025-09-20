<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMembreRequest;
use App\Http\Requests\UpdateMembreRequest;
use App\Http\Resources\MembreResource;
use App\Models\Membre;
use App\Services\MembreService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MembreController extends Controller
{
    protected MembreService $membreService;

    public function __construct(MembreService $membreService)
    {
        $this->membreService = $membreService;
    }

    /**
     * Afficher la liste des membres avec pagination et filtres
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $membres = $this->membreService->getMembresPaginated($request->all());
            
            return response()->json([
                'success' => true,
                'data' => MembreResource::collection($membres->items()),
                'pagination' => [
                    'current_page' => $membres->currentPage(),
                    'last_page' => $membres->lastPage(),
                    'per_page' => $membres->perPage(),
                    'total' => $membres->total(),
                    'from' => $membres->firstItem(),
                    'to' => $membres->lastItem(),
                ],
                'filters' => $request->only(['search', 'role', 'statut', 'sort']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des membres',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer un nouveau membre
     */
    public function store(StoreMembreRequest $request): JsonResponse
    {
        try {
            $membre = $this->membreService->createMembre($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Membre créé avec succès',
                'data' => new MembreResource($membre)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du membre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un membre spécifique
     */
    public function show(Membre $membre): JsonResponse
    {
        try {
            $membreWithRelations = $this->membreService->getMembreWithRelations($membre->id);
            
            return response()->json([
                'success' => true,
                'data' => new MembreResource($membreWithRelations)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du membre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un membre
     */
    public function update(UpdateMembreRequest $request, Membre $membre): JsonResponse
    {
        try {
            $updatedMembre = $this->membreService->updateMembre($membre->id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Membre mis à jour avec succès',
                'data' => new MembreResource($updatedMembre)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du membre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un membre
     */
    public function destroy(Membre $membre): JsonResponse
    {
        try {
            $this->membreService->deleteMembre($membre->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Membre supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du membre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des membres
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->membreService->getMembreStatistics();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechercher des membres
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $membres = $this->membreService->searchMembres($query);
            
            return response()->json([
                'success' => true,
                'data' => MembreResource::collection($membres)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les membres en PDF/Excel
     */
    public function export(Request $request): JsonResponse
    {
        try {
            $format = $request->get('format', 'pdf');
            $filePath = $this->membreService->exportMembres($format);
            
            return response()->json([
                'success' => true,
                'message' => 'Export généré avec succès',
                'download_url' => url('storage/exports/' . basename($filePath))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
