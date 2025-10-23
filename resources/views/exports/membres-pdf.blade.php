<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Membres - Gestion Kourel</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #4472C4;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        
        .header p {
            color: #666;
            font-size: 12px;
            margin: 5px 0 0 0;
        }
        
        .info-export {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #4472C4;
        }
        
        .info-export p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background-color: #4472C4;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 8px;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .statut {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .statut-actif {
            background-color: #d4edda;
            color: #155724;
        }
        
        .statut-inactif {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .statut-suspendu {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .summary {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary h3 {
            color: #4472C4;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .summary-item .number {
            font-size: 18px;
            font-weight: bold;
            color: #4472C4;
        }
        
        .summary-item .label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>Gestion Kourel</h1>
        <p>Export des Membres</p>
        <p>Généré le {{ $date_export }}</p>
    </div>

    <!-- Informations d'export -->
    <div class="info-export">
        <p><strong>Total des membres exportés :</strong> {{ $total }}</p>
        <p><strong>Date d'export :</strong> {{ $date_export }}</p>
        <p><strong>Système :</strong> Gestion Kourel v1.0</p>
    </div>

    <!-- Résumé statistique -->
    <div class="summary">
        <h3>Résumé Statistique</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="number">{{ $membres->where('statut', 'actif')->count() }}</div>
                <div class="label">Membres Actifs</div>
            </div>
            <div class="summary-item">
                <div class="number">{{ $membres->where('statut', 'inactif')->count() }}</div>
                <div class="label">Membres Inactifs</div>
            </div>
            <div class="summary-item">
                <div class="number">{{ $membres->where('statut', 'suspendu')->count() }}</div>
                <div class="label">Membres Suspendus</div>
            </div>
            <div class="summary-item">
                <div class="number">{{ $membres->whereNotNull('photo_url')->count() }}</div>
                <div class="label">Avec Photo</div>
            </div>
        </div>
    </div>

    <!-- Tableau des membres -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 12%;">Nom</th>
                <th style="width: 12%;">Prénom</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 10%;">Téléphone</th>
                <th style="width: 8%;">Rôle</th>
                <th style="width: 6%;">Statut</th>
                <th style="width: 8%;">Date Adhésion</th>
                <th style="width: 10%;">Profession</th>
                <th style="width: 4%;">Photo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($membres as $membre)
            <tr>
                <td>{{ $membre->id }}</td>
                <td>{{ $membre->nom }}</td>
                <td>{{ $membre->prenom }}</td>
                <td>{{ $membre->email ?: 'N/A' }}</td>
                <td>{{ $membre->telephone }}</td>
                <td>{{ $membre->role->nom ?? 'N/A' }}</td>
                <td>
                    <span class="statut statut-{{ $membre->statut }}">
                        {{ ucfirst($membre->statut) }}
                    </span>
                </td>
                <td>{{ $membre->date_adhesion->format('d/m/Y') }}</td>
                <td>{{ $membre->profession ?: 'N/A' }}</td>
                <td style="text-align: center;">{{ $membre->photo_url ? '✓' : '✗' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #666;">
                    Aucun membre trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré automatiquement par le système Gestion Kourel</p>
        <p>© {{ date('Y') }} Gestion Kourel - Tous droits réservés</p>
    </div>
</body>
</html>
