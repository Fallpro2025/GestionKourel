<?php

namespace App\Http\Controllers;

use App\Models\ActivityRepetition;
use App\Models\Activite;
use App\Models\Membre;
use App\Models\PresenceRepetition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ActivityRepetitionController extends Controller
{
    /**
     * Afficher la liste des répétitions d'une activité
     */
    public function index(Activite $activite)
    {
        $repetitions = $activite->repetitions()
            ->with(['responsable', 'presences.membre'])
            ->orderBy('date_repetition', 'desc')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_repetitions' => $activite->repetitions()->count(),
            'repetitions_planifiees' => $activite->repetitions()->where('statut', 'planifie')->count(),
            'repetitions_en_cours' => $activite->repetitions()->where('statut', 'en_cours')->count(),
            'repetitions_terminees' => $activite->repetitions()->where('statut', 'termine')->count(),
            'repetitions_annulees' => $activite->repetitions()->where('statut', 'annule')->count(),
            'repetitions_cette_semaine' => $activite->repetitions()
                ->whereBetween('date_repetition', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        // Membres pour les formulaires
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();

        return view('activites.repetitions.index', compact('activite', 'repetitions', 'stats', 'membres'));
    }

    /**
     * Afficher le formulaire de création d'une répétition
     */
    public function create(Activite $activite)
    {
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        return view('activites.repetitions.create', compact('activite', 'membres'));
    }

    /**
     * Enregistrer une nouvelle répétition
     */
    public function store(Request $request, Activite $activite)
    {
        $validator = Validator::make($request->all(), [
            'date_repetition' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'lieu' => 'nullable|string|max:200',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
            'notes' => 'nullable|string',
            'responsable_id' => 'nullable|exists:membres,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $repetition = $activite->repetitions()->create([
                'date_repetition' => $request->date_repetition,
                'heure_debut' => $request->heure_debut,
                'heure_fin' => $request->heure_fin,
                'lieu' => $request->lieu,
                'statut' => $request->statut,
                'notes' => $request->notes,
                'responsable_id' => $request->responsable_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Répétition créée avec succès',
                'repetition' => $repetition
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur création répétition', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher une répétition spécifique
     */
    public function show(ActivityRepetition $repetition)
    {
        $repetition->load(['activite', 'responsable', 'presences.membre']);
        
        // Statistiques de présence
        $stats = $repetition->getStatistiquesPresence();

        return view('activites.repetitions.show', compact('repetition', 'stats'));
    }

    /**
     * Afficher le formulaire de modification d'une répétition
     */
    public function edit(ActivityRepetition $repetition)
    {
        $repetition->load(['activite', 'responsable', 'presences.membre']);
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        
        return view('activites.repetitions.edit', compact('repetition', 'membres'));
    }

    /**
     * Mettre à jour une répétition
     */
    public function update(Request $request, ActivityRepetition $repetition)
    {
        $validator = Validator::make($request->all(), [
            'date_repetition' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'lieu' => 'nullable|string|max:200',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
            'notes' => 'nullable|string',
            'responsable_id' => 'nullable|exists:membres,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $repetition->update([
                'date_repetition' => $request->date_repetition,
                'heure_debut' => $request->heure_debut,
                'heure_fin' => $request->heure_fin,
                'lieu' => $request->lieu,
                'statut' => $request->statut,
                'notes' => $request->notes,
                'responsable_id' => $request->responsable_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Répétition mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour répétition', [
                'error' => $e->getMessage(),
                'repetition_id' => $repetition->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une répétition
     */
    public function destroy(ActivityRepetition $repetition)
    {
        try {
            // Vérifier s'il y a des présences enregistrées
            $presencesExistantes = $repetition->presences()->exists();

            if ($presencesExistantes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette répétition car elle contient des présences enregistrées.'
                ], 422);
            }

            $repetition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Répétition supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression répétition', [
                'error' => $e->getMessage(),
                'repetition_id' => $repetition->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les présences d'une répétition
     */
    public function presences(ActivityRepetition $repetition)
    {
        $presences = $repetition->presences()
            ->with('membre')
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupérer tous les membres actifs
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();

        // Calculer les statistiques
        $stats = [
            'presents' => $presences->where('statut', 'present')->count(),
            'absents' => $presences->whereIn('statut', ['absent_justifie', 'absent_non_justifie'])->count(),
            'retards' => $presences->where('statut', 'retard')->count(),
            'taux_presence' => $membres->count() > 0 ? round(($presences->where('statut', 'present')->count() / $membres->count()) * 100) : 0,
        ];

        return view('activites.repetitions.presences', compact('repetition', 'presences', 'membres', 'stats'));
    }

    /**
     * Marquer la présence d'un membre pour une répétition
     */
    public function marquerPresence(Request $request, ActivityRepetition $repetition)
    {
        \Log::info('=== MARQUAGE PRÉSENCE RÉPÉTITION ===', [
            'repetition_id' => $repetition->id,
            'request_data' => $request->all(),
            'method' => $request->method(),
            'headers' => $request->headers->all()
        ]);

        $validator = Validator::make($request->all(), [
            'membre_id' => 'required|exists:membres,id',
            'statut' => 'required|in:present,absent_justifie,absent_non_justifie,retard',
            'justification' => 'nullable|string',
            'minutes_retard' => 'nullable|integer|min:0|max:1440',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'prestation_effectuee' => 'nullable|in:true,false,1,0,"true","false"',
            'notes_prestation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            \Log::error('Erreurs de validation marquage présence', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all(),
                'repetition_id' => $repetition->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Convertir prestation_effectuee en booléen
            $prestationEffectuee = false;
            if ($request->has('prestation_effectuee')) {
                $prestationEffectuee = filter_var($request->prestation_effectuee, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($prestationEffectuee === null) {
                    $prestationEffectuee = false;
                }
            }

            // Vérifier si la présence existe déjà
            $presenceExistante = PresenceRepetition::where('membre_id', $request->membre_id)
                ->where('repetition_id', $repetition->id)
                ->first();

            // Préparer les données de présence
            $dataPresence = [
                    'statut' => $request->statut,
                    'justification' => $request->justification,
                    'minutes_retard' => $request->minutes_retard ?? 0,
                    'prestation_effectuee' => $prestationEffectuee,
                    'notes_prestation' => $request->notes_prestation,
            ];

            // Gérer l'heure d'arrivée et calculer automatiquement les minutes de retard
            if ($request->has('heure_arrivee') && $request->heure_arrivee) {
                // Utiliser l'heure fournie par l'utilisateur
                $dateRepetition = $repetition->date_debut ? $repetition->date_debut->format('Y-m-d') : now()->format('Y-m-d');
                $heureArrivee = $dateRepetition . ' ' . $request->heure_arrivee . ':00';
                $dataPresence['heure_arrivee'] = $heureArrivee;
                
                // Calculer automatiquement les minutes de retard par rapport à l'heure de début de l'ACTIVITÉ
                if ($request->statut === 'retard') {
                    // Utiliser l'heure de début de l'activité parente
                    $activite = $repetition->activite;
                    
                    if ($activite && $activite->date_debut) {
                        // Extraire seulement l'heure de l'activité (sans la date)
                        $heureDebutActivite = $activite->date_debut->format('H:i');
                        $heureDebutComplete = $dateRepetition . ' ' . $heureDebutActivite . ':00';
                        
                        $datetimeDebut = \Carbon\Carbon::parse($heureDebutComplete);
                        $datetimeArrivee = \Carbon\Carbon::parse($heureArrivee);
                        
                        // Calculer la différence en minutes
                        $minutesRetard = $datetimeArrivee->diffInMinutes($datetimeDebut, false);
                        
                        \Log::info('=== CALCUL RETARD PAR RAPPORT À L\'ACTIVITÉ ===', [
                            'activite_id' => $activite->id,
                            'activite_nom' => $activite->nom,
                            'heure_debut_activite' => $heureDebutActivite,
                            'heure_debut_complete' => $heureDebutComplete,
                            'heure_arrivee' => $heureArrivee,
                            'datetime_debut' => $datetimeDebut->toDateTimeString(),
                            'datetime_arrivee' => $datetimeArrivee->toDateTimeString(),
                            'minutes_retard_calculees' => $minutesRetard,
                            'formule' => 'heure_arrivee - heure_debut = ' . $minutesRetard . ' minutes'
                        ]);
                        
                        // Enregistrer les minutes de retard (même si 0)
                        $dataPresence['minutes_retard'] = max(0, $minutesRetard);
                        
                        \Log::info('Minutes de retard enregistrées', [
                            'minutes' => $dataPresence['minutes_retard'],
                            'retard_positif' => $minutesRetard > 0 ? 'OUI' : 'NON'
                        ]);
                    } else {
                        \Log::warning('Impossible de calculer le retard - Activité ou heure de début manquante', [
                            'activite_existe' => $activite ? 'OUI' : 'NON',
                            'date_debut_existe' => $activite && $activite->date_debut ? 'OUI' : 'NON'
                        ]);
                        $dataPresence['minutes_retard'] = 0;
                    }
                }
            } elseif ($request->statut === 'present' || $request->statut === 'retard') {
                // Utiliser l'heure actuelle si pas d'heure spécifiée
                $dataPresence['heure_arrivee'] = now();
                
                // Calculer les minutes de retard avec l'heure actuelle par rapport à l'ACTIVITÉ
                if ($request->statut === 'retard') {
                    $activite = $repetition->activite;
                    
                    if ($activite && $activite->date_debut) {
                        $dateRepetition = $repetition->date_debut ? $repetition->date_debut->format('Y-m-d') : now()->format('Y-m-d');
                        $heureDebutActivite = $activite->date_debut->format('H:i');
                        $heureDebutComplete = $dateRepetition . ' ' . $heureDebutActivite . ':00';
                        
                        $datetimeDebut = \Carbon\Carbon::parse($heureDebutComplete);
                        $datetimeArrivee = now();
                        
                        $minutesRetard = $datetimeArrivee->diffInMinutes($datetimeDebut, false);
                        $dataPresence['minutes_retard'] = max(0, $minutesRetard);
                        
                        \Log::info('Calcul retard avec heure actuelle', [
                            'heure_debut_activite' => $heureDebutActivite,
                            'minutes_retard' => $dataPresence['minutes_retard']
                        ]);
                    } else {
                        $dataPresence['minutes_retard'] = 0;
                    }
                }
            } else {
                $dataPresence['heure_arrivee'] = null;
            }

            if ($presenceExistante) {
                // Mettre à jour la présence existante
                $presenceExistante->update($dataPresence);
            } else {
                // Créer une nouvelle présence
                $dataPresence['membre_id'] = $request->membre_id;
                $dataPresence['repetition_id'] = $repetition->id;
                PresenceRepetition::create($dataPresence);
            }

            // Récupérer la présence mise à jour avec les relations
            $presenceMiseAJour = PresenceRepetition::where('membre_id', $request->membre_id)
                ->where('repetition_id', $repetition->id)
                ->with('membre')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Présence enregistrée avec succès',
                'presence' => $presenceMiseAJour
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur enregistrement présence répétition', [
                'error' => $e->getMessage(),
                'repetition_id' => $repetition->id,
                'membre_id' => $request->membre_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer des répétitions automatiques pour une activité
     */
    public function genererRepetitions(Request $request, Activite $activite)
    {
        \Log::info('=== GÉNÉRATION RÉPÉTITIONS ===', [
            'activite_id' => $activite->id,
            'request_data' => $request->all(),
            'jours_semaine' => $request->jours_semaine,
            'horaires_par_jour' => $request->horaires_par_jour
        ]);

        $validator = Validator::make($request->all(), [
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'jours_semaine' => 'required|array|min:1',
            'jours_semaine.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'horaires_par_jour' => 'required|string',
            'lieu' => 'nullable|string|max:200',
            'responsable_id' => 'nullable|exists:membres,id',
        ]);

        if ($validator->fails()) {
            \Log::error('Erreurs de validation génération répétitions', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = Carbon::parse($request->date_fin);
            $joursSemaine = $request->jours_semaine;
            $horairesParJour = json_decode($request->horaires_par_jour, true);
            $repetitionsCreees = 0;

            // Mapper les jours de la semaine
            $joursMap = [
                'lundi' => 1,
                'mardi' => 2,
                'mercredi' => 3,
                'jeudi' => 4,
                'vendredi' => 5,
                'samedi' => 6,
                'dimanche' => 0,
            ];

            // Générer les répétitions pour chaque jour de la semaine spécifié
            $currentDate = $dateDebut->copy();
            while ($currentDate->lte($dateFin)) {
                $jourSemaine = $currentDate->locale('fr')->dayName;
                $jourSemaineLower = strtolower($jourSemaine);
                
                // Vérifier si ce jour est sélectionné et a des horaires définis
                if (in_array($jourSemaineLower, $joursSemaine) && isset($horairesParJour[$jourSemaineLower])) {
                    $horaires = $horairesParJour[$jourSemaineLower];
                    
                    // Vérifier si une répétition existe déjà pour cette date
                    $repetitionExistante = $activite->repetitions()
                        ->where('date_repetition', $currentDate->toDateString())
                        ->first();

                    if (!$repetitionExistante) {
                        $activite->repetitions()->create([
                            'date_repetition' => $currentDate->toDateString(),
                            'heure_debut' => $horaires['debut'],
                            'heure_fin' => $horaires['fin'],
                            'lieu' => $request->lieu,
                            'statut' => 'planifie',
                            'responsable_id' => $request->responsable_id,
                        ]);
                        $repetitionsCreees++;
                    }
                }
                $currentDate->addDay();
            }

            return response()->json([
                'success' => true,
                'message' => "{$repetitionsCreees} répétitions générées avec succès",
                'repetitions_creees' => $repetitionsCreees
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur génération répétitions', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques d'une répétition
     */
    public function statistiques(ActivityRepetition $repetition)
    {
        $stats = $repetition->getStatistiquesPresence();

        return response()->json($stats);
    }
}
