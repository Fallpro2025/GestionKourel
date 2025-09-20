<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\ProjetCotisation;
use App\Models\Activite;
use App\Models\Evenement;
use App\Models\Alerte;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Obtenir les données du dashboard
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', '30'); // jours
        $dateDebut = now()->subDays($periode);

        // KPIs principaux
        $kpis = [
            'total_membres' => Membre::actifs()->count(),
            'taux_presence' => $this->calculerTauxPresenceGlobal($dateDebut),
            'cotisations_collectees' => $this->calculerCotisationsCollectees($dateDebut),
            'alertes_actives' => Alerte::nonResolues()->count(),
            'activites_cette_semaine' => $this->compterActivitesCetteSemaine(),
            'evenements_a_venir' => Evenement::aVenir()->count(),
            'budget_total_evenements' => Evenement::aVenir()->sum('budget'),
            'membres_nouveaux' => Membre::where('date_adhesion', '>=', $dateDebut)->count()
        ];

        // Activités récentes
        $activitesRecentes = Activite::with('responsable')
            ->where('date_debut', '>=', $dateDebut)
            ->orderBy('date_debut', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($activite) {
                $stats = $activite->getStatistiquesPresence();
                return [
                    'id' => $activite->id,
                    'nom' => $activite->nom,
                    'type' => $activite->type,
                    'date_debut' => $activite->date_debut,
                    'statut' => $activite->statut,
                    'lieu' => $activite->lieu,
                    'responsable' => $activite->responsable,
                    'taux_presence' => $stats['taux_presence'],
                    'total_presences' => $stats['total_presences']
                ];
            });

        // Nouveaux membres
        $nouveauxMembres = Membre::with('role')
            ->where('date_adhesion', '>=', $dateDebut)
            ->orderBy('date_adhesion', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($membre) {
                return [
                    'id' => $membre->id,
                    'nom' => $membre->nom,
                    'prenom' => $membre->prenom,
                    'nom_complet' => $membre->nom_complet,
                    'email' => $membre->email,
                    'profession' => $membre->profession,
                    'date_adhesion' => $membre->date_adhesion,
                    'photo_url' => $membre->photo_url,
                    'role' => $membre->role
                ];
            });

        // Alertes récentes
        $alertesRecentes = Alerte::with(['membre', 'activite', 'evenement'])
            ->where('created_at', '>=', $dateDebut)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($alerte) {
                return [
                    'id' => $alerte->id,
                    'titre' => $alerte->getTitre(),
                    'message' => $alerte->message,
                    'type' => $alerte->type,
                    'type_francais' => $alerte->type_francais,
                    'niveau_urgence' => $alerte->niveau_urgence,
                    'niveau_urgence_francais' => $alerte->niveau_urgence_francais,
                    'statut' => $alerte->statut,
                    'statut_francais' => $alerte->statut_francais,
                    'created_at' => $alerte->created_at,
                    'membre' => $alerte->membre,
                    'activite' => $alerte->activite,
                    'evenement' => $alerte->evenement
                ];
            });

        // Événements à venir
        $evenementsAVenir = Evenement::aVenir()
            ->orderBy('date_debut', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($evenement) {
                return [
                    'id' => $evenement->id,
                    'nom' => $evenement->nom,
                    'type' => $evenement->type,
                    'type_francais' => $evenement->type_francais,
                    'date_debut' => $evenement->date_debut,
                    'lieu' => $evenement->lieu,
                    'budget' => $evenement->budget,
                    'nombre_total_membres' => $evenement->getNombreTotalMembresSelectionnes()
                ];
            });

        // Graphiques
        $graphiques = [
            'evolution_presences' => $this->getEvolutionPresences($periode),
            'cotisations_par_mois' => $this->getCotisationsParMois($periode),
            'types_activites' => $this->getTypesActivites($periode),
            'statuts_membres' => $this->getStatutsMembres()
        ];

        return response()->json([
            'kpis' => $kpis,
            'activites_recentes' => $activitesRecentes,
            'nouveaux_membres' => $nouveauxMembres,
            'alertes_recentes' => $alertesRecentes,
            'evenements_a_venir' => $evenementsAVenir,
            'graphiques' => $graphiques,
            'periode' => $periode,
            'date_debut' => $dateDebut->toDateString()
        ]);
    }

    /**
     * Calculer le taux de présence global
     */
    private function calculerTauxPresenceGlobal($dateDebut)
    {
        $totalPresences = Presence::where('created_at', '>=', $dateDebut)->count();
        
        if ($totalPresences === 0) {
            return 0;
        }

        $presencesEffectives = Presence::where('created_at', '>=', $dateDebut)
            ->where('statut', 'present')
            ->count();

        return round(($presencesEffectives / $totalPresences) * 100, 2);
    }

    /**
     * Calculer les cotisations collectées
     */
    private function calculerCotisationsCollectees($dateDebut)
    {
        return ProjetCotisation::where('created_at', '>=', $dateDebut)
            ->sum('montant_collecte');
    }

    /**
     * Compter les activités de cette semaine
     */
    private function compterActivitesCetteSemaine()
    {
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();

        return Activite::whereBetween('date_debut', [$debutSemaine, $finSemaine])
            ->count();
    }

    /**
     * Obtenir l'évolution des présences
     */
    private function getEvolutionPresences($periode)
    {
        $donnees = [];
        
        for ($i = $periode; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            
            $total = Presence::whereDate('created_at', $date)->count();
            $presents = Presence::whereDate('created_at', $date)
                ->where('statut', 'present')
                ->count();
            
            $taux = $total > 0 ? round(($presents / $total) * 100, 2) : 0;
            
            $donnees[] = [
                'date' => $date,
                'total' => $total,
                'presents' => $presents,
                'taux' => $taux
            ];
        }

        return $donnees;
    }

    /**
     * Obtenir les cotisations par mois
     */
    private function getCotisationsParMois($periode)
    {
        $donnees = [];
        
        for ($i = $periode; $i >= 0; $i -= 30) {
            $dateDebut = now()->subDays($i)->startOfMonth();
            $dateFin = now()->subDays($i)->endOfMonth();
            
            $montant = ProjetCotisation::whereBetween('created_at', [$dateDebut, $dateFin])
                ->sum('montant_collecte');
            
            $donnees[] = [
                'mois' => $dateDebut->format('Y-m'),
                'montant' => $montant
            ];
        }

        return $donnees;
    }

    /**
     * Obtenir la répartition des types d'activités
     */
    private function getTypesActivites($periode)
    {
        $dateDebut = now()->subDays($periode);
        
        return Activite::where('created_at', '>=', $dateDebut)
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->type,
                    'total' => $item->total
                ];
            });
    }

    /**
     * Obtenir la répartition des statuts des membres
     */
    private function getStatutsMembres()
    {
        return Membre::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get()
            ->map(function ($item) {
                return [
                    'statut' => $item->statut,
                    'total' => $item->total
                ];
            });
    }

    /**
     * Obtenir les statistiques détaillées
     */
    public function statistiques(Request $request)
    {
        $periode = $request->get('periode', '30');
        $dateDebut = now()->subDays($periode);

        $statistiques = [
            'membres' => [
                'total' => Membre::count(),
                'actifs' => Membre::actifs()->count(),
                'nouveaux' => Membre::where('date_adhesion', '>=', $dateDebut)->count(),
                'par_statut' => $this->getStatutsMembres()
            ],
            'activites' => [
                'total' => Activite::where('created_at', '>=', $dateDebut)->count(),
                'par_type' => $this->getTypesActivites($periode),
                'taux_presence_moyen' => $this->calculerTauxPresenceGlobal($dateDebut)
            ],
            'cotisations' => [
                'total_projets' => ProjetCotisation::where('created_at', '>=', $dateDebut)->count(),
                'montant_total' => ProjetCotisation::where('created_at', '>=', $dateDebut)->sum('montant_total'),
                'montant_collecte' => ProjetCotisation::where('created_at', '>=', $dateDebut)->sum('montant_collecte'),
                'taux_recouvrement' => $this->calculerTauxRecouvrement($dateDebut)
            ],
            'evenements' => [
                'total' => Evenement::where('created_at', '>=', $dateDebut)->count(),
                'a_venir' => Evenement::aVenir()->count(),
                'budget_total' => Evenement::aVenir()->sum('budget')
            ],
            'alertes' => [
                'total' => Alerte::where('created_at', '>=', $dateDebut)->count(),
                'actives' => Alerte::nonResolues()->count(),
                'critiques' => Alerte::critiques()->count(),
                'resolues' => Alerte::where('statut', 'resolu')->where('created_at', '>=', $dateDebut)->count()
            ]
        ];

        return response()->json([
            'statistiques' => $statistiques,
            'periode' => $periode,
            'date_debut' => $dateDebut->toDateString()
        ]);
    }

    /**
     * Calculer le taux de recouvrement
     */
    private function calculerTauxRecouvrement($dateDebut)
    {
        $montantTotal = ProjetCotisation::where('created_at', '>=', $dateDebut)->sum('montant_total');
        $montantCollecte = ProjetCotisation::where('created_at', '>=', $dateDebut)->sum('montant_collecte');
        
        if ($montantTotal == 0) {
            return 0;
        }
        
        return round(($montantCollecte / $montantTotal) * 100, 2);
    }

    /**
     * Obtenir les alertes critiques
     */
    public function alertesCritiques()
    {
        $alertes = Alerte::critiques()
            ->with(['membre', 'activite', 'evenement'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($alerte) {
                return [
                    'id' => $alerte->id,
                    'titre' => $alerte->getTitre(),
                    'message' => $alerte->message,
                    'niveau_urgence' => $alerte->niveau_urgence,
                    'created_at' => $alerte->created_at,
                    'membre' => $alerte->membre,
                    'activite' => $alerte->activite,
                    'evenement' => $alerte->evenement
                ];
            });

        return response()->json([
            'alertes' => $alertes
        ]);
    }

    /**
     * Obtenir les activités en cours
     */
    public function activitesEnCours()
    {
        $activites = Activite::enCours()
            ->with('responsable')
            ->orderBy('date_debut', 'asc')
            ->get()
            ->map(function ($activite) {
                $stats = $activite->getStatistiquesPresence();
                return [
                    'id' => $activite->id,
                    'nom' => $activite->nom,
                    'type' => $activite->type,
                    'date_debut' => $activite->date_debut,
                    'date_fin' => $activite->date_fin,
                    'lieu' => $activite->lieu,
                    'responsable' => $activite->responsable,
                    'statistiques' => $stats
                ];
            });

        return response()->json([
            'activites' => $activites
        ]);
    }
}
