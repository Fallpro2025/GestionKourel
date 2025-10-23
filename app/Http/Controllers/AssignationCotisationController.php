<?php

namespace App\Http\Controllers;

use App\Models\AssignationCotisation;
use App\Models\ProjetCotisation;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignationCotisationController extends Controller
{
    /**
     * Afficher les assignations d'un projet
     */
    public function index(ProjetCotisation $projet)
    {
        $assignations = $projet->assignations()
            ->with('membre')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('cotisations.assignations', compact('projet', 'assignations'));
    }

    /**
     * Afficher le formulaire d'ajout d'assignation
     */
    public function create(ProjetCotisation $projet)
    {
        // Membres non encore assignés à ce projet
        $membresAssignes = $projet->assignations()->pluck('membre_id')->toArray();
        $membres = Membre::where('statut', 'actif')
            ->whereNotIn('id', $membresAssignes)
            ->orderBy('nom')
            ->get();

        return view('cotisations.assignation-create', compact('projet', 'membres'));
    }

    /**
     * Enregistrer une nouvelle assignation
     */
    public function store(Request $request, ProjetCotisation $projet)
    {
        $validator = Validator::make($request->all(), [
            'membre_id' => 'required|exists:membres,id',
            'montant_assigné' => 'required|numeric|min:0',
            'date_echeance' => 'required|date|after_or_equal:today',
        ], [
            'membre_id.required' => 'Le membre est requis.',
            'membre_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'montant_assigné.required' => 'Le montant assigné est requis.',
            'montant_assigné.numeric' => 'Le montant assigné doit être un nombre.',
            'montant_assigné.min' => 'Le montant assigné doit être positif.',
            'date_echeance.required' => 'La date d\'échéance est requise.',
            'date_echeance.date' => 'La date d\'échéance doit être une date valide.',
            'date_echeance.after_or_equal' => 'La date d\'échéance doit être aujourd\'hui ou plus tard.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérifier si le membre n'est pas déjà assigné
            $assignationExistante = $projet->assignations()
                ->where('membre_id', $request->membre_id)
                ->exists();

            if ($assignationExistante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce membre est déjà assigné à ce projet'
                ], 400);
            }

            $assignation = AssignationCotisation::create([
                'membre_id' => $request->membre_id,
                'projet_id' => $projet->id,
                'montant_assigné' => $request->montant_assigné,
                'montant_payé' => 0,
                'statut_paiement' => 'non_paye',
                'date_echeance' => $request->date_echeance,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignation créée avec succès',
                'assignation_id' => $assignation->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur création assignation', [
                'error' => $e->getMessage(),
                'projet_id' => $projet->id,
                'membre_id' => $request->membre_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'assignation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher une assignation spécifique
     */
    public function show(AssignationCotisation $assignation)
    {
        $assignation->load(['membre', 'projet']);
        return view('cotisations.assignation-show', compact('assignation'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(AssignationCotisation $assignation)
    {
        $assignation->load(['membre', 'projet']);
        return view('cotisations.assignation-edit', compact('assignation'));
    }

    /**
     * Mettre à jour une assignation
     */
    public function update(Request $request, AssignationCotisation $assignation)
    {
        $validator = Validator::make($request->all(), [
            'montant_assigné' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérifier que le nouveau montant n'est pas inférieur au montant déjà payé
            if ($request->montant_assigné < $assignation->montant_payé) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le montant assigné ne peut pas être inférieur au montant déjà payé'
                ], 400);
            }

            $assignation->update([
                'montant_assigné' => $request->montant_assigné,
                'date_echeance' => $request->date_echeance,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignation mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour assignation', [
                'error' => $e->getMessage(),
                'assignation_id' => $assignation->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une assignation
     */
    public function destroy(AssignationCotisation $assignation)
    {
        try {
            // Vérifier s'il y a des paiements
            if ($assignation->montant_payé > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette assignation car elle contient des paiements'
                ], 400);
            }

            $assignation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assignation supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression assignation', [
                'error' => $e->getMessage(),
                'assignation_id' => $assignation->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistrer un paiement
     */
    public function enregistrerPaiement(Request $request, AssignationCotisation $assignation)
    {
        $validator = Validator::make($request->all(), [
            'montant' => 'required|numeric|min:0.01',
            'methode' => 'required|string|in:espèces,virement,chèque,mobile_money',
            'notes' => 'nullable|string|max:500',
        ], [
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'methode.required' => 'La méthode de paiement est requise.',
            'methode.in' => 'Méthode de paiement invalide.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérifier que le montant ne dépasse pas le montant restant
            $montantRestant = $assignation->montant_assigné - $assignation->montant_payé;
            if ($request->montant > $montantRestant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le montant dépasse le montant restant à payer'
                ], 400);
            }

            DB::beginTransaction();

            // Enregistrer le paiement
            $assignation->enregistrerPaiement($request->montant, $request->methode);

            // Ajouter des notes si fournies
            if ($request->notes) {
                $historique = $assignation->historique_paiements ?? [];
                $dernierPaiement = end($historique);
                if ($dernierPaiement) {
                    $dernierPaiement['notes'] = $request->notes;
                    $historique[count($historique) - 1] = $dernierPaiement;
                    $assignation->update(['historique_paiements' => $historique]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès',
                'nouveau_statut' => $assignation->fresh()->statut_paiement,
                'montant_restant' => $assignation->fresh()->montant_restant
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur enregistrement paiement', [
                'error' => $e->getMessage(),
                'assignation_id' => $assignation->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du paiement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir l'historique des paiements
     */
    public function historiquePaiements(AssignationCotisation $assignation)
    {
        $historique = $assignation->historique_paiements ?? [];
        
        return response()->json([
            'success' => true,
            'historique' => $historique,
            'total_paye' => $assignation->montant_payé,
            'montant_restant' => $assignation->montant_restant,
            'statut' => $assignation->statut_paiement
        ]);
    }

    /**
     * Marquer comme payé en totalité
     */
    public function marquerPaye(AssignationCotisation $assignation)
    {
        try {
            if ($assignation->statut_paiement === 'paye') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette assignation est déjà marquée comme payée'
                ], 400);
            }

            $montantRestant = $assignation->montant_assigné - $assignation->montant_payé;
            
            if ($montantRestant > 0) {
                $assignation->enregistrerPaiement($montantRestant, 'espèces');
            }

            return response()->json([
                'success' => true,
                'message' => 'Assignation marquée comme payée avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur marquage payé', [
                'error' => $e->getMessage(),
                'assignation_id' => $assignation->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage: ' . $e->getMessage()
            ], 500);
        }
    }
}
