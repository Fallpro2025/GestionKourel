<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Membre - {{ $membre->nom }} {{ $membre->prenom }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4472C4;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #4472C4;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        
        .info-export {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #4472C4;
        }
        
        .info-export p {
            margin: 3px 0;
            font-size: 10px;
        }
        
        .membre-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background-color: #4472C4;
            color: white;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        
        .membre-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .membre-photo {
            display: table-cell;
            width: 120px;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .membre-details {
            display: table-cell;
            vertical-align: top;
        }
        
        .photo-placeholder {
            width: 100px;
            height: 100px;
            border: 2px solid #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            font-size: 24px;
            color: #666;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 5px 10px 5px 0;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        
        .statut-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
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
        
        .roles-section {
            margin-top: 20px;
        }
        
        .role-item {
            background-color: #f8f9fa;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 5px;
            border-left: 4px solid #4472C4;
        }
        
        .role-name {
            font-weight: bold;
            color: #4472C4;
        }
        
        .role-details {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        
        .historique-section {
            margin-top: 25px;
        }
        
        .historique-item {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }
        
        .historique-item:last-child {
            border-bottom: none;
        }
        
        .historique-date {
            font-size: 9px;
            color: #666;
            font-weight: bold;
        }
        
        .historique-action {
            font-weight: bold;
            color: #333;
            margin: 2px 0;
        }
        
        .historique-description {
            font-size: 9px;
            color: #666;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .competences-section {
            margin-top: 20px;
        }
        
        .competence-tag {
            display: inline-block;
            background-color: #e7f3ff;
            color: #4472C4;
            padding: 3px 8px;
            margin: 2px 4px 2px 0;
            border-radius: 12px;
            font-size: 9px;
            border: 1px solid #b3d9ff;
        }
        
        .disponibilites-section {
            margin-top: 20px;
        }
        
        .disponibilite-tag {
            display: inline-block;
            background-color: #e8f5e8;
            color: #2d5a2d;
            padding: 3px 8px;
            margin: 2px 4px 2px 0;
            border-radius: 12px;
            font-size: 9px;
            border: 1px solid #a3d9a3;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>Gestion Kourel</h1>
        <p>Profil du Membre</p>
        <p>Généré le {{ $date_export }}</p>
    </div>

    <!-- Informations d'export -->
    <div class="info-export">
        <p><strong>Membre :</strong> {{ $membre->prenom }} {{ $membre->nom }}</p>
        <p><strong>Date d'export :</strong> {{ $date_export }}</p>
        <p><strong>Exporté par :</strong> {{ $exporteur }}</p>
        <p><strong>Système :</strong> Gestion Kourel v1.0</p>
    </div>

    <!-- Informations principales du membre -->
    <div class="membre-section">
        <div class="section-title">Informations Personnelles</div>
        
        <div class="membre-info">
            <div class="membre-photo">
                @if($membre->photo_url && file_exists(storage_path('app/public/' . $membre->photo_url)))
                    <img src="{{ storage_path('app/public/' . $membre->photo_url) }}" 
                         alt="Photo de {{ $membre->nom }}" 
                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                @else
                    <div class="photo-placeholder">
                        {{ strtoupper(substr($membre->prenom ?? 'M', 0, 1)) }}{{ strtoupper(substr($membre->nom ?? 'M', 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <div class="membre-details">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nom complet :</div>
                        <div class="info-value"><strong>{{ $membre->prenom }} {{ $membre->nom }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email :</div>
                        <div class="info-value">{{ $membre->email ?: 'Non renseigné' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Téléphone :</div>
                        <div class="info-value">{{ $membre->telephone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Matricule :</div>
                        <div class="info-value">{{ $membre->matricule ?: 'Non défini' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Statut :</div>
                        <div class="info-value">
                            <span class="statut-badge statut-{{ $membre->statut }}">
                                {{ ucfirst($membre->statut) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date d'adhésion :</div>
                        <div class="info-value">{{ $membre->date_adhesion->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="membre-section">
        <div class="section-title">Détails Personnels</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Date de naissance :</div>
                <div class="info-value">{{ $membre->date_naissance ? $membre->date_naissance->format('d/m/Y') : 'Non renseignée' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Profession :</div>
                <div class="info-value">{{ $membre->profession ?: 'Non renseignée' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Niveau d'étude :</div>
                <div class="info-value">{{ $membre->niveau_etude ?: 'Non renseigné' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Adresse :</div>
                <div class="info-value">{{ $membre->adresse ?: 'Non renseignée' }}</div>
            </div>
        </div>
    </div>

    <!-- Rôles -->
    @if($membre->roles->count() > 0)
    <div class="membre-section">
        <div class="section-title">Rôles Attribués</div>
        
        <div class="roles-section">
            @foreach($membre->roles as $role)
            <div class="role-item">
                <div class="role-name">{{ $role->nom }}</div>
                <div class="role-details">
                    Niveau de priorité : {{ $role->niveau_priorite }}
                    @if($role->pivot->est_principal)
                        | <strong>Rôle principal</strong>
                    @endif
                    @if($role->pivot->notes)
                        | Notes : {{ $role->pivot->notes }}
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Compétences et disponibilités -->
    @if($membre->competences || $membre->disponibilites)
    <div class="membre-section">
        <div class="section-title">Compétences et Disponibilités</div>
        
        @if($membre->competences)
        <div class="competences-section">
            <strong>Compétences :</strong><br>
            @foreach($membre->competences as $competence)
            <span class="competence-tag">{{ $competence }}</span>
            @endforeach
        </div>
        @endif
        
        @if($membre->disponibilites)
        <div class="disponibilites-section">
            <strong>Disponibilités :</strong><br>
            @foreach($membre->disponibilites as $disponibilite)
            <span class="disponibilite-tag">{{ $disponibilite }}</span>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <!-- Historique récent -->
    @if($historique->count() > 0)
    <div class="membre-section">
        <div class="section-title">Historique Récent (10 dernières actions)</div>
        
        <div class="historique-section">
            @foreach($historique as $action)
            <div class="historique-item">
                <div class="historique-date">{{ $action->created_at->format('d/m/Y à H:i') }}</div>
                <div class="historique-action">{{ ucfirst(str_replace('_', ' ', $action->action)) }}</div>
                <div class="historique-description">{{ $action->description }}</div>
                <div class="historique-description">Par {{ $action->utilisateur }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Statistiques du membre -->
    <div class="membre-section">
        <div class="section-title">Statistiques</div>
        
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre de rôles :</div>
                <div class="info-value">{{ $membre->roles->count() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Membre depuis :</div>
                <div class="info-value">{{ $membre->date_adhesion->diffForHumans() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Actions enregistrées :</div>
                <div class="info-value">{{ $historique->count() }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Photo de profil :</div>
                <div class="info-value">{{ $membre->photo_url ? 'Oui' : 'Non' }}</div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré automatiquement par le système Gestion Kourel</p>
        <p>© {{ date('Y') }} Gestion Kourel - Tous droits réservés</p>
        <p>Ce document contient des informations confidentielles</p>
    </div>
</body>
</html>
