# Système de Gestion Académique

## Description du Projet

Ce projet est une application web académique développée avec Laravel, conçue pour centraliser, automatiser et sécuriser la gestion des séances de cours et de TD du département d'Informatique de l'Université de Yaoundé I. Il vise à résoudre les problèmes de conflits d'horaires, de suivi pédagogique, de coordination entre les acteurs (administration, enseignants, étudiants) et de génération de rapports fiables.

## Fonctionnalités Implémentées

Nous avons mis en place une base solide pour l'application, incluant :

1.  **Infrastructure de Base :**
    *   Projet Laravel initialisé et configuré avec MySQL.
    *   Toutes les tables de la base de données (Utilisateurs, Filières, UEs, Salles, Groupes, Séances, Rapports de Séance, Notifications, Absences) sont créées via des migrations.
    *   Interface utilisateur de base intégrée à partir de la maquette HTML (`TP8.html`) dans un layout Blade unifié (`layouts/app.blade.php`).
    *   Assets frontend (Tailwind CSS, Alpine.js) configurés via Vite.

2.  **Authentification et Autorisation par Rôle :**
    *   Système d'authentification complet via Laravel Breeze.
    *   Formulaire de connexion personnalisé avec sélection de rôle.
    *   Logique d'authentification basée sur l'email, le mot de passe et le rôle de l'utilisateur.
    *   Tableaux de bord spécifiques par rôle (Admin, Enseignant, Délégué) avec des vues initiales.
    *   `RoleMiddleware` pour le contrôle d'accès basé sur les rôles.
    *   **Inscription publique désactivée** pour des raisons de sécurité.
    *   Commande Artisan (`php artisan make:admin`) pour la création sécurisée du premier utilisateur administrateur.

3.  **Panneau d'Administration (CRUD) :**
    *   **Utilisateurs :** CRUD complet pour la gestion des utilisateurs (prénom, nom, email, rôle, mot de passe).
    *   **Filières :** CRUD complet pour la gestion des filières (nom, code, enseignant responsable).
    *   **UEs :** CRUD complet pour la gestion des unités d'enseignement (code, nom, filière, enseignant assigné).
    *   **Salles :** CRUD complet pour la gestion des salles (numéro, capacité).
    *   **Groupes :** CRUD complet pour la gestion des groupes d'étudiants (nom, filière associée).
    *   **Séances :** CRUD complet pour la gestion des séances (UE, date, heures de début/fin, salle, groupe, enseignant).
    *   **Import Excel pour Utilisateurs :** Fonctionnalité d'importation d'utilisateurs via un fichier Excel (`.xlsx`, `.xls`, `.csv`).
    *   **Tableau de bord Admin :** Affichage de statistiques clés (nombre total d'utilisateurs par rôle, entités, séances par statut, avancement global des UE) et notifications récentes.

4.  **Améliorations de la Gestion des Séances :**
    *   **Détection des Conflits :** Implémentation de la détection automatique des conflits d'horaires (salle, enseignant, groupe) lors de la création/modification des séances.
    *   **Vue Emploi du Temps :** Une vue publique pour consulter les emplois du temps avec des filtres (date, filière, groupe, enseignant).

5.  **Suivi Pédagogique (Spécifique aux Enseignants) :**
    *   **Mise à jour du Statut des Séances :** Les enseignants peuvent marquer leurs séances comme 'effectuée' ou 'annulée' depuis leur tableau de bord.
    *   **Création/Consultation de Rapports de Séance :** Les enseignants peuvent créer des rapports pour leurs séances et consulter les rapports existants.
    *   **Suivi de l'Avancement des UE :** Les enseignants peuvent visualiser le pourcentage d'avancement de leurs UEs assignées.

6.  **Communication (Spécifique à l'Admin) :**
    *   **Envoi de Notifications Ciblées :** L'administrateur peut envoyer des notifications à des utilisateurs (enseignants/délégués), filières ou groupes spécifiques.
    *   **Liste des Notifications :** L'administrateur peut consulter la liste de toutes les notifications envoyées.
    *   **Alertes de Modification/Annulation :** Envoi automatique de notifications aux parties concernées lors de la création, modification ou suppression d'une séance.

## Technologies Utilisées

*   **Backend :** PHP 8.x, Laravel 12.x
*   **Base de données :** MySQL
*   **Frontend :** HTML, Tailwind CSS, Alpine.js, JavaScript
*   **Dépendances PHP :** `maatwebsite/excel` pour l'import/export Excel.

## Instructions de Configuration Locale

Suivez ces étapes pour configurer le projet sur votre machine locale :

1.  **Cloner le dépôt :**
    ```bash
    git clone <URL_DU_DEPOT>
    cd gestion-academique
    ```

2.  **Installer les dépendances Composer :**
    ```bash
    php composer.phar install
    ```
    *(Si `composer` n'est pas disponible globalement, utilisez `php ../composer.phar install` depuis le répertoire `gestion-academique`)*

3.  **Configurer le fichier `.env` :**
    *   Copiez le fichier `.env.example` et renommez-le en `.env` :
        ```bash
        cp .env.example .env
        ```
    *   Générez une clé d'application :
        ```bash
        php artisan key:generate
        ```
    *   Modifiez les paramètres de connexion à la base de données MySQL dans `.env` :
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=gestion_academique # Assurez-vous que cette DB existe
        DB_USERNAME=laravel_user      # L'utilisateur MySQL que vous avez créé
        DB_PASSWORD=jimand123         # Le mot de passe de l'utilisateur MySQL
        ```
    *   **Créer la base de données et l'utilisateur MySQL** si ce n'est pas déjà fait (remplacez `jimand123` par votre mot de passe choisi) :
        ```sql
        CREATE DATABASE gestion_academique;
        CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'jimand123';
        GRANT ALL PRIVILEGES ON gestion_academique.* TO 'laravel_user'@'localhost';
        FLUSH PRIVILEGES;
        ```

4.  **Exécuter les migrations de la base de données :**
    ```bash
    php artisan migrate
    ```

5.  **Installer les dépendances Node.js et compiler les assets :**
    ```bash
    npm install
    npm run dev
    ```
    *(Assurez-vous d'avoir Node.js et npm installés. Si `npm` n'est pas trouvé, installez-le.)*

6.  **Créer un utilisateur administrateur (obligatoire pour commencer) :**
    ```bash
    php artisan make:admin
    ```
    *(Suivez les invites pour entrer le prénom, le nom, l'email et le mot de passe de l'administrateur. Choisissez le rôle `admin`.)*

7.  **Démarrer le serveur de développement Laravel :**
    ```bash
    php artisan serve
    ```
    L'application sera accessible à l'adresse : `http://127.0.0.1:8000`

## Concepts Clés et Architecture

*   **MVC (Model-View-Controller) :** Laravel suit le modèle MVC pour organiser le code.
*   **Blade Templating :** Utilisation de Blade pour les vues, avec un layout principal (`layouts/app.blade.php`) pour la cohérence de l'interface.
*   **Authentification par Rôle :** Les utilisateurs sont authentifiés et autorisés en fonction de leur rôle (`admin`, `teacher`, `delegate`). Le `RoleMiddleware` assure que seuls les utilisateurs autorisés accèdent à certaines routes.
*   **CRUD (Create, Read, Update, Delete) :** Implémentation des opérations CRUD pour la plupart des entités (Utilisateurs, Filières, UEs, Salles, Groupes, Séances).
*   **Détection de Conflits :** Logique métier intégrée pour éviter les chevauchements d'horaires lors de la gestion des séances.
*   **Notifications :** Système de notification pour informer les utilisateurs des changements importants.

## Prochaines Étapes / Comment Contribuer

Voici les principales tâches restantes pour le projet, par ordre de priorité suggéré :

1.  **Panneau Délégué - Validation des Rapports de Séance :**
    *   Implémenter le `DelegateController` et son tableau de bord.
    *   Permettre aux délégués de consulter les rapports des séances de leur groupe.
    *   Permettre aux délégués de modifier le statut d'un `RapportSeance` (par exemple, approuver/rejeter).
    *   Mettre à jour le modèle `RapportSeance` pour assigner correctement `délégué_id` lors de la validation.
    *   Ajouter les liens de navigation pour le tableau de bord du délégué.

2.  **Suivi Pédagogique - Avancement Global des UE (Admin) :**
    *   Développer une vue pour l'administrateur affichant l'avancement global des UE (par filière, par enseignant, etc.).

3.  **Communication - Affichage des Notifications Ciblées :**
    *   Afficher les notifications pertinentes sur les tableaux de bord des enseignants et des délégués.

4.  **Implémentation des autres fonctionnalités d'Import Excel :**
    *   Étendre la fonctionnalité d'import Excel aux autres entités (Filières, UEs, Salles, Groupes).

5.  **Rapports Pédagogiques Avancés :**
    *   Développer des fonctionnalités de rapports plus complètes, avec des options de filtrage et d'exportation.

6.  **Améliorations UI/UX :**
    *   Affiner l'interface utilisateur et l'expérience pour les formulaires et les tableaux.

7.  **Tests et Déploiement :**
    *   Écrire des tests unitaires et fonctionnels complets.
    *   Préparer l'application pour le déploiement en production.

## Dépannage

*   **`ParseError - syntax error, unexpected token "use"` dans `routes/web.php` :**
    *   Assurez-vous que toutes les déclarations `use App\Http\Controllers\...` sont placées **en haut du fichier `routes/web.php`**, juste après `<?php` et les autres `use` statements, et non à l'intérieur d'un groupe de routes.
*   **Les dropdowns ne fonctionnent pas (Administration, nom d'utilisateur) :**
    *   Assurez-vous d'avoir exécuté `npm install` et `npm run dev`.
    *   Vérifiez que la ligne `@vite(['resources/css/app.css', 'resources/js/app.js'])` est présente dans la section `<head>` de `resources/views/layouts/app.blade.php`.
    *   Vérifiez que la balise `<body>` dans `resources/views/layouts/app.blade.php` contient `x-data="{ open: false }"`.
*   **`could not find driver` (MySQL) :**
    *   Assurez-vous que l'extension PHP `php-mysql` est installée et activée sur votre système. Sur Arch Linux, cela implique généralement d'activer `extension=pdo_mysql` dans votre `php.ini` et de redémarrer votre serveur web.
*   **`Target class [Admin/AdminController] does not exist.` :**
    *   Vérifiez que le contrôleur existe bien dans `app/Http/Controllers/Admin/` et que le `use` statement correspondant est présent en haut de `routes/web.php`.
*   **Problèmes de base de données :**
    *   Vérifiez que votre base de données MySQL est en cours d'exécution.
    *   Assurez-vous que les informations de connexion dans votre fichier `.env` sont correctes.
    *   Vérifiez que la base de données `gestion_academique` et l'utilisateur `laravel_user` (ou vos noms choisis) existent et ont les permissions nécessaires.

---
Ce `README.md` est un document vivant. N'hésitez pas à le mettre à jour au fur et à mesure de l'avancement du projet.