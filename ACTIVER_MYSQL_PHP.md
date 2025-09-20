# 🔧 ACTIVATION MYSQL DANS PHP - GESTION KOUREL

## Problème Identifié
L'extension `pdo_mysql` n'est pas activée dans votre installation PHP.

## Solutions pour Activer MySQL dans PHP

### Solution 1: Modifier php.ini (Recommandé)

#### Étape 1: Localiser php.ini
```bash
# Trouver le fichier php.ini
php --ini
```

#### Étape 2: Modifier php.ini
Ouvrir le fichier `php.ini` et décommenter/modifier ces lignes :

```ini
; Décommenter cette ligne
extension=pdo_mysql

; Ou ajouter si elle n'existe pas
extension=pdo_mysql
extension=mysqli
```

#### Étape 3: Redémarrer le serveur web
```bash
# Redémarrer Apache/Nginx si utilisé
# Ou simplement redémarrer le terminal
```

### Solution 2: Installation XAMPP/WAMP (Simple)

#### Télécharger XAMPP
1. Aller sur https://www.apachefriends.org/
2. Télécharger XAMPP pour Windows
3. Installer avec toutes les options
4. Démarrer Apache et MySQL depuis le panneau de contrôle

#### Configuration XAMPP
- **Apache** : Port 80 (ou 8080 si conflit)
- **MySQL** : Port 3306
- **PHP** : Inclus avec toutes les extensions

### Solution 3: Installation PHP Standalone avec MySQL

#### Télécharger PHP
1. Aller sur https://windows.php.net/download/
2. Télécharger PHP Thread Safe
3. Extraire dans `C:\php`

#### Télécharger MySQL
1. Aller sur https://dev.mysql.com/downloads/mysql/
2. Télécharger MySQL Community Server
3. Installer avec configuration par défaut

#### Configurer PHP
1. Copier `php.ini-development` vers `php.ini`
2. Modifier `php.ini` :
   ```ini
   extension_dir = "ext"
   extension=pdo_mysql
   extension=mysqli
   extension=mbstring
   extension=openssl
   extension=tokenizer
   extension=xml
   extension=ctype
   extension=json
   extension=bcmath
   ```

### Solution 4: Utiliser Docker (Avancé)

#### Créer docker-compose.yml
```yaml
version: '3.8'
services:
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: gestion_kourel
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  php:
    image: php:8.2-apache
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - mysql

volumes:
  mysql_data:
```

#### Démarrer avec Docker
```bash
docker-compose up -d
```

## Vérification de l'Installation

### Test 1: Vérifier les extensions PHP
```bash
php -m | findstr mysql
```

### Test 2: Vérifier la configuration
```bash
php -i | findstr mysql
```

### Test 3: Tester la connexion MySQL
```bash
php test_mysql.php
```

## Configuration Alternative: SQLite

Si vous ne pouvez pas activer MySQL immédiatement, vous pouvez utiliser SQLite :

### Étape 1: Modifier .env
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Étape 2: Créer la base SQLite
```bash
mkdir database
echo. > database/database.sqlite
```

### Étape 3: Exécuter les migrations
```bash
php artisan migrate
```

## Script de Configuration Automatique

### Windows avec XAMPP
```batch
@echo off
echo Configuration XAMPP pour Gestion Kourel...
echo.
echo 1. Demarrer XAMPP Control Panel
echo 2. Demarrer Apache et MySQL
echo 3. Ouvrir http://localhost/phpmyadmin
echo 4. Creer la base de donnees 'gestion_kourel'
echo 5. Executer: php artisan migrate
echo.
pause
```

### Linux/Mac
```bash
#!/bin/bash
echo "Configuration MySQL pour Gestion Kourel..."
echo "1. Installer MySQL: sudo apt install mysql-server"
echo "2. Installer PHP MySQL: sudo apt install php-mysql"
echo "3. Demarrer MySQL: sudo systemctl start mysql"
echo "4. Creer la base: mysql -u root -p -e 'CREATE DATABASE gestion_kourel;'"
echo "5. Executer: php artisan migrate"
```

## Dépannage Courant

### Erreur "Class 'PDO' not found"
```bash
# Solution: Activer extension=pdo dans php.ini
extension=pdo
extension=pdo_mysql
```

### Erreur "mysql_connect() not found"
```bash
# Solution: Activer extension=mysqli dans php.ini
extension=mysqli
```

### Erreur "Access denied for user 'root'"
```bash
# Solution: Réinitialiser le mot de passe MySQL
mysql -u root -p
ALTER USER 'root'@'localhost' IDENTIFIED BY 'nouveau_mot_de_passe';
```

### Erreur "Can't connect to MySQL server"
```bash
# Solution: Vérifier que MySQL est démarré
# Windows: Services -> MySQL
# Linux: sudo systemctl status mysql
```

## Recommandations

### Pour le Développement
- **XAMPP** : Solution la plus simple
- **Docker** : Solution la plus portable
- **PHP Standalone** : Solution la plus flexible

### Pour la Production
- **MySQL Server** : Performance optimale
- **Configuration sécurisée** : Utilisateurs dédiés
- **Backup automatique** : mysqldump quotidien

## Prochaines Étapes

Une fois MySQL activé :

1. **Tester** : `php test_mysql.php`
2. **Configurer** : Modifier `.env` avec MySQL
3. **Migrer** : `php artisan migrate`
4. **Démarrer** : `php artisan serve`
5. **Accéder** : http://localhost:8000

---

**Note** : Si vous préférez continuer avec SQLite pour l'instant, consultez `DEMARRAGE_SQLITE.md` pour une configuration rapide.
