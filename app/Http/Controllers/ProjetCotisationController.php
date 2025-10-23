<?php

namespace App\Http\Controllers;

use App\Models\ProjetCotisation;
use App\Models\AssignationCotisation;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjetCotisationController extends Controller
{
    /**
     * Afficher la liste des projets de cotisation
     */
    public function index()
    {
        $projets = ProjetCotisation::with(['assignations.membre', 'createur'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_projets' => ProjetCotisation::count(),
            'projets_actifs' => ProjetCotisation::where('statut', 'actif')->count(),
            'projets_termines' => ProjetCotisation::where('statut', 'termine')->count(),
            'montant_total_collecte' => ProjetCotisation::sum('montant_collecte'),
            'montant_total_cible' => ProjetCotisation::sum('montant_total'),
            'taux_recouvrement' => $this->calculerTauxRecouvrement(),
            'assignations_en_retard' => AssignationCotisation::enRetard()->count(),
        ];

        return view('cotisations.index', compact('projets', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        return view('cotisations.create', compact('membres'));
    }

    /**
     * Enregistrer un nouveau projet
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string',
            'montant_total' => 'required|numeric|min:0',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type_cotisation' => 'required|in:obligatoire,volontaire,evenement',
            'membres' => 'required|array|min:1',
            'membres.*' => 'exists:membres,id',
            'montants' => 'required|array',
            'montants.*' => 'required|numeric|min:0.01',
        ], [
            'nom.required' => 'Le nom du projet est requis.',
            'montant_total.required' => 'Le montant total est requis.',
            'montant_total.numeric' => 'Le montant total doit être un nombre.',
            'montant_total.min' => 'Le montant total doit être positif.',
            'date_debut.required' => 'La date de début est requise.',
            'date_fin.required' => 'La date de fin est requise.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            'type_cotisation.required' => 'Le type de cotisation est requis.',
            'membres.required' => 'Au moins un membre doit être sélectionné.',
            'membres.array' => 'Les membres doivent être sélectionnés.',
            'membres.min' => 'Au moins un membre doit être sélectionné.',
            'montants.required' => 'Les montants par membre sont requis.',
            'montants.array' => 'Les montants doivent être spécifiés.',
            'montants.*.required' => 'Chaque membre doit avoir un montant.',
            'montants.*.numeric' => 'Le montant doit être un nombre.',
            'montants.*.min' => 'Le montant doit être positif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Créer le projet
            $projet = ProjetCotisation::create([
                'nom' => $request->nom,
                'description' => $request->description,
                'montant_total' => $request->montant_total,
                'montant_collecte' => 0,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'statut' => 'actif',
                'type_cotisation' => $request->type_cotisation,
                'created_by' => auth()->id() ?? 1, // TODO: Gérer l'authentification
            ]);

            // Créer les assignations avec montants individuels
            foreach ($request->membres as $membreId) {
                $montantAssigné = $request->montants[$membreId] ?? 0;
                
                AssignationCotisation::create([
                    'membre_id' => $membreId,
                    'projet_id' => $projet->id,
                    'montant_assigné' => $montantAssigné,
                    'montant_payé' => 0,
                    'statut_paiement' => 'non_paye',
                    'date_echeance' => $request->date_fin,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Projet de cotisation créé avec succès',
                'projet_id' => $projet->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur création projet cotisation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du projet: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un projet spécifique
     */
    public function show(ProjetCotisation $cotisation)
    {
        // Renommer pour cohérence avec les autres méthodes
        $projet = $cotisation;
        $projet->load(['assignations.membre', 'createur']);
        
        // Statistiques du projet
        $stats = [
            'total_assignations' => $projet->assignations->count(),
            'assignations_payees' => $projet->assignations->where('statut_paiement', 'paye')->count(),
            'assignations_partiel' => $projet->assignations->where('statut_paiement', 'partiel')->count(),
            'assignations_non_payees' => $projet->assignations->where('statut_paiement', 'non_paye')->count(),
            'assignations_en_retard' => $projet->assignations->where('en_retard', true)->count(),
            'taux_recouvrement' => $projet->pourcentage_collecte,
            'montant_restant' => $projet->montant_restant,
        ];

        return view('cotisations.show', compact('projet', 'stats'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(ProjetCotisation $cotisation)
    {
        // Renommer pour cohérence avec les autres méthodes
        $projet = $cotisation;
        $projet->load(['assignations.membre']);
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        
        return view('cotisations.edit', compact('projet', 'membres'));
    }

    /**
     * Mettre à jour un projet
     */
    public function update(Request $request, ProjetCotisation $cotisation)
    {
        // Renommer pour cohérence avec les autres méthodes
        $projet = $cotisation;
        
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string',
            'montant_total' => 'required|numeric|min:0',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type_cotisation' => 'required|in:obligatoire,volontaire,evenement',
            'statut' => 'required|in:planifie,actif,suspendu,termine,annule',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $projet->update([
                'nom' => $request->nom,
                'description' => $request->description,
                'montant_total' => $request->montant_total,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'type_cotisation' => $request->type_cotisation,
                'statut' => $request->statut,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Projet mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour projet cotisation', [
                'error' => $e->getMessage(),
                'projet_id' => $projet->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un projet
     */
    public function destroy(ProjetCotisation $cotisation)
    {
        // Renommer pour cohérence avec les autres méthodes
        $projet = $cotisation;
        
        try {
            // Vérifier s'il y a des paiements
            $paiementsExistants = $projet->assignations()
                ->where('montant_payé', '>', 0)
                ->exists();

            if ($paiementsExistants) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce projet car il contient des paiements'
                ], 400);
            }

            $projet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Projet supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression projet cotisation', [
                'error' => $e->getMessage(),
                'projet_id' => $projet->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer le taux de recouvrement global
     */
    private function calculerTauxRecouvrement(): float
    {
        $montantTotal = ProjetCotisation::sum('montant_total');
        $montantCollecte = ProjetCotisation::sum('montant_collecte');

        if ($montantTotal == 0) {
            return 0.0;
        }

        return round(($montantCollecte / $montantTotal) * 100, 2);
    }

    /**
     * Obtenir les statistiques pour l'API
     */
    public function statistiques()
    {
        $stats = [
            'total_projets' => ProjetCotisation::count(),
            'projets_actifs' => ProjetCotisation::where('statut', 'actif')->count(),
            'projets_termines' => ProjetCotisation::where('statut', 'termine')->count(),
            'montant_total_collecte' => ProjetCotisation::sum('montant_collecte'),
            'montant_total_cible' => ProjetCotisation::sum('montant_total'),
            'taux_recouvrement' => $this->calculerTauxRecouvrement(),
            'assignations_en_retard' => AssignationCotisation::enRetard()->count(),
            'projets_par_type' => ProjetCotisation::select('type_cotisation', DB::raw('count(*) as count'))
                ->groupBy('type_cotisation')
                ->get(),
            'projets_par_statut' => ProjetCotisation::select('statut', DB::raw('count(*) as count'))
                ->groupBy('statut')
                ->get(),
        ];

        return response()->json($stats);
    }
}
