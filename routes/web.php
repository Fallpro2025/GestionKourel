<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route principale - Dashboard moderne
Route::get('/', function () {
    return view('dashboard-modern');
})->name('home');

// Route de test pour les alertes
Route::get('/test-alertes', function(Request $request) {
    $type = $request->get('type', 'success');
    
    switch($type) {
        case 'success':
            return redirect()->back()->with('success', 'Ceci est un message de succès de session !');
        case 'error':
            return redirect()->back()->with('error', 'Ceci est un message d\'erreur de session !');
        case 'warning':
            return redirect()->back()->with('warning', 'Ceci est un message d\'avertissement de session !');
        case 'info':
            return redirect()->back()->with('info', 'Ceci est un message d\'information de session !');
        default:
            return redirect()->back()->with('success', 'Message de test par défaut !');
    }
})->name('test-alertes');

// Route pour afficher la page de test des alertes
Route::get('/test-alertes-page', function () {
    return view('test-alertes');
})->name('test-alertes-page');

// Route de test simple pour vérifier les alertes
Route::get('/test-alertes-simple', function () {
    return redirect('/membres')->with('success', 'Test d\'alerte de succès !');
})->name('test-alertes-simple');

// Route pour servir les images des membres
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($filePath);
    
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000', // Cache 1 an
    ]);
})->where('path', '.*');

// Route Dashboard (temporairement désactivée)
Route::get('/dashboard', function () {
    return view('dashboard-modern');
})->name('dashboard');

// Routes Gestion des Membres
Route::get('/membres', [App\Http\Controllers\MembreController::class, 'index'])->name('membres.index');

// Routes d'export des membres
Route::get('/membres/export/csv', [App\Http\Controllers\MembreExportController::class, 'exportCsv'])->name('membres.export.csv');
Route::get('/membres/export/excel', [App\Http\Controllers\MembreExportController::class, 'exportExcel'])->name('membres.export.excel');
Route::get('/membres/export/pdf', [App\Http\Controllers\MembreExportController::class, 'exportPdf'])->name('membres.export.pdf');

// Routes pour les fonctionnalités avancées des membres
Route::get('/membres/statistiques', [App\Http\Controllers\MembreController::class, 'statistiques'])->name('membres.statistiques');
Route::get('/membres/statistiques/api', [App\Http\Controllers\MembreController::class, 'statistiquesApi'])->name('membres.statistiques.api');
Route::get('/membres/recherche-avancee', [App\Http\Controllers\MembreController::class, 'rechercheAvancee'])->name('membres.recherche-avancee');

// Routes pour la gestion des photos
Route::post('/membres/{membre}/upload-photo', [App\Http\Controllers\MembreController::class, 'uploadPhoto'])->name('membres.upload-photo');
Route::get('/membres/{membre}/photo', [App\Http\Controllers\MembreController::class, 'servePhoto'])->name('membres.serve-photo');
Route::delete('/membres/{membre}/photo', [App\Http\Controllers\MembreController::class, 'deletePhoto'])->name('membres.delete-photo');

// Routes pour l'historique des membres
Route::get('/membres/{membre}/historique', [App\Http\Controllers\MembreController::class, 'historique'])->name('membres.historique');

// Routes pour l'export PDF individuel
Route::get('/membres/{membre}/export/pdf', [App\Http\Controllers\MembreController::class, 'exportPdf'])->name('membres.export-pdf');





// Routes pour les notifications
Route::post('/notifications/envoyer-tous', [App\Http\Controllers\NotificationController::class, 'envoyerATous'])->name('notifications.envoyer-tous');
Route::post('/notifications/envoyer-membre/{membre}', [App\Http\Controllers\NotificationController::class, 'envoyerAMembre'])->name('notifications.envoyer-membre');
Route::post('/notifications/envoyer-groupe', [App\Http\Controllers\NotificationController::class, 'envoyerAGroupe'])->name('notifications.envoyer-groupe');
Route::get('/notifications/statistiques', [App\Http\Controllers\NotificationController::class, 'statistiques'])->name('notifications.statistiques');
Route::get('/notifications/historique', [App\Http\Controllers\NotificationController::class, 'historique'])->name('notifications.historique');

// Routes pour l'importation Excel des membres
Route::get('/membres/import', [App\Http\Controllers\MembreImportController::class, 'index'])->name('membres.import');
Route::post('/membres/import', [App\Http\Controllers\MembreImportController::class, 'import'])->name('membres.import.process');
Route::get('/membres/import/template', [App\Http\Controllers\MembreImportController::class, 'downloadTemplate'])->name('membres.import.template');
Route::get('/membres-liste', [App\Http\Controllers\MembreController::class, 'listeModerne'])->name('membres.liste-moderne');
Route::get('/membres-vue', [App\Http\Controllers\MembreController::class, 'listeVue'])->name('membres.liste-vue');
Route::get('/membres/create', [App\Http\Controllers\MembreController::class, 'create'])->name('membres.create');
Route::post('/membres', [App\Http\Controllers\MembreController::class, 'store'])->name('membres.store');
Route::get('/membres/{membre}', [App\Http\Controllers\MembreController::class, 'show'])->name('membres.show');
Route::get('/membres/{membre}/edit', [App\Http\Controllers\MembreController::class, 'edit'])->name('membres.edit');
Route::put('/membres/{membre}', [App\Http\Controllers\MembreController::class, 'update'])->name('membres.update');
Route::delete('/membres/{membre}', [App\Http\Controllers\MembreController::class, 'destroy'])->name('membres.destroy');
Route::get('/membres/export', [App\Http\Controllers\MembreController::class, 'export'])->name('membres.export');

// Routes Gestion des Rôles
Route::resource('roles', App\Http\Controllers\RoleController::class);

// Routes Gestion des Rôles des Membres
Route::prefix('membres/{membre}')->group(function () {
    Route::get('roles', [App\Http\Controllers\MembreRoleController::class, 'index'])->name('membres.roles.index');
    Route::put('roles/{role}', [App\Http\Controllers\MembreRoleController::class, 'update'])->name('membres.roles.update');
    Route::delete('roles/{role}', [App\Http\Controllers\MembreRoleController::class, 'destroy'])->name('membres.roles.destroy');
    Route::post('roles/{role}/principal', [App\Http\Controllers\MembreRoleController::class, 'definirPrincipal'])->name('membres.roles.principal');
});

// Route pour l'ajout de rôles (sans route model binding)
Route::post('membres/{membreId}/roles', [App\Http\Controllers\MembreRoleController::class, 'store'])->name('membres.roles.store');

// Route de test sans CSRF pour l'ajout de rôles
Route::post('membres/{membreId}/roles-test', [App\Http\Controllers\MembreRoleController::class, 'store'])->name('membres.roles.store.test')->withoutMiddleware(['web']);

// Routes pour les assignations des membres
Route::prefix('membres/{membre}')->group(function () {
    Route::get('assignations', [App\Http\Controllers\MembreAssignationController::class, 'index'])->name('membres.assignations.index');
    Route::get('assignations/{assignation}', [App\Http\Controllers\MembreAssignationController::class, 'show'])->name('membres.assignations.show');
    Route::get('assignations/{assignation}/historique', [App\Http\Controllers\MembreAssignationController::class, 'historique'])->name('membres.assignations.historique');
});

// Routes Gestion des Cotisations
Route::resource('cotisations', App\Http\Controllers\ProjetCotisationController::class);

// Routes pour les assignations de cotisation
Route::prefix('cotisations/{projet}')->group(function () {
    Route::get('assignations', [App\Http\Controllers\AssignationCotisationController::class, 'index'])->name('cotisations.assignations.index');
    Route::get('assignations/create', [App\Http\Controllers\AssignationCotisationController::class, 'create'])->name('cotisations.assignations.create');
    Route::post('assignations', [App\Http\Controllers\AssignationCotisationController::class, 'store'])->name('cotisations.assignations.store');
});

// Routes pour les assignations individuelles
Route::prefix('assignations/{assignation}')->group(function () {
    Route::get('/', [App\Http\Controllers\AssignationCotisationController::class, 'show'])->name('assignations.show');
    Route::get('edit', [App\Http\Controllers\AssignationCotisationController::class, 'edit'])->name('assignations.edit');
    Route::put('/', [App\Http\Controllers\AssignationCotisationController::class, 'update'])->name('assignations.update');
    Route::delete('/', [App\Http\Controllers\AssignationCotisationController::class, 'destroy'])->name('assignations.destroy');
    Route::post('paiement', [App\Http\Controllers\AssignationCotisationController::class, 'enregistrerPaiement'])->name('assignations.paiement');
    Route::get('historique', [App\Http\Controllers\AssignationCotisationController::class, 'historiquePaiements'])->name('assignations.historique');
    Route::post('marquer-paye', [App\Http\Controllers\AssignationCotisationController::class, 'marquerPaye'])->name('assignations.marquer-paye');
});

// Route API pour les statistiques des cotisations
Route::get('/cotisations/statistiques/api', [App\Http\Controllers\ProjetCotisationController::class, 'statistiques'])->name('cotisations.statistiques.api');

// Route de test pour le modal des rôles
Route::get('/test-modal-roles', function () {
    return view('test-modal-roles');
});

// Route de test JavaScript simple
Route::get('/test-javascript', function () {
    return view('test-javascript-simple');
});

// Route de test sans alertes modernes
Route::get('/test-sans-alertes', function () {
    return view('test-sans-alertes');
});

// Routes pour les activités
Route::resource('activites', App\Http\Controllers\ActiviteController::class);
Route::get('/activites/{activite}/presences', [App\Http\Controllers\ActiviteController::class, 'presences'])->name('activites.presences');
Route::post('/activites/{activite}/marquer-presence', [App\Http\Controllers\ActiviteController::class, 'marquerPresence'])->name('activites.marquer-presence');
Route::get('/activites/{activite}/statistiques', [App\Http\Controllers\ActiviteController::class, 'statistiques'])->name('activites.statistiques');

// Routes pour les événements
Route::resource('evenements', App\Http\Controllers\EvenementController::class);
Route::get('/evenements/{evenement}/participants', [App\Http\Controllers\EvenementController::class, 'participants'])->name('evenements.participants');
Route::post('/evenements/{evenement}/ajouter-participant', [App\Http\Controllers\EvenementController::class, 'ajouterParticipant'])->name('evenements.ajouter-participant');
Route::delete('/evenements/{evenement}/retirer-participant', [App\Http\Controllers\EvenementController::class, 'retirerParticipant'])->name('evenements.retirer-participant');
Route::get('/evenements/{evenement}/statistiques', [App\Http\Controllers\EvenementController::class, 'statistiques'])->name('evenements.statistiques');

// Route Gestion des Alertes
Route::get('/alertes', function () {
    return view('alertes.alertes');
})->name('alertes');

// Route de test pour vérifier que Laravel fonctionne
Route::get('/test-laravel', function () {
    return response()->json([
        'message' => 'Laravel fonctionne correctement',
        'version' => app()->version(),
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment()
    ]);
});

// Route pour vérifier la configuration
Route::get('/config-check', function () {
    return response()->json([
        'app_name' => config('app.name'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'database_connected' => true,
        'cache_driver' => config('cache.default'),
        'session_driver' => config('session.driver'),
        'queue_driver' => config('queue.default')
    ]);
});
