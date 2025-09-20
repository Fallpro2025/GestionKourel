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

// Route Dashboard (temporairement désactivée)
Route::get('/dashboard', function () {
    return view('dashboard-modern');
})->name('dashboard');

// Routes Gestion des Membres
Route::get('/membres', [App\Http\Controllers\MembreController::class, 'index'])->name('membres.index');
Route::get('/membres-liste', [App\Http\Controllers\MembreController::class, 'listeModerne'])->name('membres.liste-moderne');
Route::get('/membres-vue', [App\Http\Controllers\MembreController::class, 'listeVue'])->name('membres.liste-vue');
Route::get('/membres/create', [App\Http\Controllers\MembreController::class, 'create'])->name('membres.create');
Route::post('/membres', [App\Http\Controllers\MembreController::class, 'store'])->name('membres.store');
Route::get('/membres/{membre}', [App\Http\Controllers\MembreController::class, 'show'])->name('membres.show');
Route::get('/membres/{membre}/edit', [App\Http\Controllers\MembreController::class, 'edit'])->name('membres.edit');
Route::put('/membres/{membre}', [App\Http\Controllers\MembreController::class, 'update'])->name('membres.update');
Route::delete('/membres/{membre}', [App\Http\Controllers\MembreController::class, 'destroy'])->name('membres.destroy');
Route::get('/membres/export', [App\Http\Controllers\MembreController::class, 'export'])->name('membres.export');


// Route Gestion des Cotisations
Route::get('/cotisations', function () {
    return view('cotisations.cotisations');
})->name('cotisations');

// Route Gestion des Activités
Route::get('/activites', function () {
    return view('activites.activites');
})->name('activites');

// Route Gestion des Événements
Route::get('/evenements', function () {
    return view('evenements.evenements');
})->name('evenements');

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
