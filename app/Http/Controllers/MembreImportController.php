<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MembreImportController extends Controller
{
    /**
     * Afficher la page d'importation
     */
    public function index()
    {
        $roles = Role::all();
        return view('membres.import', compact('roles'));
    }

    /**
     * Télécharger le template Excel
     */
    public function downloadTemplate()
    {
        $templateData = [
            ['Nom', 'Prénom', 'Email', 'Téléphone', 'Date de naissance', 'Matricule', 'Profession', 'Niveau d\'étude', 'Adresse', 'Rôle', 'Statut', 'Date d\'adhésion'],
            ['Diop', 'Modou', 'modou.diop@email.com', '0123456789', '1990-05-15', 'MEM001', 'Ingénieur', 'Master', '123 Rue de la Paix, Dakar', 'Membre', 'actif', '2024-01-01'],
            ['Ba', 'Issa', 'issa.ba@email.com', '0987654321', '1985-12-03', 'MEM002', 'Designer', 'Licence', '456 Avenue des Champs, Thiès', 'Membre', 'actif', '2024-01-15'],
            ['Ndiaye', 'Ablaye', 'ablaye.ndiaye@email.com', '0555666777', '1992-08-20', 'MEM003', 'Développeur', 'Master', '789 Boulevard de la République, Saint-Louis', 'Administrateur', 'actif', '2024-02-01'],
            ['Sow', 'Moussa', 'moussa.sow@email.com', '0333444555', '1988-03-10', 'MEM004', 'Comptable', 'BTS', '321 Rue du Commerce, Kaolack', 'Membre', 'actif', '2024-02-15'],
            ['Fall', 'Cheikh', 'cheikh.fall@email.com', '0777888999', '1995-11-25', 'MEM005', 'Enseignant', 'Master', '654 Avenue de l\'Indépendance, Ziguinchor', 'Membre', 'actif', '2024-03-01'],
            ['Thiam', 'Amadou', 'amadou.thiam@email.com', '0444555666', '1987-07-12', 'MEM006', 'Médecin', 'Doctorat', '987 Rue de la Santé, Dakar', 'Membre', 'actif', '2024-03-15']
        ];

        $filename = 'template_import_membres_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = storage_path('app/temp/' . $filename);
        
        // Créer le dossier temp s'il n'existe pas
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $file = fopen($filepath, 'w');
        
        // Ajouter l'UTF-8 BOM pour Excel
        fwrite($file, "\xEF\xBB\xBF");
        
        foreach ($templateData as $row) {
            fputcsv($file, $row, ';');
        }
        
        fclose($file);

        return response()->download($filepath, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Traiter l'importation du fichier Excel
     */
    public function import(Request $request)
    {
        Log::info('=== DÉBUT IMPORTATION ===');
        Log::info('Données reçues:', $request->all());
        
        try {
            // Validation du fichier
            $validator = Validator::make($request->all(), [
                'fichier_excel' => 'required|file|max:10240', // 10MB max
            ], [
                'fichier_excel.required' => 'Veuillez sélectionner un fichier Excel.',
                'fichier_excel.file' => 'Le fichier sélectionné n\'est pas valide.',
                'fichier_excel.max' => 'Le fichier ne doit pas dépasser 10MB.',
            ]);

            if ($validator->fails()) {
                Log::error('Erreurs de validation:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('fichier_excel');
            
            // Validation manuelle du type de fichier
            $allowedExtensions = ['xlsx', 'xls', 'csv'];
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($extension, $allowedExtensions)) {
                Log::error('Extension de fichier non autorisée:', ['extension' => $extension]);
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier doit être au format Excel (.xlsx, .xls) ou CSV.',
                    'errors' => ['fichier_excel' => ['Le fichier doit être au format Excel (.xlsx, .xls) ou CSV.']]
                ], 422);
            }
            Log::info('Fichier reçu:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension()
            ]);
            
            $extension = $file->getClientOriginalExtension();
            
            // Sauvegarder le fichier temporairement avec une méthode plus directe
            $filename = 'import_' . time() . '.' . $extension;
            $fullPath = storage_path('app/temp/' . $filename);
            
            // Créer le dossier temp s'il n'existe pas
            $tempDir = dirname($fullPath);
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
                Log::info('Dossier temp créé:', ['path' => $tempDir]);
            }
            
            // Déplacer le fichier temporaire vers notre dossier
            $tempPath = $file->getPathname();
            if (!move_uploaded_file($tempPath, $fullPath)) {
                // Si move_uploaded_file échoue, essayer copy
                if (!copy($tempPath, $fullPath)) {
                    Log::error('Impossible de sauvegarder le fichier:', [
                        'source' => $tempPath,
                        'destination' => $fullPath,
                        'move_uploaded_file' => false,
                        'copy' => false
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la sauvegarde du fichier temporaire.'
                    ], 500);
                }
            }
            
            Log::info('Fichier sauvegardé:', [
                'source' => $tempPath,
                'destination' => $fullPath,
                'exists' => file_exists($fullPath),
                'size' => file_exists($fullPath) ? filesize($fullPath) : 'N/A'
            ]);

            // Vérifier que le fichier existe avant de le lire
            if (!file_exists($fullPath)) {
                Log::error('Fichier temporaire non trouvé après copie:', ['path' => $fullPath]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la sauvegarde du fichier temporaire.'
                ], 500);
            }

            // Lire le fichier selon son type
            if ($extension === 'csv') {
                // Traitement spécial pour les fichiers CSV
                $rows = [];
                if (($handle = fopen($fullPath, 'r')) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
                Log::info('Fichier CSV lu:', ['rows_count' => count($rows)]);
            } else {
                // Traitement pour les fichiers Excel
                $spreadsheet = IOFactory::load($fullPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                Log::info('Fichier Excel lu:', ['rows_count' => count($rows)]);
            }
            
            Log::info('Fichier lu:', [
                'rows_count' => count($rows),
                'first_row' => $rows[0] ?? 'vide'
            ]);

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le fichier Excel ne contient pas de données valides.'
                ], 422);
            }

            // Récupérer les en-têtes et nettoyer le BOM UTF-8
            $rawHeaders = $rows[0];
            $headers = [];
            foreach ($rawHeaders as $header) {
                // Nettoyer le BOM UTF-8 et autres caractères invisibles
                $cleanHeader = trim($header);
                $cleanHeader = preg_replace('/^\xEF\xBB\xBF/', '', $cleanHeader); // Supprimer BOM UTF-8
                $cleanHeader = preg_replace('/[\x00-\x1F\x7F]/', '', $cleanHeader); // Supprimer seulement les caractères de contrôle
                $headers[] = strtolower(trim($cleanHeader));
            }
            $dataRows = array_slice($rows, 1);
            
            Log::info('En-têtes détectés:', $headers);
            Log::info('En-têtes bruts:', $rawHeaders);

            // Valider les en-têtes requis (avec variations d'accents)
            $requiredHeaders = ['nom', 'prenom', 'email', 'telephone'];
            $headerVariations = [
                'nom' => ['nom', 'name'],
                'prenom' => ['prenom', 'prénom', 'prnom', 'firstname'],
                'email' => ['email', 'mail', 'e-mail'],
                'telephone' => ['telephone', 'téléphone', 'tlphone', 'tel', 'phone']
            ];
            
            $foundHeaders = [];
            foreach ($requiredHeaders as $required) {
                $found = false;
                foreach ($headerVariations[$required] as $variation) {
                    if (in_array($variation, $headers)) {
                        $foundHeaders[$required] = $variation;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $foundHeaders[$required] = null;
                }
            }
            
            $missingHeaders = array_keys(array_filter($foundHeaders, function($value) {
                return $value === null;
            }));
            
            if (!empty($missingHeaders)) {
                return response()->json([
                    'success' => false,
                    'message' => 'En-têtes manquants dans le fichier Excel : ' . implode(', ', $missingHeaders)
                ], 422);
            }

            // Traiter les données
            $membresValides = [];
            $erreurs = [];
            $roles = Role::pluck('nom', 'id')->toArray();

            foreach ($dataRows as $index => $row) {
                $rowNumber = $index + 2; // +2 car on commence à la ligne 2 (après les en-têtes)
                
                if (empty(array_filter($row))) {
                    continue; // Ignorer les lignes vides
                }

                $membreData = $this->processRowData($row, $headers, $roles, $foundHeaders);
                
                // Validation des données
                $validation = $this->validateMembreData($membreData, $rowNumber);
                
                if ($validation['valid']) {
                    $membresValides[] = $membreData;
                } else {
                    $erreurs = array_merge($erreurs, $validation['errors']);
                }
            }

            Log::info('Validation terminée:', [
                'membres_valides' => count($membresValides),
                'erreurs' => count($erreurs)
            ]);

            // Si des erreurs existent, les retourner
            if (!empty($erreurs)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation détectées',
                    'errors' => $erreurs,
                    'preview' => array_slice($membresValides, 0, 5) // Aperçu des 5 premiers membres valides
                ], 422);
            }

            // Si tout est valide, procéder à l'importation
            $importedCount = $this->importMembres($membresValides);

            // Supprimer le fichier temporaire
            if (file_exists($fullPath)) {
                unlink($fullPath);
                Log::info('Fichier temporaire supprimé:', ['path' => $fullPath]);
            }

            Log::info('Importation réussie:', ['count' => $importedCount]);

            return response()->json([
                'success' => true,
                'message' => "Importation réussie ! {$importedCount} membre(s) ajouté(s) avec succès.",
                'imported_count' => $importedCount
            ]);

        } catch (\Exception $e) {
            // Supprimer le fichier temporaire en cas d'erreur
            if (isset($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
                Log::info('Fichier temporaire supprimé après erreur:', ['path' => $fullPath]);
            }
            
            Log::error('Erreur importation Excel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'importation : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Traiter les données d'une ligne
     */
    private function processRowData($row, $headers, $roles, $foundHeaders = null)
    {
        $data = [];
        
        // Utiliser les en-têtes trouvés si disponibles
        $headerMapping = $foundHeaders ?: [
            'nom' => 'nom',
            'prenom' => 'prenom', 
            'email' => 'email',
            'telephone' => 'telephone'
        ];
        
        foreach ($headers as $index => $header) {
            $value = $row[$index] ?? '';
            
            // Mapper les en-têtes trouvés
            if (in_array($header, $headerMapping)) {
                $field = array_search($header, $headerMapping);
                switch ($field) {
                    case 'nom':
                        $data['nom'] = trim($value);
                        break;
                    case 'prenom':
                        $data['prenom'] = trim($value);
                        break;
                    case 'email':
                        $data['email'] = trim($value);
                        break;
                    case 'telephone':
                        $data['telephone'] = trim($value);
                        break;
                }
            }
            
            // Traitement des autres champs optionnels
            switch ($header) {
                case 'date de naissance':
                case 'date de naissance':
                    $data['date_naissance'] = $this->parseDate($value);
                    break;
                case 'matricule':
                    $data['matricule'] = trim($value);
                    break;
                case 'profession':
                    $data['profession'] = trim($value);
                    break;
                case 'niveau d\'étude':
                case 'niveau detude':
                    $data['niveau_etude'] = trim($value);
                    break;
                case 'adresse':
                    $data['adresse'] = trim($value);
                    break;
                case 'rôle':
                case 'role':
                    $data['role_nom'] = trim($value);
                    break;
                case 'statut':
                    $data['statut'] = strtolower(trim($value));
                    break;
                case 'date d\'adhésion':
                case 'date dadhesion':
                    $data['date_adhesion'] = $this->parseDate($value);
                    break;
            }
        }

        return $data;
    }

    /**
     * Parser une date depuis Excel
     */
    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Si c'est un nombre (format Excel), le convertir
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Essayer de parser la date
        try {
            $date = \DateTime::createFromFormat('Y-m-d', $value);
            if ($date) {
                return $date->format('Y-m-d');
            }
            
            $date = \DateTime::createFromFormat('d/m/Y', $value);
            if ($date) {
                return $date->format('Y-m-d');
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs de parsing
        }

        return null;
    }

    /**
     * Valider les données d'un membre
     */
    private function validateMembreData($data, $rowNumber)
    {
        $errors = [];
        
        // Validation des champs requis
        if (empty($data['nom'])) {
            $errors[] = "Ligne {$rowNumber}: Le nom est requis";
        }
        
        if (empty($data['prenom'])) {
            $errors[] = "Ligne {$rowNumber}: Le prénom est requis";
        }
        
        if (empty($data['email'])) {
            $errors[] = "Ligne {$rowNumber}: L'email est requis";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Ligne {$rowNumber}: L'email n'est pas valide";
        }
        
        if (empty($data['telephone'])) {
            $errors[] = "Ligne {$rowNumber}: Le téléphone est requis";
        }

        // Validation du statut
        if (!empty($data['statut']) && !in_array($data['statut'], ['actif', 'inactif', 'suspendu'])) {
            $errors[] = "Ligne {$rowNumber}: Le statut doit être 'actif', 'inactif' ou 'suspendu'";
        }

        // Validation de l'unicité de l'email
        if (!empty($data['email']) && Membre::where('email', $data['email'])->exists()) {
            $errors[] = "Ligne {$rowNumber}: L'email '{$data['email']}' existe déjà";
        }

        // Validation de l'unicité du téléphone
        if (!empty($data['telephone']) && Membre::where('telephone', $data['telephone'])->exists()) {
            $errors[] = "Ligne {$rowNumber}: Le téléphone '{$data['telephone']}' existe déjà";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Importer les membres validés
     */
    private function importMembres($membresData)
    {
        $importedCount = 0;
        $roles = Role::pluck('id', 'nom')->toArray();

        foreach ($membresData as $data) {
            try {
                // Déterminer le rôle
                $roleId = null;
                if (!empty($data['role_nom'])) {
                    $roleId = $roles[$data['role_nom']] ?? null;
                }
                
                // Si aucun rôle spécifié, prendre le premier rôle par défaut
                if (!$roleId) {
                    $roleId = Role::first()->id ?? null;
                }

                // Créer le membre
                $membre = Membre::create([
                    'nom' => $data['nom'],
                    'prenom' => $data['prenom'],
                    'email' => $data['email'],
                    'telephone' => $data['telephone'],
                    'date_naissance' => $data['date_naissance'],
                    'matricule' => $data['matricule'],
                    'profession' => $data['profession'],
                    'niveau_etude' => $data['niveau_etude'],
                    'adresse' => $data['adresse'],
                    'role_id' => $roleId,
                    'statut' => $data['statut'] ?? 'actif',
                    'date_adhesion' => $data['date_adhesion'] ?? now()->format('Y-m-d'),
                ]);

                $importedCount++;
                
                Log::info('Membre importé avec succès', [
                    'membre_id' => $membre->id,
                    'nom' => $membre->nom,
                    'email' => $membre->email
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'importation d\'un membre', [
                    'data' => $data,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $importedCount;
    }
}
