<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;

class MembreExportController extends Controller
{
    /**
     * Export CSV des membres
     */
    public function exportCsv(Request $request)
    {
        try {
            $membres = $this->getMembresFiltres($request);
            
            $filename = 'membres_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($membres) {
                $file = fopen('php://output', 'w');
                
                // Ajouter BOM UTF-8 pour Excel
                fwrite($file, "\xEF\xBB\xBF");
                
                // En-têtes CSV
                fputcsv($file, [
                    'ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 
                    'Date de naissance', 'Matricule', 'Profession', 'Niveau d\'étude',
                    'Adresse', 'Rôle', 'Statut', 'Date d\'adhésion', 'Photo'
                ]);

                // Données
                foreach ($membres as $membre) {
                    fputcsv($file, [
                        $membre->id,
                        $membre->nom,
                        $membre->prenom,
                        $membre->email,
                        $membre->telephone,
                        $membre->date_naissance ? $membre->date_naissance->format('d/m/Y') : '',
                        $membre->matricule,
                        $membre->profession,
                        $membre->niveau_etude,
                        $membre->adresse,
                        $membre->role->nom ?? 'N/A',
                        ucfirst($membre->statut),
                        $membre->date_adhesion->format('d/m/Y'),
                        $membre->photo_url ? 'Oui' : 'Non'
                    ]);
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Erreur export CSV', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur lors de l\'export CSV'], 500);
        }
    }

    /**
     * Export Excel des membres
     */
    public function exportExcel(Request $request)
    {
        try {
            $membres = $this->getMembresFiltres($request);
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Titre
            $sheet->setTitle('Membres Kourel');
            
            // En-têtes
            $headers = [
                'A1' => 'ID', 'B1' => 'Nom', 'C1' => 'Prénom', 'D1' => 'Email', 'E1' => 'Téléphone',
                'F1' => 'Date de naissance', 'G1' => 'Matricule', 'H1' => 'Profession', 'I1' => 'Niveau d\'étude',
                'J1' => 'Adresse', 'K1' => 'Rôle', 'L1' => 'Statut', 'M1' => 'Date d\'adhésion', 'N1' => 'Photo'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style des en-têtes
            $headerRange = 'A1:N1';
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            
            // Données
            $row = 2;
            foreach ($membres as $membre) {
                $sheet->setCellValue('A' . $row, $membre->id);
                $sheet->setCellValue('B' . $row, $membre->nom);
                $sheet->setCellValue('C' . $row, $membre->prenom);
                $sheet->setCellValue('D' . $row, $membre->email);
                $sheet->setCellValue('E' . $row, $membre->telephone);
                $sheet->setCellValue('F' . $row, $membre->date_naissance ? $membre->date_naissance->format('d/m/Y') : '');
                $sheet->setCellValue('G' . $row, $membre->matricule);
                $sheet->setCellValue('H' . $row, $membre->profession);
                $sheet->setCellValue('I' . $row, $membre->niveau_etude);
                $sheet->setCellValue('J' . $row, $membre->adresse);
                $sheet->setCellValue('K' . $row, $membre->role->nom ?? 'N/A');
                $sheet->setCellValue('L' . $row, ucfirst($membre->statut));
                $sheet->setCellValue('M' . $row, $membre->date_adhesion->format('d/m/Y'));
                $sheet->setCellValue('N' . $row, $membre->photo_url ? 'Oui' : 'Non');
                $row++;
            }
            
            // Ajuster la largeur des colonnes
            foreach (range('A', 'N') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Bordures pour toutes les données
            $dataRange = 'A1:N' . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            
            // Nom du fichier
            $filename = 'membres_kourel_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Sauvegarder temporairement
            $tempFile = tempnam(sys_get_temp_dir(), 'membres_');
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('Erreur export Excel', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur lors de l\'export Excel'], 500);
        }
    }

    /**
     * Export PDF des membres
     */
    public function exportPdf(Request $request)
    {
        try {
            $membres = $this->getMembresFiltres($request);
            
            $data = [
                'membres' => $membres,
                'date_export' => now()->format('d/m/Y à H:i'),
                'total' => $membres->count()
            ];
            
            $pdf = Pdf::loadView('exports.membres-pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            
            $filename = 'membres_kourel_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Erreur export PDF', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur lors de l\'export PDF'], 500);
        }
    }

    /**
     * Obtenir les membres avec filtres
     */
    private function getMembresFiltres(Request $request)
    {
        $query = Membre::with('role');
        
        // Appliquer les filtres
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
        
        if ($request->filled('date_adhesion_debut')) {
            $query->where('date_adhesion', '>=', $request->date_adhesion_debut);
        }
        
        if ($request->filled('date_adhesion_fin')) {
            $query->where('date_adhesion', '<=', $request->date_adhesion_fin);
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        return $query->get();
    }
}