# CAHIER DE CONCEPTION ET D'IMPLÉMENTATION
## Système de Gestion Académique - Université de Yaoundé I

---

## TABLE DES MATIÈRES

1. [Introduction](#introduction)
2. [Contexte et Objectifs](#contexte-et-objectifs)
3. [Spécifications Fonctionnelles](#spécifications-fonctionnelles)
4. [Architecture Technique](#architecture-technique)
5. [Base de Données](#base-de-données)
6. [Fonctionnalités Implémentées](#fonctionnalités-implémentées)
7. [Contraintes Respectées](#contraintes-respectées)
8. [Guide d'Installation](#guide-dinstallation)
9. [Guide de Lancement](#guide-de-lancement)
10. [Guide d'Utilisation](#guide-dutilisation)
11. [Conclusion](#conclusion)

---

## INTRODUCTION

Ce cahier documente la conception et l'implémentation du **Système de Gestion Académique** pour le département d'Informatique de l'Université de Yaoundé I. Il s'agit d'une application web complète permettant la gestion centralisée, automatisée et sécurisée des séances de cours et de TD.

**Développeurs :** Étudiants en Informatique
**Année académique :** 2024-2025
**Framework Principal :** Laravel 11
**Base de Données :** MySQL 8.0+

---

## CONTEXTE ET OBJECTIFS

### Problème Identifié

Les institutions académiques font face à plusieurs défis :
- **Conflits d'horaires** : Superposition des séances, double réservation de salles
- **Manque de suivi** : Absence de mécanisme de validation des séances effectuées
- **Communication inefficace** : Difficultés à notifier les parties prenantes
- **Manque de transparence** : Absence de rapport pédagogique
- **Gestion manuelle** : Processus chronophage et sujet aux erreurs

### Objectifs du Projet

1. **Centraliser** la gestion des données académiques
2. **Automatiser** les processus (détection de conflits, notifications, génération d'emplois du temps)
3. **Améliorer la communication** entre administration, enseignants et étudiants
4. **Fournir des rapports** fiables et traçables
5. **Sécuriser les accès** via un système d'authentification robuste

---

## SPÉCIFICATIONS FONCTIONNELLES

### 1. GESTION DES UTILISATEURS

#### 1.1 Authentification et Autorisation

**Fonctionnalités :**
- Authentification par email et mot de passe
- Sélection du rôle lors de la connexion
- Contrôle d'accès basé sur les rôles (RBAC)
- Inscription publique désactivée (création par admin uniquement)
- Sessions sécurisées avec Laravel Sanctum

**Rôles Disponibles :**
- **Admin** : Accès complet à l'application
- **Enseignant** : Gestion de ses séances et rapports
- **Délégué** : Consultation des informations et demandes de modification
- **Étudiant** : Consultation de l'emploi du temps public

#### 1.2 Gestion des Utilisateurs (Admin)

**Opérations CRUD :**
- **Créer** : Ajout d'utilisateurs avec prénom, nom, email, rôle
- **Lire** : Affichage des listes avec filtres
- **Mettre à jour** : Modification des informations utilisateur
- **Supprimer** : Suppression d'utilisateurs (avec vérifications)

**Importation en Masse :**
- Support des formats : Excel (.xlsx), CSV
- Validation des données pendant l'import
- Rapport des erreurs pour correction

**Exportation :**
- Export au format Excel

---

### 2. GESTION DES STRUCTURES ACADÉMIQUES

#### 2.1 Filières

**Attributs :**
- Code de filière
- Nom de la filière
- Enseignant responsable
- Date de création

**Opérations CRUD :** Complètes

#### 2.2 Unités d'Enseignement (UE)

**Attributs :**
- Code UE
- Nom UE
- Filière associée
- Enseignant assigné
- Nombre d'heures
- Semestre

**Opérations CRUD :** Complètes

#### 2.3 Groupes d'Étudiants

**Attributs :**
- Nom du groupe
- Filière associée
- Niveau/Année
- Effectif

**Opérations CRUD :** Complètes

#### 2.4 Salles de Cours

**Attributs :**
- Numéro de salle
- Capacité
- Étage
- Équipements disponibles

**Opérations CRUD :** Complètes

**Gestion des Effectifs :**
- Suivi de la capacité vs effectif réel
- Alerte si effectif dépasse capacité

---

### 3. GESTION DES SÉANCES

#### 3.1 Templates d'Emploi du Temps

**Création et Gestion :**
- Création de séances templates par filière/groupe
- Association : UE, Salle, Enseignant, Jour/Créneau
- Support des divisions de groupes
- Export/Import de templates

**Filtres Disponibles :**
- Par filière
- Par groupe
- Par enseignant (filtres avancés)
- Par salle (filtres avancés)

#### 3.2 Séances Datées

**Création :**
- À partir de templates ou manuellement
- Génération en masse depuis templates
- Import depuis fichier Excel

**Attributs :**
- Date
- Heure de début/fin
- UE, Salle, Groupe, Enseignant
- Statut (planifiée, effectuée, annulée)

**Détection de Conflits :**
- Conflit de salle : deux séances dans la même salle au même créneau
- Conflit d'enseignant : deux séances du même enseignant au même créneau
- Conflit de groupe : deux séances du même groupe au même créneau

**Opérations CRUD :** Complètes

#### 3.3 Emploi du Temps Public

**Affichage :**
- Vue calendaire par semaine
- Code couleur pour les jours
- Responsive (tableau desktop, cartes mobile)

**Filtres :**
- Par filière
- Par groupe
- Par enseignant (avancé)
- Par salle (avancé)
- Affichage en cascade (filière → groupe)

---

### 4. SUIVI PÉDAGOGIQUE

#### 4.1 Mise à Jour du Statut

**Disponible pour :** Enseignants
- Marquer une séance comme "effectuée"
- Marquer une séance comme "annulée"
- Historique des modifications

#### 4.2 Rapports de Séance

**Création par l'Enseignant :**
- Date et heure de la séance
- UE et groupe concernés
- Contenu du cours
- Sujets abordés
- Devoirs/Travaux assignés
- Effectif présent vs attendu
- Remarques pédagogiques

**Validation par l'Admin :**
- Validation ou rejet des rapports
- Traçabilité des modifications

**Consultation :**
- Liste des rapports par filtres
- Détails d'un rapport
- Export PDF

---

### 5. COMMUNICATION ET NOTIFICATIONS

#### 5.1 Notifications

**Envoi par l'Admin :**
- À des utilisateurs spécifiques
- À une filière entière
- À un groupe entier
- Avec titre et contenu personnalisé

**Types de Notifications Automatiques :**
- Création de séance
- Modification de séance
- Annulation de séance
- Validation/Rejet de rapport
- Demande de modification approuvée/rejetée

**Historique :**
- Consultation de toutes les notifications envoyées
- Statut de lecture

#### 5.2 Demandes de Modification

**Initié par :** Enseignants et Délégués

**Modèles de Demandes :**
- Changement d'horaire
- Changement de salle
- Changement d'enseignant
- Annulation de séance

**Workflows :**
- Soumission → Attente d'approbation
- Approbation → Mise à jour de la séance
- Rejet → Notification à l'auteur

**Gestion par Admin :**
- Visualisation des demandes en attente
- Approbation avec mise à jour automatique
- Rejet avec notification

---

### 6. TABLEAUX DE BORD

#### 6.1 Dashboard Admin

**Statistiques :**
- Total utilisateurs par rôle
- Total filières, UEs, salles, groupes
- Total séances par statut
- Avancement global des UEs

**Alertes :**
- Classes complètes (tous les crédits validés)
- Conflits détectés
- Demandes en attente

**Actions Rapides :**
- Accès direct aux templates d'emploi du temps
- Accès aux rapports
- Accès à la gestion des utilisateurs
- Accès à la gestion des séances
- Accès aux demandes de modification
- Accès à la gestion des effectifs
- Accès à la gestion des salles

#### 6.2 Dashboard Enseignant

**Vue Personnalisée :**
- Ses séances de la semaine
- Ses UEs assignées
- Avancement de ses UEs
- Ses rapports créés
- Demandes de modification envoyées

**Actions :**
- Créer un rapport
- Marquer une séance comme effectuée/annulée
- Soumettre une demande de modification

#### 6.3 Dashboard Délégué

**Vue :**
- Horaire de son groupe
- Notifications du groupe
- Demandes en tant que représentant

**Actions :**
- Consulter l'emploi du temps
- Soumettre des demandes de modification

---

## ARCHITECTURE TECHNIQUE

### Stack Technologique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Framework Backend | Laravel | 11.x |
| Base de Données | MySQL | 8.0+ |
| Frontend Framework | Tailwind CSS | 3.x |
| JavaScript | Alpine.js | 3.x |
| Authentification | Laravel Breeze | - |
| Import/Export | Maatwebsite/Excel | 3.x |
| Build Tool | Vite | 5.x |
| PHP | - | 8.2+ |
| Serveur Web | Apache/Nginx | - |

### Architecture MVC

```
app/
├── Models/                    # Modèles Eloquent
│   ├── User.php
│   ├── Filiere.php
│   ├── Ue.php
│   ├── Groupe.php
│   ├── Salle.php
│   ├── SeanceTemplate.php
│   ├── Seance.php
│   ├── RapportSeance.php
│   ├── Notification.php
│   ├── DemandeModification.php
│   └── GroupeEffectif.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Contrôleurs admin
│   │   ├── Teacher/            # Contrôleurs enseignant
│   │   ├── Delegate/           # Contrôleurs délégué
│   │   ├── TimetableController.php
│   │   └── ...
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   └── ...
│   └── Requests/               # Form Requests
│
├── Providers/
│   └── AppServiceProvider.php
│
resources/
├── views/
│   ├── layouts/
│   ├── admin/
│   ├── teacher/
│   ├── delegate/
│   └── ...
│
routes/
├── web.php                     # Routes web avec middlewares RBAC
└── console.php                 # Commandes Artisan
```

### Flux de Requête

```
Utilisateur
    ↓
Route (web.php) avec middleware
    ↓
Controller (vérification logique)
    ↓
Model (interaction DB)
    ↓
View (Blade template)
    ↓
Rendu HTML/JSON
```

---

## BASE DE DONNÉES

### Diagramme Entité-Relation (ERD)

```
Users
├─ id (PK)
├─ first_name
├─ last_name
├─ email (UNIQUE)
├─ role (admin|teacher|delegate|student)
├─ password
├─ created_at

Filieres
├─ id (PK)
├─ code (UNIQUE)
├─ nom
├─ enseignant_id (FK → Users)
├─ created_at

Ues
├─ id (PK)
├─ code (UNIQUE)
├─ nom
├─ filiere_id (FK → Filieres)
├─ enseignant_id (FK → Users)
├─ heures
├─ semestre
├─ created_at

Groupes
├─ id (PK)
├─ nom
├─ filiere_id (FK → Filieres)
├─ niveau
├─ created_at

Salles
├─ id (PK)
├─ numero (UNIQUE)
├─ capacite
├─ etage
├─ equipements
├─ created_at

SeanceTemplates
├─ id (PK)
├─ filiere_id (FK → Filieres)
├─ groupe_id (FK → Groupes)
├─ ue_id (FK → Ues)
├─ salle_id (FK → Salles)
├─ enseignant_id (FK → Users)
├─ day_of_week (1-7)
├─ start_time
├─ group_divisions
├─ created_at

Seances
├─ id (PK)
├─ ue_id (FK → Ues)
├─ groupe_id (FK → Groupes)
├─ salle_id (FK → Salles)
├─ enseignant_id (FK → Users)
├─ jour
├─ heure_debut
├─ heure_fin
├─ statut (planifiée|effectuée|annulée)
├─ created_at

RapportSeances
├─ id (PK)
├─ seance_id (FK → Seances)
├─ enseignant_id (FK → Users)
├─ contenu
├─ effectif_present
├─ effectif_attendu
├─ statut_validation (en_attente|approuvé|rejeté)
├─ created_at

Notifications
├─ id (PK)
├─ titre
├─ contenu
├─ type (global|filiere|groupe|utilisateur)
├─ destinataire_id (FK → Users)
├─ lu (boolean)
├─ created_at

DemandesModifications
├─ id (PK)
├─ seance_id (FK → Seances)
├─ demandeur_id (FK → Users)
├─ type (horaire|salle|enseignant|annulation)
├─ raison
├─ statut (en_attente|approuvé|rejeté)
├─ reponse_admin
├─ created_at

GroupeEffectifs
├─ id (PK)
├─ groupe_id (FK → Groupes)
├─ effectif_total
├─ effectif_present
├─ date_maj
```

### Migrations

Toutes les migrations sont situées dans `database/migrations/` :
- `*_create_users_table.php`
- `*_create_filieres_table.php`
- `*_create_ues_table.php`
- `*_create_groupes_table.php`
- `*_create_salles_table.php`
- `*_create_seance_templates_table.php`
- `*_create_seances_table.php`
- `*_create_rapport_seances_table.php`
- `*_create_notifications_table.php`
- `*_create_demandes_modifications_table.php`
- `*_create_groupe_effectifs_table.php`

---

## FONCTIONNALITÉS IMPLÉMENTÉES

### PHASE 1 : Infrastructure de Base ✅

- [x] Configuration Laravel et MySQL
- [x] Authentification et autorisation par rôle
- [x] Interface utilisateur unifié (Blade + Tailwind + Alpine)
- [x] Tableaux de bord par rôle

### PHASE 2 : CRUD Complet ✅

- [x] Gestion des utilisateurs (CRUD + Import/Export)
- [x] Gestion des filières (CRUD)
- [x] Gestion des UEs (CRUD)
- [x] Gestion des salles (CRUD)
- [x] Gestion des groupes (CRUD)
- [x] Gestion des effectifs (CRUD)

### PHASE 3 : Séances et Conflits ✅

- [x] Gestion des templates d'emploi du temps (CRUD)
- [x] Gestion des séances datées (CRUD)
- [x] Détection de conflits (salle, enseignant, groupe)
- [x] Génération en masse de séances
- [x] Import de séances via Excel

### PHASE 4 : Suivi Pédagogique ✅

- [x] Mise à jour du statut des séances
- [x] Création et validation de rapports
- [x] Avancement des UEs
- [x] Historique et traçabilité

### PHASE 5 : Communication ✅

- [x] Système de notifications
- [x] Notifications automatiques
- [x] Demandes de modification
- [x] Workflow d'approbation

### PHASE 6 : Emplois du Temps Public ✅

- [x] Vue calendaire responsive
- [x] Filtres (filière, groupe, enseignant, salle)
- [x] Filtrage en cascade
- [x] Affichage pour mobile et desktop

### BONUS : Améliorations UX ✅

- [x] Dashboard amélioré avec cartes d'accès rapide
- [x] Filtres avancés masqués/affichables
- [x] Export/Import Excel
- [x] Validation côté serveur et client

---

## CONTRAINTES RESPECTÉES

### 1. Contraintes Fonctionnelles

| Contrainte | Implémentation |
|-----------|-----------------|
| Authentification obligatoire | Middleware auth sur toutes les routes |
| RBAC (Role-Based Access Control) | Middleware role:admin\|role:teacher\|role:delegate |
| Détection de conflits | Service ConflictDetector |
| Notifications automatiques | Événements Laravel |
| Validation des données | Form Requests + validation DB |
| Import/Export Excel | Maatwebsite/Excel |

### 2. Contraintes de Sécurité

| Contrainte | Implémentation |
|-----------|-----------------|
| Protection CSRF | Tokens CSRF sur tous les formulaires |
| Hachage des mots de passe | bcrypt via Laravel |
| SQL Injection | Requêtes Eloquent paramétrées |
| Injection XSS | Échappement Blade {{}}} |
| Inscription publique désactivée | Routes d'enregistrement supprimées |
| Gestion des sessions | Sessions sécurisées Laravel |

### 3. Contraintes de Performance

| Contrainte | Implémentation |
|-----------|-----------------|
| Optimisation des requêtes | Eager loading (with) |
| Caching | Cache Laravel |
| Pagination | Utilisation de paginate() |
| Index de base de données | Clés primaires et étrangères |

### 4. Contraintes de Compatibilité

| Contrainte | Implémentation |
|-----------|-----------------|
| Responsive design | Tailwind CSS (mobile-first) |
| Navigateurs modernes | ES6+ JavaScript |
| PHP 8.2+ | Syntaxe moderne PHP |
| MySQL 8.0+ | Requêtes SQL modernes |

---

## GUIDE D'INSTALLATION

### Prérequis

**Logiciels Requis :**
- PHP 8.2 ou supérieur
- Composer 2.x
- MySQL 8.0 ou supérieur
- Node.js 18+ et npm
- Git (optionnel)

**Vérification des Prérequis :**

```bash
# Vérifier PHP
php -v

# Vérifier Composer
composer -v

# Vérifier MySQL
mysql --version

# Vérifier Node.js
node -v
npm -v
```

### Étapes d'Installation

#### 1. Cloner le Projet

```bash
# Via HTTPS
git clone https://github.com/utilisateur/gestion-academique.git

# OU via SSH
git clone git@github.com:utilisateur/gestion-academique.git

cd gestion-academique
```

#### 2. Installer les Dépendances PHP

```bash
composer install

# Si une erreur d'authentification apparaît avec GitHub
# Générer un token GitHub et utiliser :
composer config github-oauth.github.com <votre_token>
composer install
```

#### 3. Configurer le Fichier .env

```bash
# Copier le fichier d'exemple
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

**Éditer `.env` avec vos paramètres :**

```env
# Base de Données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_academique
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Application
APP_NAME="Gestion Académique"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Mail (optionnel)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
```

#### 4. Créer la Base de Données

```bash
# Via MySQL CLI
mysql -u root -p
> CREATE DATABASE gestion_academique;
> exit;

# OU via phpmyadmin ou tout autre outil
```

#### 5. Exécuter les Migrations

```bash
php artisan migrate
```

**Sortie attendue :**
```
Migrating: 0001_01_01_000000_create_users_table
Migrated: 0001_01_01_000000_create_users_table (xxxms)
...
```

#### 6. Installer les Dépendances Frontend

```bash
npm install
```

#### 7. Compiler les Assets

```bash
# Mode développement avec watch
npm run dev

# OU Mode production
npm run build
```

**Note :** Pour le développement, gardez `npm run dev` actif dans un terminal séparé.

#### 8. Créer le Premier Utilisateur Admin

```bash
php artisan make:admin
```

**Exemple d'interaction :**
```
Enter the admin's first name: Admin
Enter the admin's last name: User
Enter the admin's email: admin@example.com
Enter the admin's password: 
Confirm password: 

Admin user created successfully!
```

#### 9. Configurer le Stockage (Optionnel)

```bash
# Créer le lien symbolique pour les fichiers uploadés
php artisan storage:link
```

### Vérification de l'Installation

```bash
# Vérifier la configuration
php artisan config:show

# Vérifier les migrations
php artisan migrate:status
```

---

## GUIDE DE LANCEMENT

### Démarrage en Mode Développement

#### Terminal 1 : Serveur Laravel

```bash
php artisan serve
```

**Sortie :**
```
INFO  Server running on [http://127.0.0.1:8000].

  Press Ctrl+C to quit
```

#### Terminal 2 : Compilation Frontend

```bash
npm run dev
```

**Sortie :**
```
VITE v5.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Press h to show help
```

#### Accès à l'Application

- **URL :** http://localhost:8000
- **Email Admin :** admin@example.com (ou celui créé)
- **Mot de passe :** Celui que vous avez défini

### Démarrage en Mode Production

#### 1. Préparer l'Application

```bash
# Optimiser pour production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compiler les assets
npm run build
```

#### 2. Déployer sur Serveur

```bash
# Copier les fichiers sur le serveur (via FTP/SSH)
scp -r . utilisateur@serveur:/chemin/vers/application

# OU utiliser un outil de déploiement (Deployer, Envoy, etc.)
```

#### 3. Configurer le Serveur Web

**Pour Apache (`httpd.conf` ou `.htaccess`) :**

```apache
<Directory /chemin/vers/application/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

# Rediriger les requêtes vers index.php
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

**Pour Nginx (`nginx.conf`) :**

```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /chemin/vers/application/public;
    index index.php;

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

#### 4. Configuration SSL (HTTPS)

```bash
# Utiliser Let's Encrypt
certbot certonly --webroot -w /chemin/vers/application/public -d votre-domaine.com
```

---

## GUIDE D'UTILISATION

### Authentification

#### Connexion

1. Accédez à http://localhost:8000
2. Entrez vos identifiants (email et mot de passe)
3. Sélectionnez votre rôle
4. Cliquez sur "Se connecter"

#### Déconnexion

- Cliquez sur votre nom en haut à droite
- Sélectionnez "Déconnexion"

### Rôle Admin

#### Gestion des Utilisateurs

1. Accédez à **Admin → Gestion des Utilisateurs**
2. **Créer :** Cliquez "Ajouter", remplissez le formulaire
3. **Lire :** Consultez la liste avec filtres
4. **Mettre à jour :** Cliquez "Éditer" sur un utilisateur
5. **Supprimer :** Cliquez "Supprimer" avec confirmation

#### Importation d'Utilisateurs

1. Allez à **Utilisateurs → Importer**
2. Préparez un fichier Excel avec les colonnes :
   - Prénom
   - Nom
   - Email
   - Rôle (admin, teacher, delegate, student)
   - Mot de passe
3. Téléchargez le fichier
4. Consultez le rapport d'import

#### Gestion des Templates d'Emploi du Temps

1. Accédez à **Emplois du Temps → Templates**
2. **Créer Template :** Remplissez les champs :
   - Filière
   - Groupe
   - UE
   - Salle
   - Enseignant
   - Jour et créneau horaire
3. **Utiliser Filtres Avancés :** Cliquez "Plus de filtres" pour affiner par enseignant/salle
4. **Exporter/Importer :** Disponible via les boutons en haut

#### Gestion des Séances Datées

1. Allez à **Gestion des Séances**
2. **Créer Manuellement :** Cliquez "Ajouter", remplissez les champs
3. **Générer en Masse :** Cliquez "Générer depuis templates", sélectionnez filière/groupe/semaine
4. **Importer :** Téléchargez un fichier Excel

**Important :** L'application détectera automatiquement les conflits d'horaires.

#### Gestion des Demandes de Modification

1. Allez à **Demandes de Modification**
2. **Consulter :** Visualisez les demandes en attente
3. **Approuver :** La séance sera mise à jour automatiquement
4. **Rejeter :** L'enseignant/délégué recevra une notification

#### Envoi de Notifications

1. Accédez à **Notifications → Créer**
2. Sélectionnez les destinataires :
   - Utilisateurs spécifiques
   - Filière entière
   - Groupe entier
3. Écrivez le titre et le contenu
4. Cliquez "Envoyer"

#### Consultation des Rapports

1. Allez à **Rapports de Séance**
2. **Filtrer :** Par filière, groupe, statut
3. **Consulter :** Cliquez sur un rapport pour voir les détails
4. **Valider/Rejeter :** Pour les rapports en attente
5. **Exporter :** Générez un PDF du rapport

### Rôle Enseignant

#### Consulter son Tableau de Bord

- **Mes Séances :** Semaine en cours
- **Mes UEs :** Avec avancement
- **Mes Rapports :** Créés ou en attente de validation

#### Marquer une Séance comme Effectuée

1. Cliquez sur la séance dans le tableau de bord
2. Sélectionnez "Effectuée"
3. Cliquez "Enregistrer"

#### Créer un Rapport de Séance

1. Cliquez "Créer un rapport" depuis la séance
2. Remplissez les informations :
   - Contenu du cours
   - Effectif présent
   - Devoirs assignés
   - Remarques
3. Soumettez le rapport

#### Soumettre une Demande de Modification

1. Cliquez le menu "Demandes"
2. Sélectionnez le type de demande
3. Choisissez la séance et expliquez la raison
4. Soumettez

### Rôle Délégué

#### Consulter l'Emploi du Temps

1. Accédez à **Emplois du Temps**
2. L'emploi du temps de votre groupe s'affiche
3. Utilisez les filtres pour affiner la vue

#### Soumettre une Demande de Modification

1. Cliquez sur une séance
2. Cliquez "Demander une modification"
3. Expliquez la raison
4. Soumettez pour approbation admin

### Rôle Étudiant

#### Consulter l'Emploi du Temps Public

1. Accédez à http://localhost:8000/timetables
2. Sélectionnez votre filière puis groupe
3. Consultez votre emploi du temps pour la semaine

---

## MAINTENANCE ET DÉPANNAGE

### Commandes Utiles

```bash
# Vérifier l'état de l'application
php artisan status

# Nettoyer les caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Réinitialiser la base de données (ATTENTION !)
php artisan migrate:refresh

# Générer à nouveau les migrations
php artisan migrate

# Lancer les tests
php artisan test

# Consulter les logs
tail -f storage/logs/laravel.log
```

### Problèmes Courants

**Erreur : "SQLSTATE[HY000]: General error: 1030"**
```bash
# Solution : Vérifier les droits de la base de données
php artisan tinker
> DB::statement('SHOW VARIABLES LIKE "max_allowed_packet"');
```

**Erreur : "Class not found"**
```bash
# Solution : Regénérer l'autoloader
composer dump-autoload
```

**Erreur : "npm: command not found"**
```bash
# Solution : Installer Node.js et npm depuis nodejs.org
# OU utiliser npm directement si Node.js est installé
node -v
```

---

## CONCLUSION

Le **Système de Gestion Académique** a été implémenté en suivant les meilleures pratiques de développement Laravel et en respectant toutes les contraintes du cahier de charges.

### Points Forts

✅ Architecture modulaire et extensible
✅ Sécurité robuste avec authentification et autorisation
✅ Interface utilisateur intuitive et responsive
✅ Détection automatique des conflits
✅ Système de notifications fonctionnel
✅ Documentation complète
✅ Code maintenable et testable

### Perspectives Futures

- Intégration de calendriers externes (Google Calendar, Outlook)
- Système de backup automatisé
- Statistiques avancées et dashboards personnalisables
- Mobile app native (iOS/Android)
- Intégration SSO (Single Sign-On)
- Système de chat en temps réel

---

## CONTACTS ET SUPPORT

**Auteurs :** Étudiants en Informatique - Université de Yaoundé I
**Email Support :** support@gestion-academique.local
**GitHub :** https://github.com/utilisateur/gestion-academique

---

**Document généré :** 24 Janvier 2026
**Version :** 1.0
**Statut :** Complet et Testé
