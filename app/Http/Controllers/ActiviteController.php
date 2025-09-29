<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Membre;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActiviteController extends Controller
{
    /**
     * Afficher la liste des activités
     */
    public function index()
    {
        $activites = Activite::with(['responsable', 'presences'])
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_activites' => Activite::count(),
            'activites_planifiees' => Activite::where('statut', 'planifie')->count(),
            'activites_en_cours' => Activite::where('statut', 'en_cours')->count(),
            'activites_terminees' => Activite::where('statut', 'termine')->count(),
            'activites_annulees' => Activite::where('statut', 'annule')->count(),
            'activites_cette_semaine' => Activite::whereBetween('date_debut', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'taux_presence_moyen' => $this->calculerTauxPresenceMoyen(),
        ];

        return view('activites.index', compact('activites', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        return view('activites.create', compact('membres'));
    }

    /**
     * Enregistrer une nouvelle activité
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:repetition,prestation,goudi_aldiouma,formation,reunion',
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date_debut' => 'required|date|after:now',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:200',
            'responsable_id' => 'nullable|exists:membres,id',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
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
            $activite = Activite::create([
                'type' => $request->type,
                'nom' => $request->nom,
                'description' => $request->description,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'lieu' => $request->lieu,
                'responsable_id' => $request->responsable_id,
                'statut' => $request->statut,
                'configuration' => $request->configuration,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activité créée avec succès',
                'activite' => $activite
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur création activité', [
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
     * Afficher une activité spécifique
     */
    public function show(Activite $activite)
    {
        $activite->load(['responsable', 'presences.membre']);
        
        // Statistiques de présence
        $stats = [
            'total_presences' => $activite->presences->count(),
            'presents' => $activite->presences->where('statut', 'present')->count(),
            'absents_justifies' => $activite->presences->where('statut', 'absent_justifie')->count(),
            'absents_non_justifies' => $activite->presences->where('statut', 'absent_non_justifie')->count(),
            'retards' => $activite->presences->where('statut', 'retard')->count(),
            'taux_presence' => $this->calculerTauxPresenceActivite($activite),
        ];

        return view('activites.show', compact('activite', 'stats'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Activite $activite)
    {
        $activite->load(['responsable', 'presences.membre']);
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        
        return view('activites.edit', compact('activite', 'membres'));
    }

    /**
     * Mettre à jour une activité
     */
    public function update(Request $request, Activite $activite)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:repetition,prestation,goudi_aldiouma,formation,reunion',
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'lieu' => 'nullable|string|max:200',
            'responsable_id' => 'nullable|exists:membres,id',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
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
            $activite->update([
                'type' => $request->type,
                'nom' => $request->nom,
                'description' => $request->description,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'lieu' => $request->lieu,
                'responsable_id' => $request->responsable_id,
                'statut' => $request->statut,
                'configuration' => $request->configuration,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activité mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour activité', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une activité
     */
    public function destroy(Activite $activite)
    {
        try {
            // Vérifier s'il y a des présences enregistrées
            $presencesExistantes = $activite->presences()->exists();

            if ($presencesExistantes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette activité car elle contient des présences enregistrées.'
                ], 422);
            }

            $activite->delete();

            return response()->json([
                'success' => true,
                'message' => 'Activité supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression activité', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les présences d'une activité
     */
    public function presences(Activite $activite)
    {
        $presences = $activite->presences()
            ->with('membre')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('activites.presences', compact('activite', 'presences'));
    }

    /**
     * Marquer la présence d'un membre pour une activité
     */
    public function marquerPresence(Request $request, Activite $activite)
    {
        $validator = Validator::make($request->all(), [
            'membre_id' => 'required|exists:membres,id',
            'statut' => 'required|in:present,absent_justifie,absent_non_justifie,retard',
            'justification' => 'nullable|string',
            'minutes_retard' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérifier si la présence existe déjà
            $presenceExistante = Presence::where('membre_id', $request->membre_id)
                ->where('activite_id', $activite->id)
                ->first();

            if ($presenceExistante) {
                // Mettre à jour la présence existante
                $presenceExistante->update([
                    'statut' => $request->statut,
                    'justification' => $request->justification,
                    'minutes_retard' => $request->minutes_retard,
                    'heure_arrivee' => $request->statut === 'present' || $request->statut === 'retard' ? now() : null,
                ]);
            } else {
                // Créer une nouvelle présence
                Presence::create([
                    'membre_id' => $request->membre_id,
                    'activite_id' => $activite->id,
                    'statut' => $request->statut,
                    'justification' => $request->justification,
                    'minutes_retard' => $request->minutes_retard,
                    'heure_arrivee' => $request->statut === 'present' || $request->statut === 'retard' ? now() : null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Présence enregistrée avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur enregistrement présence', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id,
                'membre_id' => $request->membre_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques d'une activité
     */
    public function statistiques(Activite $activite)
    {
        $stats = [
            'total_presences' => $activite->presences->count(),
            'presents' => $activite->presences->where('statut', 'present')->count(),
            'absents_justifies' => $activite->presences->where('statut', 'absent_justifie')->count(),
            'absents_non_justifies' => $activite->presences->where('statut', 'absent_non_justifie')->count(),
            'retards' => $activite->presences->where('statut', 'retard')->count(),
            'taux_presence' => $this->calculerTauxPresenceActivite($activite),
            'membres_les_plus_presents' => $this->getMembresLesPlusPresents($activite),
            'evolution_presence' => $this->getEvolutionPresence($activite),
        ];

        return response()->json($stats);
    }

    /**
     * Calculer le taux de présence moyen
     */
    private function calculerTauxPresenceMoyen()
    {
        $activites = Activite::where('statut', 'termine')->get();
        
        if ($activites->isEmpty()) {
            return 0;
        }

        $totalTaux = 0;
        foreach ($activites as $activite) {
            $totalTaux += $this->calculerTauxPresenceActivite($activite);
        }

        return round($totalTaux / $activites->count(), 2);
    }

    /**
     * Calculer le taux de présence pour une activité
     */
    private function calculerTauxPresenceActivite(Activite $activite)
    {
        $totalPresences = $activite->presences->count();
        
        if ($totalPresences === 0) {
            return 0;
        }

        $presents = $activite->presences->where('statut', 'present')->count();
        return round(($presents / $totalPresences) * 100, 2);
    }

    /**
     * Obtenir les membres les plus présents
     */
    private function getMembresLesPlusPresents(Activite $activite)
    {
        return $activite->presences()
            ->where('statut', 'present')
            ->with('membre')
            ->get()
            ->groupBy('membre_id')
            ->map(function ($presences) {
                return [
                    'membre' => $presences->first()->membre,
                    'count' => $presences->count()
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();
    }

    /**
     * Obtenir l'évolution de la présence
     */
    private function getEvolutionPresence(Activite $activite)
    {
        // Logique pour calculer l'évolution de la présence
        // Peut être étendue selon les besoins
        return [
            'tendance' => 'stable',
            'variation' => 0
        ];
    }
}
