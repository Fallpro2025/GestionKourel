<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Membre;
use App\Models\Activite;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlerteController extends Controller
{
    /**
     * Afficher la liste des alertes
     */
    public function index()
    {
        $alertes = Alerte::with(['membre', 'activite', 'evenement', 'resolvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistiques
        $stats = [
            'total_alertes' => Alerte::count(),
            'alertes_nouvelles' => Alerte::where('statut', 'nouveau')->count(),
            'alertes_envoyees' => Alerte::where('statut', 'envoye')->count(),
            'alertes_lues' => Alerte::where('statut', 'lu')->count(),
            'alertes_resolues' => Alerte::where('statut', 'resolu')->count(),
            'alertes_critiques' => Alerte::critiques()->count(),
            'alertes_aujourd_hui' => Alerte::whereDate('created_at', today())->count(),
            'alertes_cette_semaine' => Alerte::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('alertes.index', compact('alertes', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        $activites = Activite::orderBy('date_debut', 'desc')->get();
        $evenements = Evenement::orderBy('date_debut', 'desc')->get();
        
        return view('alertes.create', compact('membres', 'activites', 'evenements'));
    }

    /**
     * Enregistrer une nouvelle alerte
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:absence_repetitive,absence_non_justifiee,retard_excessif,cotisation_retard,evenement_majeur',
            'membre_id' => 'nullable|exists:membres,id',
            'activite_id' => 'nullable|exists:activites,id',
            'evenement_id' => 'nullable|exists:evenements,id',
            'message' => 'required|string',
            'niveau_urgence' => 'required|in:info,warning,error,critical',
            'canal_notification' => 'nullable|array',
            'canal_notification.*' => 'in:email,sms,whatsapp,push',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $alerte = Alerte::create([
                'type' => $request->type,
                'membre_id' => $request->membre_id,
                'activite_id' => $request->activite_id,
                'evenement_id' => $request->evenement_id,
                'message' => $request->message,
                'niveau_urgence' => $request->niveau_urgence,
                'canal_notification' => $request->canal_notification,
                'statut' => 'nouveau',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alerte créée avec succès',
                'alerte' => $alerte
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur création alerte', [
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
     * Afficher une alerte spécifique
     */
    public function show(Alerte $alerte)
    {
        $alerte->load(['membre', 'activite', 'evenement', 'resolvedBy']);
        
        return view('alertes.show', compact('alerte'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Alerte $alerte)
    {
        $alerte->load(['membre', 'activite', 'evenement', 'resolvedBy']);
        $membres = Membre::where('statut', 'actif')->orderBy('nom')->get();
        $activites = Activite::orderBy('date_debut', 'desc')->get();
        $evenements = Evenement::orderBy('date_debut', 'desc')->get();
        
        return view('alertes.edit', compact('alerte', 'membres', 'activites', 'evenements'));
    }

    /**
     * Mettre à jour une alerte
     */
    public function update(Request $request, Alerte $alerte)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:absence_repetitive,absence_non_justifiee,retard_excessif,cotisation_retard,evenement_majeur',
            'membre_id' => 'nullable|exists:membres,id',
            'activite_id' => 'nullable|exists:activites,id',
            'evenement_id' => 'nullable|exists:evenements,id',
            'message' => 'required|string',
            'niveau_urgence' => 'required|in:info,warning,error,critical',
            'statut' => 'required|in:nouveau,envoye,lu,resolu',
            'canal_notification' => 'nullable|array',
            'canal_notification.*' => 'in:email,sms,whatsapp,push',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $alerte->update([
                'type' => $request->type,
                'membre_id' => $request->membre_id,
                'activite_id' => $request->activite_id,
                'evenement_id' => $request->evenement_id,
                'message' => $request->message,
                'niveau_urgence' => $request->niveau_urgence,
                'statut' => $request->statut,
                'canal_notification' => $request->canal_notification,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alerte mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour alerte', [
                'error' => $e->getMessage(),
                'alerte_id' => $alerte->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une alerte
     */
    public function destroy(Alerte $alerte)
    {
        try {
            $alerte->delete();

            return response()->json([
                'success' => true,
                'message' => 'Alerte supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur suppression alerte', [
                'error' => $e->getMessage(),
                'alerte_id' => $alerte->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marquer une alerte comme lue
     */
    public function marquerCommeLue(Alerte $alerte)
    {
        try {
            $alerte->marquerCommeLue();

            return response()->json([
                'success' => true,
                'message' => 'Alerte marquée comme lue'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur marquage alerte comme lue', [
                'error' => $e->getMessage(),
                'alerte_id' => $alerte->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Résoudre une alerte
     */
    public function resoudre(Request $request, Alerte $alerte)
    {
        $validator = Validator::make($request->all(), [
            'resolved_by' => 'required|exists:membres,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $alerte->resoudre($request->resolved_by);

            return response()->json([
                'success' => true,
                'message' => 'Alerte résolue avec succès'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur résolution alerte', [
                'error' => $e->getMessage(),
                'alerte_id' => $alerte->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la résolution: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marquer toutes les alertes comme lues
     */
    public function marquerToutesCommeLues()
    {
        try {
            Alerte::where('statut', 'envoye')->update(['statut' => 'lu']);

            return response()->json([
                'success' => true,
                'message' => 'Toutes les alertes ont été marquées comme lues'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur marquage toutes alertes comme lues', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les alertes non lues
     */
    public function nonLues()
    {
        $alertes = Alerte::with(['membre', 'activite', 'evenement'])
            ->whereIn('statut', ['nouveau', 'envoye'])
            ->orderBy('niveau_urgence', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($alertes);
    }

    /**
     * Obtenir les alertes critiques
     */
    public function alertesCritiques()
    {
        $alertes = Alerte::critiques()
            ->with(['membre', 'activite', 'evenement'])
            ->where('statut', '!=', 'resolu')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($alertes);
    }

    /**
     * Obtenir les notifications
     */
    public function notifications()
    {
        $alertes = Alerte::with(['membre', 'activite', 'evenement'])
            ->whereIn('statut', ['nouveau', 'envoye'])
            ->orderBy('niveau_urgence', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($alertes);
    }

    /**
     * Obtenir les statistiques des alertes
     */
    public function statistiques()
    {
        $stats = [
            'total' => Alerte::count(),
            'par_statut' => Alerte::select('statut', DB::raw('count(*) as total'))
                ->groupBy('statut')
                ->get(),
            'par_niveau_urgence' => Alerte::select('niveau_urgence', DB::raw('count(*) as total'))
                ->groupBy('niveau_urgence')
                ->get(),
            'par_type' => Alerte::select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get(),
            'evolution_7_jours' => Alerte::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return response()->json($stats);
    }
}
