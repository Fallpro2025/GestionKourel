# 🗄️ SCHÉMA MYSQL OPTIMISÉ - PLATEFORME GESTION DAHIRA/KOUREL

## 🎯 Configuration MySQL Moderne

### Version Recommandée
```sql
-- MySQL 8.0+ pour les performances optimales
-- Configuration recommandée pour la production
SET GLOBAL innodb_buffer_pool_size = 1G;
SET GLOBAL max_connections = 200;
SET GLOBAL query_cache_size = 64M;
```

## 🏗️ Structure des Tables Optimisées

### 1. 📋 Table des Membres (membres)
```sql
CREATE TABLE membres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(20) UNIQUE,
    date_naissance DATE,
    date_adhesion DATE NOT NULL DEFAULT (CURRENT_DATE),
    statut ENUM('actif', 'inactif', 'suspendu', 'ancien') DEFAULT 'actif',
    role_id BIGINT UNSIGNED,
    photo_url VARCHAR(500),
    preferences_notifications JSON,
    adresse TEXT,
    profession VARCHAR(100),
    niveau_etude VARCHAR(50),
    competences JSON, -- ['déclamation', 'chorale', 'animation']
    disponibilites JSON, -- Jours et heures disponibles
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nom_prenom (nom, prenom),
    INDEX idx_email (email),
    INDEX idx_statut (statut),
    INDEX idx_date_adhesion (date_adhesion),
    FULLTEXT idx_recherche (nom, prenom, email, profession)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. 🔐 Table des Rôles et Permissions
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    permissions JSON, -- ['gestion_membres', 'gestion_cotisations', 'gestion_evenements']
    niveau_priorite TINYINT UNSIGNED DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nom (nom),
    INDEX idx_niveau (niveau_priorite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    module VARCHAR(50) NOT NULL, -- 'membres', 'cotisations', 'evenements'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_nom (nom),
    INDEX idx_module (module)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. 💰 Tables des Cotisations
```sql
CREATE TABLE projets_cotisation (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    montant_total DECIMAL(15,2) NOT NULL,
    montant_collecte DECIMAL(15,2) DEFAULT 0.00,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('planifie', 'actif', 'suspendu', 'termine', 'annule') DEFAULT 'planifie',
    type_cotisation ENUM('obligatoire', 'volontaire', 'evenement') DEFAULT 'obligatoire',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_statut (statut),
    INDEX idx_dates (date_debut, date_fin),
    INDEX idx_type (type_cotisation),
    INDEX idx_created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE assignations_cotisation (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id BIGINT UNSIGNED NOT NULL,
    projet_id BIGINT UNSIGNED NOT NULL,
    montant_assigné DECIMAL(10,2) NOT NULL,
    montant_payé DECIMAL(10,2) DEFAULT 0.00,
    statut_paiement ENUM('non_paye', 'partiel', 'paye', 'rembourse') DEFAULT 'non_paye',
    date_echeance DATE,
    date_dernier_paiement TIMESTAMP NULL,
    historique_paiements JSON, -- [{'date': '2025-01-15', 'montant': 5000, 'methode': 'espèces'}]
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_membre_projet (membre_id, projet_id),
    INDEX idx_statut_paiement (statut_paiement),
    INDEX idx_date_echeance (date_echeance),
    INDEX idx_membre_id (membre_id),
    INDEX idx_projet_id (projet_id),
    
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE CASCADE,
    FOREIGN KEY (projet_id) REFERENCES projets_cotisation(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4. 📅 Tables des Activités et Présences
```sql
CREATE TABLE activites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('repetition', 'prestation', 'goudi_aldiouma', 'formation', 'reunion') NOT NULL,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    lieu VARCHAR(200),
    responsable_id BIGINT UNSIGNED,
    statut ENUM('planifie', 'confirme', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
    configuration JSON, -- {'repetition_hebdomadaire': true, 'jours': ['lundi', 'mercredi']}
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_dates (date_debut, date_fin),
    INDEX idx_statut (statut),
    INDEX idx_responsable (responsable_id),
    
    FOREIGN KEY (responsable_id) REFERENCES membres(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE presences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id BIGINT UNSIGNED NOT NULL,
    activite_id BIGINT UNSIGNED NOT NULL,
    statut ENUM('present', 'absent_justifie', 'absent_non_justifie', 'retard') NOT NULL,
    heure_arrivee TIMESTAMP NULL,
    minutes_retard TINYINT UNSIGNED DEFAULT 0,
    justification TEXT,
    latitude DECIMAL(10, 8) NULL, -- Géolocalisation
    longitude DECIMAL(11, 8) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE KEY unique_membre_activite (membre_id, activite_id),
    INDEX idx_statut (statut),
    INDEX idx_membre_id (membre_id),
    INDEX idx_activite_id (activite_id),
    INDEX idx_heure_arrivee (heure_arrivee),
    
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. 🎤 Table des Événements Spéciaux
```sql
CREATE TABLE evenements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    type ENUM('magal', 'gamou', 'promokhane', 'conference', 'formation', 'autre') NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    lieu VARCHAR(200),
    description TEXT,
    budget DECIMAL(15,2),
    statut ENUM('planifie', 'confirme', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
    membres_selectionnes JSON, -- {'declamation': [1,2,3], 'chorale': [4,5,6], 'animation': [7,8,9]}
    configuration JSON, -- {'dress_code': 'tenue traditionnelle', 'transport': 'organise'}
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_dates (date_debut, date_fin),
    INDEX idx_statut (statut),
    INDEX idx_created_by (created_by),
    
    FOREIGN KEY (created_by) REFERENCES membres(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 6. 🔔 Système d'Alertes Intelligent
```sql
CREATE TABLE alertes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('absence_repetitive', 'absence_non_justifiee', 'retard_excessif', 'cotisation_retard', 'evenement_majeur') NOT NULL,
    membre_id BIGINT UNSIGNED,
    activite_id BIGINT UNSIGNED NULL,
    evenement_id BIGINT UNSIGNED NULL,
    message TEXT NOT NULL,
    niveau_urgence ENUM('info', 'warning', 'error', 'critical') DEFAULT 'warning',
    statut ENUM('nouveau', 'envoye', 'lu', 'resolu') DEFAULT 'nouveau',
    canal_notification JSON, -- ['email', 'sms', 'whatsapp', 'push']
    sent_at TIMESTAMP NULL,
    resolved_at TIMESTAMP NULL,
    resolved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_niveau (niveau_urgence),
    INDEX idx_statut (statut),
    INDEX idx_membre_id (membre_id),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites(id) ON DELETE CASCADE,
    FOREIGN KEY (evenement_id) REFERENCES evenements(id) ON DELETE CASCADE,
    FOREIGN KEY (resolved_by) REFERENCES membres(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 7. 📊 Table d'Audit et Logs
```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id BIGINT UNSIGNED,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_table_record (table_name, record_id),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (user_id) REFERENCES membres(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 🚀 Optimisations MySQL Spécifiques

### Index Composés pour Performance
```sql
-- Index pour les requêtes fréquentes
CREATE INDEX idx_presences_membre_date ON presences(membre_id, created_at);
CREATE INDEX idx_cotisations_echeance_statut ON assignations_cotisation(date_echeance, statut_paiement);
CREATE INDEX idx_activites_type_date ON activites(type, date_debut);
CREATE INDEX idx_alertes_membre_statut ON alertes(membre_id, statut);
```

### Vues pour Simplifier les Requêtes
```sql
-- Vue des statistiques des membres
CREATE VIEW vue_statistiques_membres AS
SELECT 
    m.id,
    m.nom,
    m.prenom,
    COUNT(DISTINCT p.activite_id) as total_activites,
    COUNT(DISTINCT CASE WHEN p.statut = 'present' THEN p.activite_id END) as activites_presentes,
    COUNT(DISTINCT CASE WHEN p.statut = 'absent_non_justifie' THEN p.activite_id END) as absences_non_justifiees,
    ROUND((COUNT(DISTINCT CASE WHEN p.statut = 'present' THEN p.activite_id END) / 
           COUNT(DISTINCT p.activite_id)) * 100, 2) as taux_presence
FROM membres m
LEFT JOIN presences p ON m.id = p.membre_id
GROUP BY m.id, m.nom, m.prenom;

-- Vue des cotisations en retard
CREATE VIEW vue_cotisations_retard AS
SELECT 
    m.nom,
    m.prenom,
    m.email,
    m.telephone,
    pc.nom as projet_nom,
    ac.montant_assigné,
    ac.montant_payé,
    (ac.montant_assigné - ac.montant_payé) as montant_restant,
    ac.date_echeance,
    DATEDIFF(CURRENT_DATE, ac.date_echeance) as jours_retard
FROM assignations_cotisation ac
JOIN membres m ON ac.membre_id = m.id
JOIN projets_cotisation pc ON ac.projet_id = pc.id
WHERE ac.statut_paiement IN ('non_paye', 'partiel')
AND ac.date_echeance < CURRENT_DATE;
```

## 📈 Requêtes Optimisées pour les KPIs

### Dashboard Statistiques
```sql
-- Taux de présence global
SELECT 
    COUNT(*) as total_presences,
    COUNT(CASE WHEN statut = 'present' THEN 1 END) as presences,
    ROUND((COUNT(CASE WHEN statut = 'present' THEN 1 END) / COUNT(*)) * 100, 2) as taux_presence
FROM presences 
WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY);

-- Recouvrement des cotisations
SELECT 
    SUM(montant_assigné) as total_assigné,
    SUM(montant_payé) as total_payé,
    ROUND((SUM(montant_payé) / SUM(montant_assigné)) * 100, 2) as taux_recouvrement
FROM assignations_cotisation ac
JOIN projets_cotisation pc ON ac.projet_id = pc.id
WHERE pc.statut = 'actif';
```

---

*Schéma MySQL optimisé par un expert avec 50 ans d'expérience* 🚀

