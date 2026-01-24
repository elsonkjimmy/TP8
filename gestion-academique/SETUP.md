# GUIDE RAPIDE D'INSTALLATION ET DE LANCEMENT

## ⚡ Installation Rapide (5 minutes)

### 1. Prérequis
```bash
# Vérifier que vous avez :
php -v          # PHP 8.2+
composer -v     # Composer 2.x
mysql --version # MySQL 8.0+
node -v         # Node.js 18+
```

### 2. Cloner et Configurer
```bash
# Cloner le projet
git clone <url_du_repo>
cd gestion-academique

# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Copier la configuration
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 3. Configurer la Base de Données

Éditer le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_academique
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

Créer la base de données :
```bash
mysql -u root -p
> CREATE DATABASE gestion_academique;
> exit;
```

### 4. Initialiser la Base de Données
```bash
# Exécuter les migrations
php artisan migrate

# Créer le premier utilisateur admin
php artisan make:admin
```

---

## 🚀 Lancer l'Application

### Terminal 1 : Serveur Laravel
```bash
php artisan serve
```
L'application sera accessible à : **http://localhost:8000**

### Terminal 2 : Compilation Frontend
```bash
npm run dev
```

### ✅ Application Prête !

Connectez-vous avec les identifiants admin que vous avez créés.

---

## 📝 Utilisateurs de Test

Vous pouvez créer d'autres utilisateurs via le panneau admin ou importer un fichier Excel.

**Format d'import Excel :**
| Prénom | Nom | Email | Rôle | Mot de passe |
|--------|-----|-------|------|--------------|
| Jean | Dupont | jean@example.com | teacher | password123 |
| Marie | Martin | marie@example.com | delegate | password123 |

---

## 🔧 Commandes Utiles

```bash
# Nettoyer les caches
php artisan cache:clear
php artisan view:clear

# Réinitialiser la base de données (⚠️ SUPPRIME LES DONNÉES)
php artisan migrate:refresh

# Lancer les tests
php artisan test

# Accéder à la console interactive
php artisan tinker
```

---

## 📚 Fonctionnalités Principales

✅ **Admin Dashboard** - Statistiques et gestion
✅ **Gestion des Utilisateurs** - CRUD + Import/Export
✅ **Templates d'Emploi du Temps** - CRUD complet
✅ **Gestion des Séances** - Détection de conflits
✅ **Rapports Pédagogiques** - Création et validation
✅ **Demandes de Modification** - Workflow d'approbation
✅ **Notifications** - Système complet
✅ **Emploi du Temps Public** - Vue calendaire responsive

---

## ⚙️ Mode Production

```bash
# Compiler les assets pour la production
npm run build

# Optimiser pour la production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🆘 Dépannage

**Erreur de connexion à la base de données ?**
- Vérifiez que MySQL est lancé
- Vérifiez les identifiants dans `.env`
- Assurez-vous que la base de données existe

**Assets CSS/JS ne chargent pas ?**
- Lancez `npm run dev` ou `npm run build`
- Videz le cache du navigateur (Ctrl+Shift+Delete)

**Erreur "Command not found" ?**
- Assurez-vous d'être dans le répertoire `gestion-academique`
- Vérifiez que tous les prérequis sont installés

---

**Documentation Complète :** Consultez `CAHIER_CONCEPTION_IMPLEMENTATION.md`

**Besoin d'aide ?** Consultez le fichier README.md ou contactez l'administrateur.
