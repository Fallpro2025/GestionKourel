<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Role;
use App\Models\MembreRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MembreRoleController extends Controller
{
    /**
     * Afficher les rôles d'un membre
     */
    public function index(Membre $membre)
    {
        $membre->load(['roles' => function($query) {
            $query->withPivot(['est_principal', 'date_attribution', 'notes']);
        }]);
        $rolesDisponibles = Role::all();
        
        return view('membres.roles', compact('membre', 'rolesDisponibles'));
    }

    /**
     * Ajouter un rôle à un membre
     */
    public function store(Request $request, $membreId)
    {
        try {
            // Récupérer le membre
            $membre = Membre::find($membreId);
            if (!$membre) {
                return response()->json(['success' => false, 'message' => 'Membre non trouvé'], 404);
            }
            
            // Récupérer le rôle
            $roleId = $request->input('role_id');
            if (!$roleId) {
                return response()->json(['success' => false, 'message' => 'Rôle requis'], 422);
            }
            
            $role = Role::find($roleId);
            if (!$role) {
                return response()->json(['success' => false, 'message' => 'Rôle non trouvé'], 404);
            }
            
            // Vérifier si le membre a déjà ce rôle
            $roleExiste = DB::table('membre_role')
                ->where('membre_id', $membre->id)
                ->where('role_id', $roleId)
                ->exists();
                
            if ($roleExiste) {
                return response()->json(['success' => false, 'message' => 'Ce membre a déjà ce rôle'], 400);
            }
            
            // Ajouter le rôle
            $membre->roles()->attach($roleId, [
                'est_principal' => $request->input('est_principal', false),
                'date_attribution' => now(),
                'notes' => $request->input('notes', '')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rôle ajouté avec succès'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erreur ajout rôle', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un rôle d'un membre
     */
    public function update(Request $request, Membre $membre, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'est_principal' => 'boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Log::info('Modification rôle', [
                'membre_id' => $membre->id,
                'role_id' => $role->id,
                'est_principal' => $request->est_principal,
                'notes' => $request->notes
            ]);
            
            // Si on définit ce rôle comme principal, retirer le statut des autres
            if ($request->est_principal) {
                DB::table('membre_role')
                    ->where('membre_id', $membre->id)
                    ->where('role_id', '!=', $role->id)
                    ->update(['est_principal' => false]);
            }

            // Mettre à jour le rôle
            $membre->roles()->updateExistingPivot($role->id, [
                'est_principal' => $request->est_principal ?? false,
                'notes' => $request->notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rôle mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur modification rôle', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du rôle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un rôle d'un membre
     */
    public function destroy(Membre $membre, Role $role)
    {
        try {
            \Log::info('Suppression rôle', [
                'membre_id' => $membre->id,
                'role_id' => $role->id
            ]);
            
            $membre->roles()->detach($role->id);

            return response()->json([
                'success' => true,
                'message' => 'Rôle supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur suppression rôle', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du rôle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Définir un rôle comme principal
     */
    public function definirPrincipal(Membre $membre, Role $role)
    {
        try {
            $membre->definirRolePrincipal($role->id);

            return response()->json([
                'success' => true,
                'message' => 'Rôle principal défini avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la définition du rôle principal'
            ], 500);
        }
    }
}
