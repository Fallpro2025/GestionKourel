<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembreController extends Controller
{
    /**
     * Afficher la liste des membres
     */
    public function index()
    {
        $membres = Membre::with('role')->get();
        return view('membres.membres', compact('membres'));
    }

    /**
     * Afficher la liste moderne des membres
     */
    public function listeModerne()
    {
        $membres = Membre::with('role')->orderBy('created_at', 'desc')->get();
        $roles = \App\Models\Role::all();
        return view('membres.liste-membres-modern', compact('membres', 'roles'));
    }

    /**
     * Afficher la liste Vue.js des membres
     */
    public function listeVue()
    {
        $membres = Membre::with('role')->orderBy('created_at', 'desc')->get();
        return view('membres.membres-liste-modern', compact('membres'));
    }

    /**
     * Afficher le formulaire d'ajout de membre
     */
    public function create()
    {
        $roles = Role::all();
        return view('membres.create-simple', compact('roles'));
    }

    /**
     * Enregistrer un nouveau membre
     */
    public function store(Request $request)
    {
        try {
            // Log des données reçues pour débogage
            \Log::info('Données reçues pour création membre', [
                'donnees_recues' => $request->all(),
                'fichiers' => $request->allFiles(),
                'headers' => $request->headers->all()
            ]);
            
            // Validation des données
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'nullable|email|unique:membres,email',
                'telephone' => 'required|string|max:20|unique:membres,telephone',
                'date_naissance' => 'nullable|date',
                'adresse' => 'nullable|string|max:500',
                'role_id' => 'required|exists:roles,id',
                'date_inscription' => 'required|date',
                'statut' => 'required|in:actif,inactif,suspendu',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

        if ($validator->fails()) {
                \Log::error('Erreur de validation membre', [
                    'errors' => $validator->errors(),
                    'input' => $request->all()
                ]);
                
                // Retourner JSON pour les requêtes AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Traitement de l'image si fournie
        $photoPath = null;
        if ($request->hasFile('photo')) {
                try {
            $photoPath = $request->file('photo')->store('membres/photos', 'public');
                    \Log::info('Photo téléchargée avec succès', ['path' => $photoPath]);
                } catch (\Exception $e) {
                    \Log::error('Erreur téléchargement photo', ['error' => $e->getMessage()]);
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Erreur lors du téléchargement de la photo: ' . $e->getMessage()
                        ], 500);
                    }
                    
                    return redirect()->back()
                        ->with('error', 'Erreur lors du téléchargement de la photo: ' . $e->getMessage())
                        ->withInput();
                }
        }

        // Création du membre
        $membre = Membre::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'role_id' => $request->role_id,
            'date_adhesion' => $request->date_inscription,
            'statut' => $request->statut,
            'photo_url' => $photoPath
        ]);

            \Log::info('Membre créé avec succès', ['membre_id' => $membre->id]);

            // Retourner JSON pour les requêtes AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Membre ajouté avec succès !',
                    'membre' => $membre
                ]);
            }

        return redirect()->route('membres.index')
            ->with('success', 'Membre ajouté avec succès !');

        } catch (\Exception $e) {
            \Log::error('Erreur création membre', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            // Retourner JSON pour les requêtes AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du membre: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du membre: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'un membre
     */
    public function show(Membre $membre)
    {
        $membre->load('role', 'presences', 'activites');
        return view('membres.show', compact('membre'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Membre $membre)
    {
        $roles = Role::all();
        return view('membres.edit', compact('membre', 'roles'));
    }

    /**
     * Mettre à jour un membre
     */
    public function update(Request $request, Membre $membre)
    {
        try {
            // Log des données reçues pour débogage
            \Log::info('Données reçues pour mise à jour membre', [
                'membre_id' => $membre->id,
                'donnees_recues' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
        // Validation des données - Adaptée à la structure réelle de la base
        $validator = Validator::make($request->all(), [
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20|unique:membres,telephone,' . $membre->id,
            'email' => 'nullable|email|max:255|unique:membres,email,' . $membre->id,
            'statut' => 'nullable|string|in:actif,inactif,suspendu,ancien',
            'date_adhesion' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            '_token' => 'required|string'
        ]);

        if ($validator->fails()) {
                \Log::error('Erreur de validation membre update', [
                    'errors' => $validator->errors(),
                    'membre_id' => $membre->id,
                    'input' => $request->all(),
                    'validation_rules' => [
                        'prenom' => 'required|string|max:255',
                        'nom' => 'required|string|max:255',
                        'telephone' => 'required|string|max:20',
                        'email' => 'nullable|email|max:255',
                        'role' => 'nullable|string|max:100',
                        'statut' => 'nullable|string|in:Actif,Inactif,Suspendu',
                        'notes' => 'nullable|string|max:1000',
                        'created_at' => 'nullable|date',
                        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                        '_token' => 'required|string'
                    ]
                ]);
                
                // Retourner JSON pour les requêtes AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $validator->errors(),
                        'debug' => [
                            'received_data' => $request->all(),
                            'validation_failed' => true
                        ]
                    ], 422);
                }
                
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Traitement de l'image si fournie
            $photoPath = $membre->photo_url;
        if ($request->hasFile('photo')) {
                try {
            // Supprimer l'ancienne photo si elle existe
            if ($membre->photo_url) {
                \Storage::disk('public')->delete($membre->photo_url);
            }
            $photoPath = $request->file('photo')->store('membres/photos', 'public');
                    \Log::info('Photo mise à jour avec succès', ['path' => $photoPath]);
                } catch (\Exception $e) {
                    \Log::error('Erreur mise à jour photo', ['error' => $e->getMessage()]);
                    return redirect()->back()
                        ->with('error', 'Erreur lors de la mise à jour de la photo: ' . $e->getMessage())
                        ->withInput();
                }
        }

        // Mise à jour du membre - Adaptée à la structure réelle de la base
        $membre->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'statut' => strtolower($request->statut), // Convertir en minuscules pour la base
            'date_adhesion' => $request->date_adhesion ? new \DateTime($request->date_adhesion) : $membre->date_adhesion,
            'photo_url' => $photoPath
        ]);

            \Log::info('Membre mis à jour avec succès', ['membre_id' => $membre->id]);

            // Retourner JSON pour les requêtes AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Membre mis à jour avec succès !',
                    'membre' => $membre->fresh()
                ]);
            }

        return redirect()->route('membres.index')
            ->with('success', 'Membre mis à jour avec succès !');

        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour membre', [
                'error' => $e->getMessage(),
                'membre_id' => $membre->id,
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            // Retourner JSON pour les requêtes AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du membre: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du membre: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un membre
     */
    public function destroy(Membre $membre)
    {
        // Supprimer la photo si elle existe
        if ($membre->photo_url) {
            \Storage::disk('public')->delete($membre->photo_url);
        }

        $membre->delete();

        return redirect()->route('membres.index')
            ->with('success', 'Membre supprimé avec succès !');
    }

    /**
     * Exporter les membres
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        if ($format === 'csv') {
            return $this->exportCsv();
        }
        
        return redirect()->back()->with('error', 'Format d\'export non supporté');
    }

    /**
     * Export CSV
     */
    private function exportCsv()
    {
        $membres = Membre::with('role')->get();
        
        $filename = 'membres_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($membres) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 
                'Date de naissance', 'Adresse', 'Rôle', 'Statut', 
                'Date d\'inscription', 'Notes'
            ]);

            // Données
            foreach ($membres as $membre) {
                fputcsv($file, [
                    $membre->id,
                    $membre->nom,
                    $membre->prenom,
                    $membre->email,
                    $membre->telephone,
                    $membre->date_naissance->format('d/m/Y'),
                    $membre->adresse,
                    $membre->role->nom ?? 'N/A',
                    $membre->statut,
                    $membre->date_inscription->format('d/m/Y'),
                    $membre->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}