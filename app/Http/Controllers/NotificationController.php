<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Envoyer une notification à tous les membres
     */
    public function envoyerATous(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
                'type' => 'required|in:info,success,warning,error',
                'canaux' => 'required|array',
                'canaux.*' => 'in:email,sms,whatsapp,push'
            ], [
                'titre.required' => 'Le titre est requis',
                'message.required' => 'Le message est requis',
                'type.required' => 'Le type de notification est requis',
                'canaux.required' => 'Veuillez sélectionner au moins un canal de communication'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $membres = Membre::where('statut', 'actif')->get();
            $envoyees = 0;
            $echecs = 0;

            foreach ($membres as $membre) {
                try {
                    // Créer la notification en base
                    $notification = Notification::create([
                        'membre_id' => $membre->id,
                        'titre' => $request->titre,
                        'message' => $request->message,
                        'type' => $request->type,
                        'canal' => $request->canaux[0] ?? 'app', // Prendre le premier canal
                        'envoyee' => true,
                        'envoyee_le' => now(),
                        'metadata' => ['canaux' => $request->canaux]
                    ]);

                    // Simuler l'envoi selon les canaux
                    foreach ($request->canaux as $canal) {
                        $this->envoyerNotification($membre, $notification, $canal);
                    }

                    $envoyees++;

                } catch (\Exception $e) {
                    Log::error('Erreur envoi notification membre', [
                        'membre_id' => $membre->id,
                        'error' => $e->getMessage()
                    ]);
                    $echecs++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Notifications envoyées avec succès ! {$envoyees} envoyées, {$echecs} échecs.",
                'statistiques' => [
                    'envoyees' => $envoyees,
                    'echecs' => $echecs,
                    'total' => $membres->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi notifications tous', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi des notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer une notification à un membre spécifique
     */
    public function envoyerAMembre(Request $request, Membre $membre)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
                'type' => 'required|in:info,success,warning,error',
                'canaux' => 'required|array',
                'canaux.*' => 'in:email,sms,whatsapp,push'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Créer la notification
            $notification = Notification::create([
                'membre_id' => $membre->id,
                'titre' => $request->titre,
                'message' => $request->message,
                'type' => $request->type,
                'canal' => $request->canaux[0] ?? 'app',
                'envoyee' => true,
                'envoyee_le' => now(),
                'metadata' => ['canaux' => $request->canaux]
            ]);

            // Envoyer selon les canaux
            $envoyees = 0;
            foreach ($request->canaux as $canal) {
                if ($this->envoyerNotification($membre, $notification, $canal)) {
                    $envoyees++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Notification envoyée à {$membre->prenom} {$membre->nom} via {$envoyees} canal(s).",
                'notification' => $notification
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi notification membre', [
                'membre_id' => $membre->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envoyer une notification à un groupe de membres
     */
    public function envoyerAGroupe(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:255',
                'message' => 'required|string|max:1000',
                'type' => 'required|in:info,success,warning,error',
                'canaux' => 'required|array',
                'canaux.*' => 'in:email,sms,whatsapp,push',
                'groupe' => 'required|in:actifs,inactifs,suspendus,role,profession',
                'valeur' => 'required_if:groupe,role,profession|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Déterminer les membres du groupe
            $membres = $this->getMembresGroupe($request->groupe, $request->valeur);
            
            if ($membres->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun membre trouvé pour ce groupe.'
                ], 404);
            }

            $envoyees = 0;
            $echecs = 0;

            foreach ($membres as $membre) {
                try {
                    $notification = Notification::create([
                        'membre_id' => $membre->id,
                        'titre' => $request->titre,
                        'message' => $request->message,
                        'type' => $request->type,
                        'canal' => $request->canaux[0] ?? 'app',
                        'envoyee' => true,
                        'envoyee_le' => now(),
                        'metadata' => ['canaux' => $request->canaux]
                    ]);

                    foreach ($request->canaux as $canal) {
                        $this->envoyerNotification($membre, $notification, $canal);
                    }

                    $envoyees++;

                } catch (\Exception $e) {
                    Log::error('Erreur envoi notification groupe', [
                        'membre_id' => $membre->id,
                        'error' => $e->getMessage()
                    ]);
                    $echecs++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Notifications envoyées au groupe ! {$envoyees} envoyées, {$echecs} échecs.",
                'statistiques' => [
                    'envoyees' => $envoyees,
                    'echecs' => $echecs,
                    'total' => $membres->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi notifications groupe', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi des notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des notifications
     */
    public function statistiques()
    {
        try {
            $stats = [
                'total' => Notification::count(),
                'envoyees' => Notification::where('envoyee', true)->count(),
                'non_envoyees' => Notification::where('envoyee', false)->count(),
                'par_type' => Notification::selectRaw('type, count(*) as count')
                    ->groupBy('type')
                    ->get()
                    ->pluck('count', 'type'),
                'par_canal' => $this->getStatistiquesCanaux(),
                'recentes' => Notification::with('membre')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Erreur statistiques notifications', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques'
            ], 500);
        }
    }

    /**
     * Obtenir l'historique des notifications
     */
    public function historique(Request $request)
    {
        try {
            $query = Notification::with('membre');

            // Filtres
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('envoyee')) {
                $query->where('envoyee', $request->envoyee);
            }

            if ($request->filled('date_debut')) {
                $query->where('created_at', '>=', $request->date_debut);
            }

            if ($request->filled('date_fin')) {
                $query->where('created_at', '<=', $request->date_fin);
            }

            $notifications = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json($notifications);

        } catch (\Exception $e) {
            Log::error('Erreur historique notifications', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement de l\'historique'
            ], 500);
        }
    }

    /**
     * Envoyer une notification via un canal spécifique
     */
    private function envoyerNotification(Membre $membre, Notification $notification, string $canal): bool
    {
        try {
            switch ($canal) {
                case 'email':
                    return $this->envoyerEmail($membre, $notification);
                case 'sms':
                    return $this->envoyerSMS($membre, $notification);
                case 'whatsapp':
                    return $this->envoyerWhatsApp($membre, $notification);
                case 'push':
                    return $this->envoyerPush($membre, $notification);
                default:
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi notification canal', [
                'canal' => $canal,
                'membre_id' => $membre->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer par email (simulation)
     */
    private function envoyerEmail(Membre $membre, Notification $notification): bool
    {
        // Simulation d'envoi email
        Log::info('Email envoyé', [
            'membre' => $membre->email,
            'titre' => $notification->titre
        ]);
        return true;
    }

    /**
     * Envoyer par SMS (simulation)
     */
    private function envoyerSMS(Membre $membre, Notification $notification): bool
    {
        // Simulation d'envoi SMS
        Log::info('SMS envoyé', [
            'membre' => $membre->telephone,
            'message' => $notification->message
        ]);
        return true;
    }

    /**
     * Envoyer par WhatsApp (simulation)
     */
    private function envoyerWhatsApp(Membre $membre, Notification $notification): bool
    {
        // Simulation d'envoi WhatsApp
        Log::info('WhatsApp envoyé', [
            'membre' => $membre->telephone,
            'message' => $notification->message
        ]);
        return true;
    }

    /**
     * Envoyer notification push (simulation)
     */
    private function envoyerPush(Membre $membre, Notification $notification): bool
    {
        // Simulation d'envoi push
        Log::info('Push envoyé', [
            'membre_id' => $membre->id,
            'titre' => $notification->titre
        ]);
        return true;
    }

    /**
     * Obtenir les membres d'un groupe
     */
    private function getMembresGroupe(string $groupe, ?string $valeur = null)
    {
        $query = Membre::query();

        switch ($groupe) {
            case 'actifs':
                $query->where('statut', 'actif');
                break;
            case 'inactifs':
                $query->where('statut', 'inactif');
                break;
            case 'suspendus':
                $query->where('statut', 'suspendu');
                break;
            case 'role':
                if ($valeur) {
                    $query->whereHas('role', function($q) use ($valeur) {
                        $q->where('nom', $valeur);
                    });
                }
                break;
            case 'profession':
                if ($valeur) {
                    $query->where('profession', 'like', "%{$valeur}%");
                }
                break;
        }

        return $query->get();
    }

    /**
     * Obtenir les statistiques par canal
     */
    private function getStatistiquesCanaux()
    {
        return Notification::selectRaw('canal, count(*) as count')
            ->groupBy('canal')
            ->get()
            ->pluck('count', 'canal')
            ->toArray();
    }
}