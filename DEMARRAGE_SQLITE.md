# 🚀 DÉMARRAGE RAPIDE AVEC SQLITE - GESTION KOUREL

## Solution Simple (Sans MySQL)

Si vous avez des problèmes avec MySQL ou l'extension pdo_mysql, voici comment démarrer rapidement avec SQLite :

### 1. Configuration SQLite

#### Option A: Copier la configuration
```bash
# Copier la configuration SQLite
copy env.example .env
```

#### Option B: Modifier manuellement le .env
```env
# Changer ces lignes dans .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 2. Créer la base de données SQLite

```bash
# Créer le fichier de base de données
mkdir database
echo. > database/database.sqlite
```

### 3. Générer la clé d'application

```bash
# Générer une clé aléatoire
php artisan key:generate
```

### 4. Créer les tables

```bash
# Exécuter les migrations
php artisan migrate
```

### 5. Démarrer le serveur

```bash
# Démarrer Laravel
php artisan serve --port=8000
```

## 🎯 Accès à l'Application

- **URL** : http://localhost:8000
- **Interface** : Ultra-moderne avec glassmorphism
- **Base de données** : SQLite (fichier local)

## 📊 Données de Test

### Membres par Défaut
1. **DIAGNE Amadou** - Administrateur
2. **FALL Fatou** - Responsable  
3. **NDIAYE Moussa** - Membre
4. **BA Aïcha** - Trésorier
5. **SARR Ibrahima** - Secrétaire

### Projet de Cotisation
- **Nom** : Cotisation Annuelle 2025
- **Montant** : 50,000 XOF
- **Échéance** : 31/12/2025

## 🔧 Avantages SQLite

- ✅ **Aucune installation** de serveur de base de données
- ✅ **Fichier unique** facile à sauvegarder
- ✅ **Performance** excellente pour les petits projets
- ✅ **Portable** - fonctionne partout
- ✅ **Sécurisé** - pas de connexion réseau

## 📱 Fonctionnalités Disponibles

### Dashboard
- KPIs en temps réel
- Statistiques des membres
- Graphiques interactifs
- Alertes critiques

### Gestion des Membres
- Ajout/modification/suppression
- Recherche et filtrage
- Profils complets avec photos
- Historique des activités

### Gestion des Cotisations
- Projets de cotisation
- Assignations aux membres
- Suivi des paiements
- Alertes de retard

### Gestion des Activités
- Planification des répétitions
- Enregistrement des présences
- Suivi des retards
- Statistiques de présence

### Gestion des Événements
- Événements spéciaux (Magal, Gamou)
- Sélection des participants
- Gestion du budget
- Configuration flexible

### Système d'Alertes
- Notifications automatiques
- Règles configurables
- Escalade des alertes
- Dashboard centralisé

## 🚨 Dépannage

### Erreur "Class not found"
```bash
composer dump-autoload
```

### Erreur de permissions
```bash
# Windows
icacls database /grant Everyone:F /T

# Linux/Mac  
chmod -R 755 database
```

### Erreur de mémoire PHP
```bash
# Augmenter la mémoire dans php.ini
memory_limit = 512M
```

### Port déjà utilisé
```bash
php artisan serve --port=8080
```

## 📈 Migration vers MySQL (Plus tard)

Quand vous serez prêt pour MySQL :

1. **Installer MySQL** et l'extension pdo_mysql
2. **Modifier .env** :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gestion_kourel
   DB_USERNAME=root
   DB_PASSWORD=votre_mot_de_passe
   ```
3. **Exécuter** : `php artisan migrate`

## 🎉 C'est Parti !

Votre plateforme **Gestion Kourel** est maintenant prête avec SQLite !

**Accès** : http://localhost:8000
**Base de données** : Fichier local SQLite
**Fonctionnalités** : 100% opérationnelles

Développé avec ❤️ pour la communauté musulmane 🕌
