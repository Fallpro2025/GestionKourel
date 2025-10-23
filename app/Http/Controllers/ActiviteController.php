<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Membre;
use App\Models\Presence;
use Carbon\Carbon;
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
        \Log::info('Tentative de création d\'activité', [
            'data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        // Règles de validation de base
        $rules = [
            'type' => 'required|in:repetition,prestation,goudi_aldiouma,formation,reunion',
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string',
            'lieu' => 'nullable|string|max:200',
            'responsable_id' => 'nullable|exists:membres,id',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
            'configuration' => 'nullable|array',
        ];

        // Ajouter les règles de dates seulement si elles sont présentes
        if ($request->has('date_debut') && $request->date_debut) {
            $rules['date_debut'] = 'required|date|after:now';
        }
        
        if ($request->has('date_fin') && $request->date_fin) {
            $rules['date_fin'] = 'required|date|after:date_debut';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Log::error('Erreurs de validation', [
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
            // Préparer les données de création
            $data = [
                'type' => $request->type,
                'nom' => $request->nom,
                'description' => $request->description,
                'lieu' => $request->lieu,
                'responsable_id' => $request->responsable_id,
                'statut' => $request->statut,
                'configuration' => $request->configuration,
            ];

            // Ajouter les dates seulement si elles sont présentes
            if ($request->has('date_debut') && $request->date_debut) {
                $data['date_debut'] = $request->date_debut;
            } else {
                // Dates par défaut pour les activités avec répétitions
                $data['date_debut'] = now();
                $data['date_fin'] = now()->addHours(2);
            }

            if ($request->has('date_fin') && $request->date_fin) {
                $data['date_fin'] = $request->date_fin;
            }

            \Log::info('Données préparées pour création', ['data' => $data]);
            
            $activite = Activite::create($data);
            
            \Log::info('Activité créée avec succès', ['activite_id' => $activite->id]);

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
        \Log::info('=== MISE À JOUR ACTIVITÉ ===', [
            'activite_id' => $activite->id,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $rules = [
            'type' => 'required|in:repetition,prestation,goudi_aldiouma,formation,reunion',
            'nom' => 'required|string|max:200',
            'description' => 'nullable|string',
            'lieu' => 'nullable|string|max:200',
            'responsable_id' => 'nullable|exists:membres,id',
            'statut' => 'required|in:planifie,confirme,en_cours,termine,annule',
            'configuration' => 'nullable|array',
        ];

        // Validation conditionnelle des dates selon le type de création
        if ($request->has('type_creation') && $request->type_creation === 'repetition') {
            // Pour les activités avec répétitions, les dates ne sont pas obligatoires
            if ($request->has('date_debut') && $request->date_debut) {
                $rules['date_debut'] = 'date';
            }
            if ($request->has('date_fin') && $request->date_fin) {
                $rules['date_fin'] = 'date|after:date_debut';
            }
        } else {
            // Pour les activités simples, les dates sont obligatoires
            $rules['date_debut'] = 'required|date';
            $rules['date_fin'] = 'required|date|after:date_debut';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Log::error('Erreurs de validation mise à jour', [
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
            // Vérifier si c'est une activité avec répétitions
            if ($request->has('type_creation') && $request->type_creation === 'repetition') {
                return $this->gererActiviteAvecRepetitions($request, $activite);
            }

            // Préparer les données de mise à jour pour activité simple
            $data = [
                'type' => $request->type,
                'nom' => $request->nom,
                'description' => $request->description,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'lieu' => $request->lieu,
                'responsable_id' => $request->responsable_id,
                'statut' => $request->statut,
                'configuration' => $request->configuration,
            ];

            \Log::info('Données préparées pour mise à jour activité simple', ['data' => $data]);
            
            $activite->update($data);

            \Log::info('Activité simple mise à jour avec succès', ['activite_id' => $activite->id]);

            return response()->json([
                'success' => true,
                'message' => 'Activité mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour activité', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gérer une activité avec répétitions
     */
    private function gererActiviteAvecRepetitions(Request $request, Activite $activite)
    {
        \Log::info('=== GESTION ACTIVITÉ AVEC RÉPÉTITIONS ===', [
            'activite_id' => $activite->id,
            'request_data' => $request->all()
        ]);

        // Validation spécifique pour les répétitions
        $validator = Validator::make($request->all(), [
            'date_debut_repetition' => 'required|date',
            'date_fin_repetition' => 'required|date|after:date_debut_repetition',
            'jours_repetition' => 'required|array|min:1',
            'jours_repetition.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'horaires' => 'required|array',
        ]);

        if ($validator->fails()) {
            \Log::error('Erreurs de validation répétitions', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation pour les répétitions',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Mettre à jour l'activité principale
            $activite->update([
                'type' => $request->type,
                'nom' => $request->nom,
                'description' => $request->description,
                'lieu' => $request->lieu,
                'responsable_id' => $request->responsable_id,
                'statut' => $request->statut,
                'configuration' => $request->configuration,
            ]);

            // Supprimer les anciennes répétitions
            $activite->repetitions()->delete();

            // Générer les nouvelles répétitions
            $dateDebut = Carbon::parse($request->date_debut_repetition);
            $dateFin = Carbon::parse($request->date_fin_repetition);
            $joursRepetition = $request->jours_repetition;
            $horaires = $request->horaires;
            $repetitionsCreees = 0;

            \Log::info('Génération des répétitions', [
                'date_debut' => $dateDebut->toDateString(),
                'date_fin' => $dateFin->toDateString(),
                'jours' => $joursRepetition,
                'horaires' => $horaires
            ]);

            $currentDate = $dateDebut->copy();
            while ($currentDate->lte($dateFin)) {
                $jourSemaine = $currentDate->locale('fr')->dayName;
                $jourSemaineLower = strtolower($jourSemaine);
                
                if (in_array($jourSemaineLower, $joursRepetition) && 
                    isset($horaires[$jourSemaineLower]) && 
                    !empty($horaires[$jourSemaineLower]['debut']) && 
                    !empty($horaires[$jourSemaineLower]['fin'])) {
                    
                    // Créer la répétition
                    $activite->repetitions()->create([
                        'date_repetition' => $currentDate->toDateString(),
                        'heure_debut' => $horaires[$jourSemaineLower]['debut'],
                        'heure_fin' => $horaires[$jourSemaineLower]['fin'],
                        'lieu' => $request->lieu,
                        'statut' => 'planifie',
                        'responsable_id' => $request->responsable_id,
                    ]);
                    $repetitionsCreees++;
                }
                $currentDate->addDay();
            }

            \Log::info('Répétitions générées avec succès', [
                'activite_id' => $activite->id,
                'repetitions_creees' => $repetitionsCreees
            ]);

            return response()->json([
                'success' => true,
                'message' => "Activité mise à jour avec {$repetitionsCreees} répétitions générées",
                'repetitions_creees' => $repetitionsCreees
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur gestion répétitions', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération des répétitions: ' . $e->getMessage()
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
        // Vérifier si l'activité a des répétitions
        $repetitions = $activite->repetitions()->orderBy('date_repetition', 'asc')->get();
        
        if ($repetitions->isEmpty()) {
            return redirect()->route('activites.show', $activite)
                ->with('error', 'Cette activité n\'a pas de répétitions. Veuillez d\'abord créer des répétitions pour gérer les présences.');
        }
        
        // Rediriger vers la première répétition pour la gestion des présences
        $premiereRepetition = $repetitions->first();
        return redirect()->route('repetitions.presences', $premiereRepetition);
    }

    /**
     * Marquer la présence d'un membre pour une activité
     */
    public function marquerPresence(Request $request, Activite $activite)
    {
        // Vérifier si l'activité a des répétitions
        $repetitions = $activite->repetitions()->orderBy('date_repetition', 'asc')->get();
        
        if ($repetitions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette activité n\'a pas de répétitions. Veuillez d\'abord créer des répétitions pour gérer les présences.'
            ], 400);
        }
        
        // Rediriger vers la première répétition pour la gestion des présences
        $premiereRepetition = $repetitions->first();
        return redirect()->route('repetitions.presences', $premiereRepetition);
    }

    /**
     * Modifier une présence existante
     */
    public function modifierPresence(Request $request, Presence $presence)
    {
        \Log::info('=== MODIFICATION PRÉSENCE ===', [
            'presence_id' => $presence->id,
            'request_data' => $request->all(),
            'method' => $request->method()
        ]);

        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:present,absent_justifie,absent_non_justifie,retard',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'minutes_retard' => 'nullable|integer|min:0|max:1440',
            'justification' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            \Log::error('Erreurs de validation modification présence', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all(),
                'presence_id' => $presence->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Préparer les données de mise à jour
            $data = [
                'statut' => $request->statut,
                'justification' => $request->justification,
                'minutes_retard' => $request->minutes_retard ?? 0,
            ];

            // Gérer l'heure d'arrivée et calculer automatiquement les minutes de retard
            if ($request->has('heure_arrivee') && $request->heure_arrivee) {
                // Convertir l'heure en datetime complet avec la date de l'activité
                $activite = $presence->activite;
                $dateActivite = $activite->date_debut ? $activite->date_debut->format('Y-m-d') : now()->format('Y-m-d');
                $heureArrivee = $dateActivite . ' ' . $request->heure_arrivee . ':00';
                $data['heure_arrivee'] = $heureArrivee;
                
                // Calculer automatiquement les minutes de retard par rapport à l'heure de début de l'ACTIVITÉ
                if ($request->statut === 'retard') {
                    if ($activite && $activite->date_debut) {
                        // Extraire seulement l'heure de l'activité (sans la date)
                        $heureDebutActivite = $activite->date_debut->format('H:i');
                        $heureDebutComplete = $dateActivite . ' ' . $heureDebutActivite . ':00';
                        
                        $datetimeDebut = \Carbon\Carbon::parse($heureDebutComplete);
                        $datetimeArrivee = \Carbon\Carbon::parse($heureArrivee);
                        
                        // Calculer la différence en minutes
                        $minutesRetard = $datetimeArrivee->diffInMinutes($datetimeDebut, false);
                        
                        \Log::info('=== CALCUL RETARD ACTIVITÉ GÉNÉRALE ===', [
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
                        $data['minutes_retard'] = max(0, $minutesRetard);
                        
                        \Log::info('Minutes de retard enregistrées (activité générale)', [
                            'minutes' => $data['minutes_retard'],
                            'retard_positif' => $minutesRetard > 0 ? 'OUI' : 'NON'
                        ]);
                    } else {
                        \Log::warning('Impossible de calculer le retard - Activité ou heure de début manquante', [
                            'activite_existe' => $activite ? 'OUI' : 'NON',
                            'date_debut_existe' => $activite && $activite->date_debut ? 'OUI' : 'NON'
                        ]);
                        $data['minutes_retard'] = 0;
                    }
                }
            } elseif ($request->statut === 'present' || $request->statut === 'retard') {
                // Si présent ou retard sans heure spécifiée, utiliser l'heure actuelle
                $data['heure_arrivee'] = now();
                
                // Calculer les minutes de retard avec l'heure actuelle par rapport à l'ACTIVITÉ
                if ($request->statut === 'retard') {
                    $activite = $presence->activite;
                    
                    if ($activite && $activite->date_debut) {
                        $dateActivite = $activite->date_debut ? $activite->date_debut->format('Y-m-d') : now()->format('Y-m-d');
                        $heureDebutActivite = $activite->date_debut->format('H:i');
                        $heureDebutComplete = $dateActivite . ' ' . $heureDebutActivite . ':00';
                        
                        $datetimeDebut = \Carbon\Carbon::parse($heureDebutComplete);
                        $datetimeArrivee = now();
                        
                        $minutesRetard = $datetimeArrivee->diffInMinutes($datetimeDebut, false);
                        $data['minutes_retard'] = max(0, $minutesRetard);
                        
                        \Log::info('Calcul retard avec heure actuelle (activité générale)', [
                            'heure_debut_activite' => $heureDebutActivite,
                            'minutes_retard' => $data['minutes_retard']
                        ]);
                    } else {
                        $data['minutes_retard'] = 0;
                    }
                }
            } else {
                // Pour les absents, pas d'heure d'arrivée
                $data['heure_arrivee'] = null;
            }

            \Log::info('Données préparées pour modification présence', ['data' => $data]);
            
            $presence->update($data);

            \Log::info('Présence modifiée avec succès', [
                'presence_id' => $presence->id,
                'membre_id' => $presence->membre_id,
                'statut' => $presence->statut
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Présence modifiée avec succès',
                'presence' => $presence->fresh(['membre'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur modification présence', [
                'error' => $e->getMessage(),
                'presence_id' => $presence->id,
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer le taux de présence moyen de toutes les activités
     */
    private function calculerTauxPresenceMoyen()
    {
        try {
            $activites = Activite::with('presences')->get();
            
            if ($activites->isEmpty()) {
                return 0;
            }

            $totalTaux = 0;
            $activitesAvecPresences = 0;

            foreach ($activites as $activite) {
                $taux = $this->calculerTauxPresenceActivite($activite);
                if ($taux > 0) {
                    $totalTaux += $taux;
                    $activitesAvecPresences++;
                }
            }

            return $activitesAvecPresences > 0 ? round($totalTaux / $activitesAvecPresences, 2) : 0;
        } catch (\Exception $e) {
            \Log::error('Erreur calcul taux présence moyen', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Calculer le taux de présence pour une activité spécifique
     */
    private function calculerTauxPresenceActivite(Activite $activite)
    {
        try {
            $presences = $activite->presences;
            
            if ($presences->isEmpty()) {
                return 0;
            }

            $totalPresences = $presences->count();
            $presents = $presences->where('statut', 'present')->count();
            $retards = $presences->where('statut', 'retard')->count();
            
            // Les retards comptent comme présents
            $totalPresents = $presents + $retards;
            
            return $totalPresences > 0 ? round(($totalPresents / $totalPresences) * 100, 2) : 0;
        } catch (\Exception $e) {
            \Log::error('Erreur calcul taux présence activité', [
                'error' => $e->getMessage(),
                'activite_id' => $activite->id
            ]);
            return 0;
        }
    }
}