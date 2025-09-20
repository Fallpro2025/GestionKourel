<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\ProjetCotisationController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\AlerteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes publiques (authentification)
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::get('check', [AuthController::class, 'checkAuth']);
});

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    
    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('statistiques', [DashboardController::class, 'statistiques']);
        Route::get('alertes-critiques', [DashboardController::class, 'alertesCritiques']);
        Route::get('activites-en-cours', [DashboardController::class, 'activitesEnCours']);
    });

    // Membres
    Route::prefix('membres')->group(function () {
        Route::get('/', [MembreController::class, 'index']);
        Route::post('/', [MembreController::class, 'store']);
        Route::get('search', [MembreController::class, 'search']);
        Route::get('{membre}', [MembreController::class, 'show']);
        Route::put('{membre}', [MembreController::class, 'update']);
        Route::delete('{membre}', [MembreController::class, 'destroy']);
        Route::get('{membre}/statistiques', [MembreController::class, 'statistiques']);
        Route::get('{membre}/historique', [MembreController::class, 'historique']);
        Route::get('{membre}/export', [MembreController::class, 'export']);
    });

    // Projets de cotisation
    Route::prefix('projets-cotisation')->group(function () {
        Route::get('/', [ProjetCotisationController::class, 'index']);
        Route::post('/', [ProjetCotisationController::class, 'store']);
        Route::get('{projet}', [ProjetCotisationController::class, 'show']);
        Route::put('{projet}', [ProjetCotisationController::class, 'update']);
        Route::delete('{projet}', [ProjetCotisationController::class, 'destroy']);
        Route::get('{projet}/statistiques', [ProjetCotisationController::class, 'statistiques']);
        Route::post('{projet}/assigner', [ProjetCotisationController::class, 'assignerMembres']);
    });

    // Assignations de cotisation
    Route::prefix('assignations-cotisation')->group(function () {
        Route::get('/', [ProjetCotisationController::class, 'indexAssignations']);
        Route::post('{assignation}/paiement', [ProjetCotisationController::class, 'enregistrerPaiement']);
        Route::get('{assignation}/historique', [ProjetCotisationController::class, 'historiquePaiements']);
    });

    // Activités
    Route::prefix('activites')->group(function () {
        Route::get('/', [ActiviteController::class, 'index']);
        Route::post('/', [ActiviteController::class, 'store']);
        Route::get('{activite}', [ActiviteController::class, 'show']);
        Route::put('{activite}', [ActiviteController::class, 'update']);
        Route::delete('{activite}', [ActiviteController::class, 'destroy']);
        Route::get('{activite}/presences', [ActiviteController::class, 'presences']);
        Route::post('{activite}/marquer-presence', [ActiviteController::class, 'marquerPresence']);
        Route::get('{activite}/statistiques', [ActiviteController::class, 'statistiques']);
    });

    // Présences
    Route::prefix('presences')->group(function () {
        Route::get('/', [ActiviteController::class, 'indexPresences']);
        Route::put('{presence}', [ActiviteController::class, 'updatePresence']);
        Route::delete('{presence}', [ActiviteController::class, 'deletePresence']);
    });

    // Événements
    Route::prefix('evenements')->group(function () {
        Route::get('/', [EvenementController::class, 'index']);
        Route::post('/', [EvenementController::class, 'store']);
        Route::get('{evenement}', [EvenementController::class, 'show']);
        Route::put('{evenement}', [EvenementController::class, 'update']);
        Route::delete('{evenement}', [EvenementController::class, 'destroy']);
        Route::get('{evenement}/participants', [EvenementController::class, 'participants']);
        Route::post('{evenement}/ajouter-participant', [EvenementController::class, 'ajouterParticipant']);
        Route::delete('{evenement}/retirer-participant', [EvenementController::class, 'retirerParticipant']);
        Route::get('{evenement}/statistiques', [EvenementController::class, 'statistiques']);
    });

    // Alertes
    Route::prefix('alertes')->group(function () {
        Route::get('/', [AlerteController::class, 'index']);
        Route::post('/', [AlerteController::class, 'store']);
        Route::get('{alerte}', [AlerteController::class, 'show']);
        Route::put('{alerte}', [AlerteController::class, 'update']);
        Route::delete('{alerte}', [AlerteController::class, 'destroy']);
        Route::post('{alerte}/marquer-lue', [AlerteController::class, 'marquerCommeLue']);
        Route::post('{alerte}/resoudre', [AlerteController::class, 'resoudre']);
        Route::get('regles', [AlerteController::class, 'regles']);
        Route::post('regles', [AlerteController::class, 'storeRegle']);
        Route::put('regles/{regle}', [AlerteController::class, 'updateRegle']);
        Route::delete('regles/{regle}', [AlerteController::class, 'destroyRegle']);
    });

    // Rôles et permissions
    Route::prefix('roles')->group(function () {
        Route::get('/', [MembreController::class, 'indexRoles']);
        Route::post('/', [MembreController::class, 'storeRole']);
        Route::get('{role}', [MembreController::class, 'showRole']);
        Route::put('{role}', [MembreController::class, 'updateRole']);
        Route::delete('{role}', [MembreController::class, 'destroyRole']);
    });

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [AlerteController::class, 'notifications']);
        Route::post('marquer-lues', [AlerteController::class, 'marquerToutesCommeLues']);
        Route::get('non-lues', [AlerteController::class, 'nonLues']);
    });

    // Rapports et exports
    Route::prefix('rapports')->group(function () {
        Route::get('membres', [MembreController::class, 'rapportMembres']);
        Route::get('cotisations', [ProjetCotisationController::class, 'rapportCotisations']);
        Route::get('presences', [ActiviteController::class, 'rapportPresences']);
        Route::get('evenements', [EvenementController::class, 'rapportEvenements']);
        Route::get('alertes', [AlerteController::class, 'rapportAlertes']);
    });

    // Configuration
    Route::prefix('config')->group(function () {
        Route::get('/', [DashboardController::class, 'configuration']);
        Route::put('/', [DashboardController::class, 'updateConfiguration']);
    });

});

// Routes de test (à supprimer en production)
Route::get('/test', function () {
    return response()->json([
        'message' => 'API Gestion Kourel fonctionnelle',
        'version' => '1.0.0',
        'timestamp' => now()->toISOString()
    ]);
});

// Route de santé de l'API
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => 'connected',
        'timestamp' => now()->toISOString()
    ]);
});
