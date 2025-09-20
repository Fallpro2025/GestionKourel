# 🎵 Gestion Kourel - Système de Gestion de Groupe Musical

## 📋 Description

**Gestion Kourel** est un système de gestion moderne et responsive pour groupes musicaux, développé avec Laravel et des technologies web modernes. Le système permet de gérer efficacement les membres, activités, événements et alertes du groupe.

## ✨ Fonctionnalités Principales

### 👥 Gestion des Membres
- **Vues Multiples** : Grille, Liste et Tableau
- **Informations Complètes** : Profil, rôle, statut, présence
- **Recherche et Filtrage** : Recherche rapide par nom, rôle, statut
- **Export** : CSV, PDF, Excel
- **Interface Moderne** : Design responsive avec animations fluides

### 🎯 Activités
- **Planning** : Gestion des répétitions et activités
- **Présence** : Suivi automatique des présences
- **Statistiques** : Taux de présence et performance

### 📅 Événements
- **Calendrier** : Planning des concerts et événements
- **Prestations** : Gestion des prestations musicales
- **Participants** : Sélection et gestion des membres

### 🔔 Alertes
- **Notifications** : Alertes importantes pour le groupe
- **Priorités** : Système de priorités des alertes

## 🛠️ Technologies Utilisées

- **Backend** : Laravel 11
- **Frontend** : Vue.js 3, Tailwind CSS
- **Base de Données** : MySQL/SQLite
- **Icônes** : Font Awesome
- **Design** : Interface moderne et responsive

## 🚀 Installation

### Prérequis
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL ou SQLite

### Installation Rapide
```bash
# Cloner le repository
git clone https://github.com/votre-username/gestion-kourel.git
cd gestion-kourel

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Démarrer le serveur
php artisan serve
```

## 📱 Interface Utilisateur

### Vues Modernes
- **Vue Grille** : Cartes visuelles avec informations essentielles
- **Vue Liste** : Format horizontal compact
- **Vue Tableau** : Format tabulaire avec colonnes

### Fonctionnalités Avancées
- **Changement de Vue Dynamique** : Bouton qui cycle entre les vues
- **Persistance** : Sauvegarde de la vue préférée
- **Responsive** : Adaptation automatique aux écrans
- **Animations** : Transitions fluides et modernes

## 🎨 Design System

### Couleurs
- **Primary** : Bleu moderne (#3b82f6)
- **Secondary** : Vert (#10b981)
- **Accent** : Rose, Orange, Violet, Ambre
- **Background** : Dégradé sombre élégant

### Composants
- **Cartes** : Design glassmorphism avec effets de survol
- **Boutons** : Styles modernes avec transitions
- **Navigation** : Sidebar responsive avec animations
- **Modales** : Overlays élégants pour les formulaires

## 📊 Base de Données

### Modèles Principaux
- **Membre** : Informations personnelles et rôle
- **Activité** : Répétitions et activités du groupe
- **Événement** : Concerts et événements
- **Présence** : Suivi des présences
- **Alerte** : Notifications importantes

## 🔧 Configuration

### Variables d'Environnement
```env
APP_NAME="Gestion Kourel"
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_kourel
DB_USERNAME=root
DB_PASSWORD=
```

## 📝 Utilisation

### Gestion des Membres
1. **Ajouter un Membre** : Bouton "Ajouter Membre" dans la navbar
2. **Changer de Vue** : Bouton "Vue" qui cycle entre Grille/Liste/Tableau
3. **Exporter** : Bouton "Exporter" pour CSV/PDF/Excel
4. **Rechercher** : Barre de recherche en temps réel

### Navigation
- **Dashboard** : Vue d'ensemble du groupe
- **Membres** : Gestion complète des membres
- **Activités** : Planning et répétitions
- **Événements** : Concerts et événements
- **Alertes** : Notifications importantes

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👨‍💻 Développeur

Développé avec ❤️ pour la gestion moderne des groupes musicaux.

---

**Gestion Kourel** - Révolutionnez la gestion de votre groupe musical ! 🎵✨# GestionKourel
