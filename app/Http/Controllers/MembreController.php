<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Role;
use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class MembreController extends Controller
{
    /**
     * Afficher la liste des membres
     */
    public function index()
    {
        $membres = Membre::with('role')->orderBy('created_at', 'desc')->get();
        $roles = \App\Models\Role::all();
        return view('membres.index', compact('membres', 'roles'));
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

        // Enregistrer dans l'historique
        Historique::enregistrer(
            $membre,
            'created',
            "Nouveau membre créé : {$membre->prenom} {$membre->nom}",
            null,
            $membre->toArray()
        );

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
        $membre->load('role', 'roles');
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
            
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:membres,email,' . $membre->id,
            'telephone' => 'required|string|max:20|unique:membres,telephone,' . $membre->id,
            'date_naissance' => 'nullable|date',
            'matricule' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'niveau_etude' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:500',
            'role_id' => 'required|exists:roles,id',
            'statut' => 'required|string|in:actif,inactif,suspendu',
            'date_adhesion' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
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

        // Sauvegarder les données avant modification
        $donneesAvant = $membre->toArray();

        // Mise à jour du membre
        $membre->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'date_naissance' => $request->date_naissance,
            'matricule' => $request->matricule,
            'profession' => $request->profession,
            'niveau_etude' => $request->niveau_etude,
            'adresse' => $request->adresse,
            'role_id' => $request->role_id,
            'statut' => $request->statut,
            'date_adhesion' => $request->date_adhesion,
            'photo_url' => $photoPath
        ]);

        // Enregistrer dans l'historique
        Historique::enregistrer(
            $membre,
            'updated',
            "Membre modifié : {$membre->prenom} {$membre->nom}",
            $donneesAvant,
            $membre->fresh()->toArray()
        );

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
        // Enregistrer dans l'historique avant suppression
        Historique::enregistrer(
            $membre,
            'deleted',
            "Membre supprimé : {$membre->prenom} {$membre->nom}",
            $membre->toArray(),
            null
        );

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
    
    /**
     * Obtenir les statistiques avancées des membres
     */
    public function statistiques()
    {
        $stats = [
            'total' => Membre::count(),
            'actifs' => Membre::where('statut', 'actif')->count(),
            'inactifs' => Membre::where('statut', 'inactif')->count(),
            'suspendus' => Membre::where('statut', 'suspendu')->count(),
            'nouveaux_mois' => Membre::where('created_at', '>=', now()->startOfMonth())->count(),
            'nouveaux_semaine' => Membre::where('created_at', '>=', now()->startOfWeek())->count(),
            'par_role' => Membre::with('role')
                ->selectRaw('role_id, count(*) as count')
                ->groupBy('role_id')
                ->get()
                ->map(function($item) {
                    return [
                        'role' => $item->role->nom ?? 'Aucun rôle',
                        'count' => $item->count
                    ];
                }),
            'par_profession' => Membre::selectRaw('profession, count(*) as count')
                ->whereNotNull('profession')
                ->groupBy('profession')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'age_moyen' => Membre::whereNotNull('date_naissance')
                ->selectRaw('AVG(YEAR(CURDATE()) - YEAR(date_naissance)) as age_moyen')
                ->first()
                ->age_moyen ?? 0,
            'avec_email' => Membre::whereNotNull('email')->count(),
            'avec_telephone' => Membre::whereNotNull('telephone')->count(),
            'avec_photo' => Membre::whereNotNull('photo_url')->count()
        ];
        
        return view('membres.statistiques', compact('stats'));
    }
    
    /**
     * Obtenir les statistiques en JSON (API)
     */
    public function statistiquesApi()
    {
        $stats = [
            'total' => Membre::count(),
            'actifs' => Membre::where('statut', 'actif')->count(),
            'inactifs' => Membre::where('statut', 'inactif')->count(),
            'suspendus' => Membre::where('statut', 'suspendu')->count(),
            'nouveaux_mois' => Membre::where('created_at', '>=', now()->startOfMonth())->count(),
            'nouveaux_semaine' => Membre::where('created_at', '>=', now()->startOfWeek())->count(),
            'par_role' => Membre::with('role')
                ->selectRaw('role_id, count(*) as count')
                ->groupBy('role_id')
                ->get()
                ->map(function($item) {
                    return [
                        'role' => $item->role->nom ?? 'Aucun rôle',
                        'count' => $item->count
                    ];
                }),
            'par_profession' => Membre::selectRaw('profession, count(*) as count')
                ->whereNotNull('profession')
                ->groupBy('profession')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'age_moyen' => Membre::whereNotNull('date_naissance')
                ->selectRaw('AVG(YEAR(CURDATE()) - YEAR(date_naissance)) as age_moyen')
                ->first()
                ->age_moyen ?? 0,
            'avec_email' => Membre::whereNotNull('email')->count(),
            'avec_telephone' => Membre::whereNotNull('telephone')->count(),
            'avec_photo' => Membre::whereNotNull('photo_url')->count()
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Obtenir les membres avec des filtres avancés
     */
    public function rechercheAvancee(Request $request)
    {
        $query = Membre::with('role');
        
        // Filtres de base
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('profession', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('profession')) {
            $query->where('profession', 'like', "%{$request->profession}%");
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $membres = $query->paginate($perPage);
        
        return response()->json($membres);
    }
    
    /**
     * Upload de photo pour un membre
     */
    public function uploadPhoto(Request $request, Membre $membre)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            // Supprimer l'ancienne photo si elle existe
            if ($membre->photo_url) {
                \Storage::disk('public')->delete($membre->photo_url);
            }

            // Sauvegarder la nouvelle photo
            $photoPath = $request->file('photo')->store('membres/photos', 'public');
            
            $membre->update(['photo_url' => $photoPath]);

            return response()->json([
                'success' => true,
                'message' => 'Photo mise à jour avec succès',
                'photo_url' => asset('storage/' . $photoPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload de la photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Servir la photo d'un membre
     */
    public function servePhoto(Membre $membre)
    {
        if (!$membre->photo_url || !\Storage::disk('public')->exists($membre->photo_url)) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $membre->photo_url);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * Supprimer la photo d'un membre
     */
    public function deletePhoto(Membre $membre)
    {
        try {
            if ($membre->photo_url) {
                \Storage::disk('public')->delete($membre->photo_url);
                $membre->update(['photo_url' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Photo supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher l'historique d'un membre
     */
    public function historique(Membre $membre)
    {
        $historique = Historique::pourModele(get_class($membre), $membre->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('membres.historique', compact('membre', 'historique'));
    }

    /**
     * Exporter un membre en PDF
     */
    public function exportPdf(Membre $membre)
    {
        try {
            // Charger les relations nécessaires de manière sécurisée
            $membre->load('role');
            
            // Charger les rôles si la relation existe
            if (method_exists($membre, 'roles')) {
                $membre->load('roles');
            }
            
            // Obtenir l'historique récent de manière sécurisée
            $historique = collect([]);
            try {
                if (class_exists('App\Models\Historique')) {
                    $historique = Historique::pourModele(get_class($membre), $membre->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                }
            } catch (\Exception $e) {
                \Log::warning('Impossible de charger l\'historique pour l\'export PDF', [
                    'membre_id' => $membre->id,
                    'error' => $e->getMessage()
                ]);
            }

            $data = [
                'membre' => $membre,
                'historique' => $historique,
                'date_export' => now()->format('d/m/Y à H:i'),
                'exporteur' => 'Administrateur'
            ];

            $pdf = Pdf::loadView('exports.membre-pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            
            $filename = 'membre_' . strtolower(str_replace(' ', '_', $membre->nom . '_' . $membre->prenom)) . '_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Erreur export PDF membre', [
                'membre_id' => $membre->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export PDF: ' . $e->getMessage());
        }
    }
}