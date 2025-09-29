<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvenementController extends Controller
{
    /**
     * Afficher la liste des événements
     */
    public function index()
    {
        $evenements = Evenement::with(['createur'])
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_evenements' => Evenement::count(),
            'evenements_planifies' => Evenement::where('statut', 'planifie')->count(),
            'evenements_en_cours' => Evenement::where('statut', 'en_cours')->count(),
            'evenements_termines' => Evenement::where('statut', 'termine')->count(),
            'evenements_annules' => Evenement::where('statut', 'annule')->count(),
            'evenements_cette_semaine' => Evenement::whereBetween('date_debut', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'budget_total' => Evenement::sum('budget'),
            'evenements_majeurs' => Evenement::majeurs()->count(),
        ];

        return view('evenements.index', compact('evenements', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        return view('evenements.create', compact('membres'));
    }

    /**
     * Enregistrer un nouvel événement
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:200',
            'type' => 'required|in:magal,gamou,promokhane,conference,formation,autre',
            'date_debut' => 'required|date|after:now',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
            'membres_selectionnes' => 'nullable|array',
            'configuration' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $evenement = Evenement::create([
                'nom' => $request->nom,
                'type' => $request->type,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'lieu' => $request->lieu,
                'description' => $request->description,
                'budget' => $request->budget,
                'statut' => $request->statut,
                'membres_selectionnes' => $request->membres_selectionnes,
                'configuration' => $request->configuration,
                'created_by' => auth()->id() ?? 1, // TODO: Remplacer par l'ID de l'utilisateur connecté
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Événement créé avec succès',
                'evenement' => $evenement
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur création événement', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un événement spécifique
     */
    public function show(Evenement $evenement)
    {
        $evenement->load(['createur']);
        
        // Récupérer les membres sélectionnés avec leurs détails
        $membresSelectionnes = [];
        if ($evenement->membres_selectionnes) {
            foreach ($evenement->membres_selectionnes as $prestation => $membreIds) {
                $membres = Membre::whereIn('id', $membreIds)->get();
                $membresSelectionnes[$prestation] = $membres;
            }
        }

        // Statistiques
        $stats = [
            'nombre_prestations' => count($evenement->membres_selectionnes ?? []),
            'nombre_total_membres' => $evenement->getNombreTotalMembresSelectionnes(),
            'duree_heures' => $evenement->duree_heures,
            'budget' => $evenement->budget,
            'statut' => $evenement->statut_francais,
            'type' => $evenement->type_francais,
        ];

        return view('evenements.show', compact('evenement', 'membresSelectionnes', 'stats'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Evenement $evenement)
    {
        $evenement->load(['createur']);
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        
        return view('evenements.edit', compact('evenement', 'membres'));
    }

    /**
     * Mettre à jour un événement
     */
    public function update(Request $request, Evenement $evenement)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:200',
            'type' => 'required|in:magal,gamou,promokhane,conference,formation,autre',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
            'membres_selectionnes' => 'nullable|array',
            'configuration' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $evenement->update([
                'nom' => $request->nom,
                'type' => $request->type,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'lieu' => $request->lieu,
                'description' => $request->description,
                'budget' => $request->budget,
                'statut' => $request->statut,
                'membres_selectionnes' => $request->membres_selectionnes,
                'configuration' => $request->configuration,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Événement mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour événement', [
                'error' => $e->getMessage(),
                'evenement_id' => $evenement->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un événement
     */
    public function destroy(Evenement $evenement)
    {
        try {
            // Vérifier s'il y a des alertes liées
            $alertesExistantes = $evenement->alertes()->exists();

            if ($alertesExistantes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cet événement car il contient des alertes associées.'
                ], 422);
            }

            $evenement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Événement supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression événement', [
                'error' => $e->getMessage(),
                'evenement_id' => $evenement->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les participants d'un événement
     */
    public function participants(Evenement $evenement)
    {
        $membresSelectionnes = [];
        if ($evenement->membres_selectionnes) {
            foreach ($evenement->membres_selectionnes as $prestation => $membreIds) {
                $membres = Membre::whereIn('id', $membreIds)->get();
                $membresSelectionnes[$prestation] = $membres;
            }
        }

        return view('evenements.participants', compact('evenement', 'membresSelectionnes'));
    }

    /**
     * Ajouter un participant à une prestation
     */
    public function ajouterParticipant(Request $request, Evenement $evenement)
    {
        $validator = Validator::make($request->all(), [
            'membre_id' => 'required|exists:membres,id',
            'prestation' => 'required|string|in:declamation,chorale,animation,organisation,logistique',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $evenement->ajouterMembrePrestation($request->membre_id, $request->prestation);

            return response()->json([
                'success' => true,
                'message' => 'Participant ajouté avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur ajout participant', [
                'error' => $e->getMessage(),
                'evenement_id' => $evenement->id,
                'membre_id' => $request->membre_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retirer un participant d'une prestation
     */
    public function retirerParticipant(Request $request, Evenement $evenement)
    {
        $validator = Validator::make($request->all(), [
            'membre_id' => 'required|exists:membres,id',
            'prestation' => 'required|string|in:declamation,chorale,animation,organisation,logistique',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $evenement->retirerMembrePrestation($request->membre_id, $request->prestation);

            return response()->json([
                'success' => true,
                'message' => 'Participant retiré avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur retrait participant', [
                'error' => $e->getMessage(),
                'evenement_id' => $evenement->id,
                'membre_id' => $request->membre_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du retrait: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques d'un événement
     */
    public function statistiques(Evenement $evenement)
    {
        $stats = $evenement->getStatistiques();
        
        // Ajouter des statistiques supplémentaires
        $stats['prestations_detaillees'] = [];
        if ($evenement->membres_selectionnes) {
            foreach ($evenement->membres_selectionnes as $prestation => $membreIds) {
                $membres = Membre::whereIn('id', $membreIds)->get();
                $stats['prestations_detaillees'][$prestation] = [
                    'nombre_membres' => count($membreIds),
                    'membres' => $membres->map(function($membre) {
                        return [
                            'id' => $membre->id,
                            'nom' => $membre->nom,
                            'prenom' => $membre->prenom,
                            'email' => $membre->email
                        ];
                    })
                ];
            }
        }

        return response()->json($stats);
    }
}
