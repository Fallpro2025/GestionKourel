<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Afficher la liste des rôles
     */
    public function index()
    {
        $roles = Role::withCount('membres')->orderBy('niveau_priorite', 'desc')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $permissionsDisponibles = $this->getPermissionsDisponibles();
        return view('roles.create', compact('permissionsDisponibles'));
    }

    /**
     * Enregistrer un nouveau rôle
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50|unique:roles,nom',
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string',
            'niveau_priorite' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::create([
                'nom' => $request->nom,
                'description' => $request->description,
                'permissions' => $request->permissions,
                'niveau_priorite' => $request->niveau_priorite
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rôle créé avec succès',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du rôle'
            ], 500);
        }
    }

    /**
     * Afficher un rôle spécifique
     */
    public function show(Role $role)
    {
        $role->load('membres');
        return view('roles.show', compact('role'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Role $role)
    {
        $permissionsDisponibles = $this->getPermissionsDisponibles();
        return view('roles.edit', compact('role', 'permissionsDisponibles'));
    }

    /**
     * Mettre à jour un rôle
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:50|unique:roles,nom,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string',
            'niveau_priorite' => 'required|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role->update([
                'nom' => $request->nom,
                'description' => $request->description,
                'permissions' => $request->permissions,
                'niveau_priorite' => $request->niveau_priorite
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rôle modifié avec succès',
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du rôle'
            ], 500);
        }
    }

    /**
     * Supprimer un rôle
     */
    public function destroy(Role $role)
    {
        try {
            // Vérifier si des membres ont ce rôle
            if ($role->membres()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce rôle car des membres l\'utilisent encore'
                ], 400);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rôle supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du rôle'
            ], 500);
        }
    }

    /**
     * Obtenir la liste des permissions disponibles
     */
    private function getPermissionsDisponibles(): array
    {
        return [
            'voir_profil' => 'Voir les profils des membres',
            'gestion_membres' => 'Gérer les membres',
            'gestion_cotisations' => 'Gérer les cotisations',
            'gestion_evenements' => 'Gérer les événements',
            'gestion_finances' => 'Gérer les finances',
            'gestion_documents' => 'Gérer les documents',
            'gestion_activites' => 'Gérer les activités',
            'animer_activites' => 'Animer les activités',
            'participer_chorale' => 'Participer à la chorale',
            'participation_repetitions' => 'Participer aux répétitions',
            'participation_concerts' => 'Participer aux concerts',
            'interpretation_solos' => 'Interpréter des solos',
            'interpretation_instrumentale' => 'Interpréter des instruments',
            'gestion_section' => 'Gérer une section',
            'coordination_activites' => 'Coordonner les activités',
            'gestion_technique' => 'Gérer la technique',
            'maintenance_equipements' => 'Maintenir les équipements',
            'administration_generale' => 'Administration générale'
        ];
    }
}
