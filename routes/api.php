<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route de test simple
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

// Route API pour récupérer les membres
Route::get('/membres', function () {
    $membres = \App\Models\Membre::with('role')->get();
    
    return response()->json($membres->map(function($membre) {
        return [
            'id' => $membre->id,
            'nom' => $membre->nom,
            'prenom' => $membre->prenom,
            'email' => $membre->email,
            'telephone' => $membre->telephone,
            'date_adhesion' => $membre->date_adhesion,
            'statut' => $membre->statut,
            'matricule' => $membre->matricule,
            'profession' => $membre->profession,
            'competences' => $membre->competences,
            'taux_presence' => $membre->calculerTauxPresence(),
            'role' => $membre->role ? $membre->role->nom : null,
            'created_at' => $membre->created_at,
            'updated_at' => $membre->updated_at
        ];
    }));
});