-- Configuration MySQL optimisée pour Gestion Kourel
-- Script complet de création et configuration

-- Créer la base de données avec optimisations
CREATE DATABASE IF NOT EXISTS gestion_kourel 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Utiliser la base de données
USE gestion_kourel;

-- Configuration des variables MySQL pour optimiser les performances
SET GLOBAL innodb_buffer_pool_size = 268435456; -- 256MB
SET GLOBAL max_connections = 200;
SET GLOBAL query_cache_size = 67108864; -- 64MB
SET GLOBAL tmp_table_size = 67108864; -- 64MB
SET GLOBAL max_heap_table_size = 67108864; -- 64MB

-- Table des rôles
CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_roles_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des membres avec optimisations
CREATE TABLE IF NOT EXISTS membres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    contact VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE,
    date_naissance DATE,
    adresse TEXT,
    photo_profil VARCHAR(255),
    role_id BIGINT UNSIGNED,
    statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    competences JSON,
    disponibilites JSON,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL,
    INDEX idx_membres_nom_prenom (nom, prenom),
    INDEX idx_membres_contact (contact),
    INDEX idx_membres_email (email),
    INDEX idx_membres_statut (statut),
    INDEX idx_membres_role (role_id),
    FULLTEXT idx_membres_recherche (nom, prenom, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des projets de cotisation
CREATE TABLE IF NOT EXISTS projets_cotisation (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    montant_cible DECIMAL(15, 2) NOT NULL,
    date_echeance DATE,
    statut ENUM('actif', 'termine', 'annule') DEFAULT 'actif',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_projets_statut (statut),
    INDEX idx_projets_echeance (date_echeance),
    INDEX idx_projets_montant (montant_cible),
    FULLTEXT idx_projets_recherche (nom, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des assignations de cotisation
CREATE TABLE IF NOT EXISTS assignations_cotisation (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id BIGINT UNSIGNED NOT NULL,
    projet_cotisation_id BIGINT UNSIGNED NOT NULL,
    montant_assigne DECIMAL(15, 2) NOT NULL,
    montant_paye DECIMAL(15, 2) DEFAULT 0.00,
    date_paiement TIMESTAMP NULL DEFAULT NULL,
    statut ENUM('en_attente', 'paye', 'partiel', 'en_retard') DEFAULT 'en_attente',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE CASCADE,
    FOREIGN KEY (projet_cotisation_id) REFERENCES projets_cotisation(id) ON DELETE CASCADE,
    INDEX idx_assignations_membre (membre_id),
    INDEX idx_assignations_projet (projet_cotisation_id),
    INDEX idx_assignations_statut (statut),
    INDEX idx_assignations_date_paiement (date_paiement),
    INDEX idx_assignations_montant (montant_assigne, montant_paye)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des activités
CREATE TABLE IF NOT EXISTS activites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    type ENUM('repetition', 'prestation', 'goudi_aldiouma', 'autre') NOT NULL,
    date_heure_debut DATETIME NOT NULL,
    date_heure_fin DATETIME NOT NULL,
    lieu VARCHAR(200),
    statut ENUM('planifie', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_activites_type (type),
    INDEX idx_activites_date (date_heure_debut),
    INDEX idx_activites_statut (statut),
    INDEX idx_activites_lieu (lieu),
    FULLTEXT idx_activites_recherche (nom, description, lieu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des présences
CREATE TABLE IF NOT EXISTS presences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id BIGINT UNSIGNED NOT NULL,
    activite_id BIGINT UNSIGNED NOT NULL,
    statut ENUM('present', 'absent_justifie', 'absent_non_justifie', 'retard') NOT NULL,
    heure_arrivee TIMESTAMP NULL DEFAULT NULL,
    minutes_retard INT UNSIGNED DEFAULT 0,
    justification TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites(id) ON DELETE CASCADE,
    INDEX idx_presences_membre (membre_id),
    INDEX idx_presences_activite (activite_id),
    INDEX idx_presences_statut (statut),
    INDEX idx_presences_date (created_at),
    INDEX idx_presences_heure_arrivee (heure_arrivee),
    INDEX idx_presences_retard (minutes_retard)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des événements
CREATE TABLE IF NOT EXISTS evenements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    date_heure_debut DATETIME NOT NULL,
    date_heure_fin DATETIME NOT NULL,
    lieu VARCHAR(200),
    budget DECIMAL(15, 2),
    statut ENUM('planifie', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
    membres_selectionnes JSON,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_evenements_date (date_heure_debut),
    INDEX idx_evenements_statut (statut),
    INDEX idx_evenements_lieu (lieu),
    INDEX idx_evenements_budget (budget),
    FULLTEXT idx_evenements_recherche (nom, description, lieu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des alertes
CREATE TABLE IF NOT EXISTS alertes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    membre_id BIGINT UNSIGNED,
    type_alerte ENUM('absence_repetitive', 'absence_non_justifiee', 'retard_excessif', 'cotisation_retard', 'evenement_majeur') NOT NULL,
    message TEXT NOT NULL,
    date_declenchement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('active', 'resolue', 'ignoree') DEFAULT 'active',
    canal_notification JSON,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (membre_id) REFERENCES membres(id) ON DELETE SET NULL,
    INDEX idx_alertes_membre (membre_id),
    INDEX idx_alertes_type (type_alerte),
    INDEX idx_alertes_statut (statut),
    INDEX idx_alertes_date (date_declenchement),
    INDEX idx_alertes_urgence (type_alerte, statut, date_declenchement)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des migrations Laravel
CREATE TABLE IF NOT EXISTS migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL,
    INDEX idx_migrations_batch (batch)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des sessions Laravel
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_sessions_user_id (user_id),
    INDEX idx_sessions_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table du cache Laravel
CREATE TABLE IF NOT EXISTS cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL,
    INDEX idx_cache_expiration (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des jobs Laravel
CREATE TABLE IF NOT EXISTS jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX idx_jobs_queue (queue),
    INDEX idx_jobs_reserved_at (reserved_at),
    INDEX idx_jobs_available_at (available_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des rôles par défaut
INSERT INTO roles (nom, description) VALUES
('Administrateur', 'Accès complet à toutes les fonctionnalités'),
('Responsable', 'Gestion des membres et activités'),
('Membre', 'Accès aux informations personnelles'),
('Trésorier', 'Gestion des cotisations et finances'),
('Secrétaire', 'Gestion des événements et communications');

-- Insertion de données de test
INSERT INTO membres (nom, prenom, contact, email, role_id, statut) VALUES
('DIAGNE', 'Amadou', '+221701234567', 'amadou.diagne@example.com', 1, 'actif'),
('FALL', 'Fatou', '+221701234568', 'fatou.fall@example.com', 2, 'actif'),
('NDIAYE', 'Moussa', '+221701234569', 'moussa.ndiaye@example.com', 3, 'actif'),
('BA', 'Aïcha', '+221701234570', 'aicha.ba@example.com', 4, 'actif'),
('SARR', 'Ibrahima', '+221701234571', 'ibrahima.sarr@example.com', 5, 'actif');

-- Insertion d'un projet de cotisation de test
INSERT INTO projets_cotisation (nom, description, montant_cible, date_echeance, statut) VALUES
('Cotisation Annuelle 2025', 'Cotisation pour les activités de l\'année 2025', 50000.00, '2025-12-31', 'actif');

-- Insertion d'assignations de test
INSERT INTO assignations_cotisation (membre_id, projet_cotisation_id, montant_assigne, montant_paye, statut) VALUES
(1, 1, 10000.00, 10000.00, 'paye'),
(2, 1, 10000.00, 5000.00, 'partiel'),
(3, 1, 10000.00, 0.00, 'en_attente'),
(4, 1, 10000.00, 10000.00, 'paye'),
(5, 1, 10000.00, 0.00, 'en_retard');

-- Insertion d'une activité de test
INSERT INTO activites (nom, description, type, date_heure_debut, date_heure_fin, lieu, statut) VALUES
('Répétition Hebdomadaire', 'Répétition générale du groupe', 'repetition', '2025-01-20 19:00:00', '2025-01-20 21:00:00', 'Mosquée Centrale', 'planifie');

-- Insertion d'un événement de test
INSERT INTO evenements (nom, description, date_heure_debut, date_heure_fin, lieu, budget, statut) VALUES
('Magal 2025', 'Célébration annuelle du Magal', '2025-02-15 08:00:00', '2025-02-15 18:00:00', 'Grande Mosquée', 100000.00, 'planifie');

-- Insertion d'alertes de test
INSERT INTO alertes (membre_id, type_alerte, message, statut) VALUES
(3, 'cotisation_retard', 'Cotisation en retard depuis 15 jours', 'active'),
(5, 'cotisation_retard', 'Cotisation en retard depuis 30 jours', 'active');

-- Création d'une vue pour les statistiques des membres
CREATE VIEW vue_statistiques_membres AS
SELECT 
    m.id,
    m.nom,
    m.prenom,
    m.statut,
    r.nom as role_nom,
    COUNT(DISTINCT ac.id) as nombre_cotisations,
    SUM(ac.montant_assigne) as total_assigne,
    SUM(ac.montant_paye) as total_paye,
    COUNT(DISTINCT p.id) as nombre_presences,
    COUNT(DISTINCT CASE WHEN p.statut = 'present' THEN p.id END) as presences_effectives
FROM membres m
LEFT JOIN roles r ON m.role_id = r.id
LEFT JOIN assignations_cotisation ac ON m.id = ac.membre_id
LEFT JOIN presences p ON m.id = p.membre_id
GROUP BY m.id, m.nom, m.prenom, m.statut, r.nom;

-- Création d'une vue pour les statistiques des cotisations
CREATE VIEW vue_statistiques_cotisations AS
SELECT 
    pc.id,
    pc.nom,
    pc.montant_cible,
    COUNT(ac.id) as nombre_assignations,
    SUM(ac.montant_assigne) as montant_total_assigne,
    SUM(ac.montant_paye) as montant_total_paye,
    COUNT(CASE WHEN ac.statut = 'paye' THEN 1 END) as nombre_payes,
    COUNT(CASE WHEN ac.statut = 'partiel' THEN 1 END) as nombre_partiels,
    COUNT(CASE WHEN ac.statut = 'en_retard' THEN 1 END) as nombre_retards
FROM projets_cotisation pc
LEFT JOIN assignations_cotisation ac ON pc.id = ac.projet_cotisation_id
GROUP BY pc.id, pc.nom, pc.montant_cible;

-- Message de confirmation
SELECT 'Base de données Gestion Kourel créée avec succès!' as message,
       'Tables créées: 12' as tables,
       'Index créés: 35+' as indexes,
       'Vues créées: 2' as vues,
       'Données de test insérées' as donnees_test;
