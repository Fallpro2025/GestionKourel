<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class AuthController extends Controller
{
    /**
     * Authentification d'un membre
     */
    public function login(Request $request)
    {
        // Rate limiting pour la sécurité
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => "Trop de tentatives de connexion. Réessayez dans {$seconds} secondes.",
                'errors' => ['email' => ['Trop de tentatives']]
            ], 429);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean'
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($key, 300); // 5 minutes
            return response()->json([
                'message' => 'Données de connexion invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($key);
            
            $membre = Auth::user();
            $token = $membre->createToken('auth-token')->plainTextToken;
            
            event(new Login('web', $membre, $remember));

            return response()->json([
                'message' => 'Connexion réussie',
                'membre' => [
                    'id' => $membre->id,
                    'nom' => $membre->nom,
                    'prenom' => $membre->prenom,
                    'email' => $membre->email,
                    'role' => $membre->role,
                    'photo_url' => $membre->photo_url,
                    'statut' => $membre->statut
                ],
                'token' => $token,
                'expires_at' => now()->addDays(7)->toISOString()
            ]);
        }

        RateLimiter::hit($key, 300);
        return response()->json([
            'message' => 'Identifiants incorrects',
            'errors' => ['email' => ['Email ou mot de passe incorrect']]
        ], 401);
    }

    /**
     * Déconnexion d'un membre
     */
    public function logout(Request $request)
    {
        $membre = Auth::user();
        
        // Révoquer le token actuel
        $request->user()->currentAccessToken()->delete();
        
        event(new Logout('web', $membre));

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Obtenir les informations du membre connecté
     */
    public function me(Request $request)
    {
        $membre = $request->user();
        
        return response()->json([
            'membre' => [
                'id' => $membre->id,
                'nom' => $membre->nom,
                'prenom' => $membre->prenom,
                'nom_complet' => $membre->nom_complet,
                'email' => $membre->email,
                'telephone' => $membre->telephone,
                'date_naissance' => $membre->date_naissance,
                'date_adhesion' => $membre->date_adhesion,
                'statut' => $membre->statut,
                'role' => $membre->role,
                'photo_url' => $membre->photo_url,
                'profession' => $membre->profession,
                'niveau_etude' => $membre->niveau_etude,
                'competences' => $membre->competences,
                'disponibilites' => $membre->disponibilites,
                'preferences_notifications' => $membre->preferences_notifications,
                'age' => $membre->age,
                'taux_presence' => $membre->calculerTauxPresence(),
                'montant_cotisations_retard' => $membre->getMontantCotisationsRetard()
            ]
        ]);
    }

    /**
     * Rafraîchir le token d'authentification
     */
    public function refresh(Request $request)
    {
        $membre = $request->user();
        
        // Révoquer l'ancien token
        $request->user()->currentAccessToken()->delete();
        
        // Créer un nouveau token
        $token = $membre->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Token rafraîchi avec succès',
            'token' => $token,
            'expires_at' => now()->addDays(7)->toISOString()
        ]);
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $membre = $request->user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $membre->password)) {
            return response()->json([
                'message' => 'Mot de passe actuel incorrect',
                'errors' => ['current_password' => ['Le mot de passe actuel est incorrect']]
            ], 422);
        }

        // Mettre à jour le mot de passe
        $membre->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }

    /**
     * Demander une réinitialisation de mot de passe
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:membres,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Email invalide ou inexistant',
                'errors' => $validator->errors()
            ], 422);
        }

        $membre = Membre::where('email', $request->email)->first();
        
        // Générer un token de réinitialisation
        $token = \Str::random(64);
        
        // Stocker le token (dans une table de tokens de réinitialisation)
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $membre->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Envoyer l'email de réinitialisation
        // TODO: Implémenter l'envoi d'email
        
        return response()->json([
            'message' => 'Email de réinitialisation envoyé',
            'token' => $token // En développement seulement
        ]);
    }

    /**
     * Réinitialiser le mot de passe avec un token
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:membres,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier le token
        $passwordReset = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'message' => 'Token invalide ou expiré',
                'errors' => ['token' => ['Token invalide']]
            ], 422);
        }

        // Vérifier que le token n'est pas trop ancien (1 heure)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'message' => 'Token expiré',
                'errors' => ['token' => ['Token expiré']]
            ], 422);
        }

        // Mettre à jour le mot de passe
        $membre = Membre::where('email', $request->email)->first();
        $membre->update([
            'password' => Hash::make($request->password)
        ]);

        // Supprimer le token utilisé
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Mot de passe réinitialisé avec succès'
        ]);
    }

    /**
     * Vérifier si l'utilisateur est authentifié
     */
    public function checkAuth(Request $request)
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'membre' => [
                    'id' => Auth::user()->id,
                    'nom_complet' => Auth::user()->nom_complet,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }
}
