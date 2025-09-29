<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\AssignationCotisation;
use Illuminate\Http\Request;

class MembreAssignationController extends Controller
{
    /**
     * Afficher les assignations d'un membre
     */
    public function index(Membre $membre)
    {
        $assignations = $membre->assignationsCotisation()
            ->with('projet')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $statistiques = [
            'total_assignations' => $assignations->count(),
            'montant_total_assigné' => $assignations->sum('montant_assigné'),
            'montant_total_payé' => $assignations->sum('montant_payé'),
            'montant_total_restant' => $assignations->sum('montant_restant'),
            'pourcentage_moyen_payé' => $assignations->avg('pourcentage_payé'),
            'assignations_en_retard' => $assignations->where('en_retard', true)->count(),
            'assignations_payées' => $assignations->where('statut_paiement', 'paye')->count(),
        ];

        return view('membres.assignations', compact('membre', 'assignations', 'statistiques'));
    }

    /**
     * Afficher les détails d'une assignation spécifique
     */
    public function show(Membre $membre, AssignationCotisation $assignation)
    {
        // Vérifier que l'assignation appartient au membre
        if ($assignation->membre_id !== $membre->id) {
            abort(403, 'Cette assignation ne vous appartient pas.');
        }

        $assignation->load('projet', 'membre');
        
        // Récupérer l'historique des paiements
        $historiquePaiements = $assignation->historique_paiements ?? [];

        return view('membres.assignation-details', compact('membre', 'assignation', 'historiquePaiements'));
    }

    /**
     * Afficher l'historique des paiements d'une assignation
     */
    public function historique(Membre $membre, AssignationCotisation $assignation)
    {
        // Vérifier que l'assignation appartient au membre
        if ($assignation->membre_id !== $membre->id) {
            abort(403, 'Cette assignation ne vous appartient pas.');
        }

        $historiquePaiements = $assignation->historique_paiements ?? [];

        return view('membres.assignation-historique', compact('membre', 'assignation', 'historiquePaiements'));
    }
}
