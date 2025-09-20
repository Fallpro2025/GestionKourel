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

// Route principale - Application simple
Route::get('/', function () {
    return view('app-simple');
})->name('home');

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
