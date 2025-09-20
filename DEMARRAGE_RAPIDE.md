# 🚀 DÉMARRAGE RAPIDE - GESTION KOUREL

## Installation Express (5 minutes)

### 1. Prérequis
- ✅ PHP 8.2+ installé
- ✅ MySQL 8.0+ installé
- ✅ Node.js 18+ installé (optionnel pour le développement)

### 2. Configuration Base de Données

#### Option A: Script SQL automatique
```bash
# Créer la base de données et les tables
mysql -u root -p < config_database_simple.sql
```

#### Option B: Configuration manuelle
1. Créer la base de données dans MySQL :
```sql
CREATE DATABASE gestion_kourel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Modifier le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_kourel
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 3. Démarrage de l'Application

```bash
# Démarrer le serveur Laravel
php artisan serve
```

L'application sera accessible sur : **http://localhost:8000**

## 🎯 Fonctionnalités Disponibles

### 👥 Gestion des Membres
- **URL** : `/membres`
- **Fonctionnalités** : Ajout, modification, suppression, recherche
- **Données de test** : 5 membres pré-configurés

### 💰 Gestion des Cotisations
- **URL** : `/cotisations`
- **Fonctionnalités** : Projets, assignations, suivi des paiements
- **Données de test** : 1 projet avec 5 assignations

### 📅 Gestion des Activités
- **URL** : `/activites`
- **Fonctionnalités** : Répétitions, prestations, suivi des présences
- **Données de test** : 1 activité de répétition

### 🎤 Gestion des Événements
- **URL** : `/evenements`
- **Fonctionnalités** : Magal, Gamou, Promokhane, sélection des participants

### 🔔 Système d'Alertes
- **URL** : `/alertes`
- **Fonctionnalités** : Alertes automatiques, notifications

### 📊 Dashboard
- **URL** : `/`
- **Fonctionnalités** : KPIs, statistiques, graphiques

## 🔧 Configuration Avancée

### Variables d'Environnement Importantes
```env
# Application
APP_NAME="Gestion Kourel"
APP_URL=http://localhost:8000
APP_KEY=base64:... (généré automatiquement)

# Base de données
DB_CONNECTION=mysql
DB_DATABASE=gestion_kourel

# Sanctum (API)
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1

# SMS (Twilio) - Optionnel
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_number

# WhatsApp Business API - Optionnel
WHATSAPP_TOKEN=your_whatsapp_token
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
```

## 📱 API Endpoints

### Authentification
- `POST /api/auth/login` - Connexion
- `POST /api/auth/logout` - Déconnexion
- `GET /api/auth/me` - Informations utilisateur

### Membres
- `GET /api/membres` - Liste des membres
- `POST /api/membres` - Créer un membre
- `GET /api/membres/{id}` - Détails d'un membre
- `PUT /api/membres/{id}` - Modifier un membre
- `DELETE /api/membres/{id}` - Supprimer un membre

### Dashboard
- `GET /api/dashboard` - Données du dashboard
- `GET /api/dashboard/statistiques` - Statistiques détaillées

## 🎨 Interface Utilisateur

### Design Moderne
- **Thème** : Sombre par défaut avec glassmorphism
- **Responsive** : Mobile-first, adaptatif
- **Animations** : Transitions fluides
- **Couleurs** : Bleu professionnel, vert confiance

### Navigation
- **Sidebar** : Navigation principale avec icônes
- **Header** : Recherche globale, notifications, profil
- **Breadcrumbs** : Navigation contextuelle
- **Modals** : Actions rapides et confirmations

## 🔒 Sécurité

### Authentification
- **Laravel Sanctum** : Tokens sécurisés
- **2FA** : Double authentification (optionnel)
- **Rate Limiting** : Protection contre les attaques

### Permissions
- **RBAC** : Rôles et permissions granulaires
- **Audit Trail** : Traçabilité des actions
- **Validation** : Validation stricte des données

## 📊 Données de Test

### Membres Pré-configurés
1. **DIAGNE Amadou** - Administrateur
2. **FALL Fatou** - Responsable
3. **NDIAYE Moussa** - Membre
4. **BA Aïcha** - Trésorier
5. **SARR Ibrahima** - Secrétaire

### Projet de Cotisation
- **Nom** : Cotisation Annuelle 2025
- **Montant** : 50,000 XOF
- **Échéance** : 31/12/2025
- **Statut** : Actif

### Activité de Test
- **Nom** : Répétition Hebdomadaire
- **Type** : Répétition
- **Date** : 20/01/2025 19:00-21:00
- **Lieu** : Mosquée Centrale

## 🚨 Dépannage

### Erreur "Class not found"
```bash
# Régénérer l'autoload
composer dump-autoload
```

### Erreur de base de données
```bash
# Vérifier la connexion
php artisan tinker
# Puis : DB::connection()->getPdo();
```

### Erreur de permissions
```bash
# Windows
icacls storage /grant Everyone:F /T

# Linux/Mac
chmod -R 755 storage bootstrap/cache
```

### Port déjà utilisé
```bash
# Utiliser un autre port
php artisan serve --port=8080
```

## 📞 Support

### Documentation
- **README.md** : Documentation complète
- **ARCHITECTURE_MODERNE.md** : Architecture technique
- **DESIGN_SYSTEM_MODERNE.md** : Guide de design

### Fichiers de Configuration
- **composer.json** : Dépendances PHP
- **package.json** : Dépendances Node.js
- **tailwind.config.js** : Configuration Tailwind
- **vite.config.js** : Configuration Vite

### Scripts Utiles
- **test_app.php** : Test d'installation
- **install.bat** : Installation Windows
- **install.sh** : Installation Linux/Mac

---

## 🎉 Félicitations !

Votre plateforme **Gestion Kourel** est maintenant opérationnelle !

**Accès** : http://localhost:8000
**Documentation** : README.md
**Support** : sauvegarde_discussion_gestion_kourel.txt

Développé avec ❤️ pour la communauté musulmane 🕌
