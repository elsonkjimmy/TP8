# DOCUMENTATION TECHNIQUE - ARCHITECTURE ET STRUCTURE

## 📋 Table des Matières

1. [Structure du Projet](#structure-du-projet)
2. [Modèles et Relations](#modèles-et-relations)
3. [Contrôleurs et Routes](#contrôleurs-et-routes)
4. [Vues et Templates](#vues-et-templates)
5. [Services et Utilitaires](#services-et-utilitaires)
6. [Middlewares](#middlewares)
7. [Validations](#validations)
8. [Événements et Notifications](#événements-et-notifications)

---

## 1. Structure du Projet

```
gestion-academique/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── MakeAdminCommand.php         # Commande pour créer un admin
│   │   └── Kernel.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── AdminUserController.php
│   │   │   │   ├── AdminFiliereController.php
│   │   │   │   ├── AdminUeController.php
│   │   │   │   ├── AdminGroupeController.php
│   │   │   │   ├── AdminSalleController.php
│   │   │   │   ├── AdminSeanceController.php
│   │   │   │   ├── GroupeEffectifController.php
│   │   │   │   ├── AdminDemandeModificationController.php
│   │   │   │   ├── AdminNotificationController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   └── SeanceGeneratorController.php
│   │   │   │
│   │   │   ├── Teacher/
│   │   │   │   ├── TeacherController.php
│   │   │   │   ├── TeacherSeanceController.php
│   │   │   │   └── SeanceReportController.php
│   │   │   │
│   │   │   ├── SeanceTemplateController.php
│   │   │   └── TimetableController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php            # Contrôle d'accès par rôle
│   │   │   ├── Authenticate.php
│   │   │   └── RedirectIfAuthenticated.php
│   │   │
│   │   └── Requests/
│   │       ├── StoreUserRequest.php
│   │       ├── UpdateUserRequest.php
│   │       ├── StoreSeanceRequest.php
│   │       └── ... (autres Form Requests)
│   │
│   ├── Models/
│   │   ├── User.php                         # Modèle utilisateur (RBAC)
│   │   ├── Filiere.php                      # Modèle filière
│   │   ├── Ue.php                           # Modèle UE
│   │   ├── Groupe.php                       # Modèle groupe
│   │   ├── Salle.php                        # Modèle salle
│   │   ├── SeanceTemplate.php               # Modèle template
│   │   ├── Seance.php                       # Modèle séance
│   │   ├── RapportSeance.php                # Modèle rapport
│   │   ├── Notification.php                 # Modèle notification
│   │   ├── DemandeModification.php          # Modèle demande
│   │   └── GroupeEffectif.php               # Modèle effectif
│   │
│   ├── Services/
│   │   ├── ConflictDetectorService.php      # Détection de conflits
│   │   ├── NotificationService.php          # Envoi de notifications
│   │   ├── SeanceGeneratorService.php       # Génération de séances
│   │   └── ExcelImportService.php           # Import Excel
│   │
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   │
│   └── Observers/
│       └── SeanceObserver.php               # Observer pour événements séances
│
├── database/
│   ├── migrations/
│   │   ├── *_create_users_table.php
│   │   ├── *_create_filieres_table.php
│   │   ├── *_create_ues_table.php
│   │   ├── *_create_groupes_table.php
│   │   ├── *_create_salles_table.php
│   │   ├── *_create_seance_templates_table.php
│   │   ├── *_create_seances_table.php
│   │   ├── *_create_rapport_seances_table.php
│   │   ├── *_create_notifications_table.php
│   │   ├── *_create_demandes_modifications_table.php
│   │   └── *_create_groupe_effectifs_table.php
│   │
│   ├── factories/
│   │   └── UserFactory.php
│   │
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php                # Layout principal
│   │   │   └── guest.blade.php              # Layout public
│   │   │
│   │   ├── auth/
│   │   │   ├── login.blade.php              # Page de connexion
│   │   │   └── register.blade.php           # Page d'inscription (désactivée)
│   │   │
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php          # Dashboard admin
│   │   │   ├── users/
│   │   │   ├── filieres/
│   │   │   ├── ues/
│   │   │   ├── groupes/
│   │   │   ├── salles/
│   │   │   ├── seances/
│   │   │   ├── groupe-effectifs/
│   │   │   ├── demandes-modifications/
│   │   │   ├── notifications/
│   │   │   └── reports/
│   │   │
│   │   ├── teacher/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── seances/
│   │   │   └── reports/
│   │   │
│   │   ├── seance_templates/
│   │   │   ├── index.blade.php              # Vue templates + filtres avancés
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   │
│   │   └── timetables/
│   │       └── index.blade.php              # Vue emploi du temps public + filtres
│   │
│   ├── css/
│   │   └── app.css                          # Styles personnalisés
│   │
│   └── js/
│       └── app.js                           # Scripts JavaScript
│
├── routes/
│   ├── web.php                              # Routes web avec middlewares
│   └── console.php                          # Commandes Artisan
│
├── public/
│   ├── index.php                            # Point d'entrée
│   ├── css/
│   └── js/
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── mail.php
│   ├── queue.php
│   └── services.php
│
├── storage/
│   ├── app/                                 # Fichiers uploadés
│   ├── logs/                                # Fichiers logs
│   └── framework/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env                                     # Configuration locale
├── .env.example                             # Exemple de configuration
├── composer.json                            # Dépendances PHP
├── package.json                             # Dépendances Node.js
├── tailwind.config.js                       # Configuration Tailwind
├── vite.config.js                           # Configuration Vite
├── phpunit.xml                              # Configuration tests
├── artisan                                  # CLI Laravel
└── README.md                                # Documentation

```

---

## 2. Modèles et Relations

### User (Utilisateur)

```php
class User extends Model {
    // Attributs
    protected $fillable = ['first_name', 'last_name', 'email', 'role', 'password'];
    
    // Rôles : admin, teacher, delegate, student
    
    // Relations
    public function filieres() { return $this->hasMany(Filiere::class, 'enseignant_id'); }
    public function ues() { return $this->hasMany(Ue::class, 'enseignant_id'); }
    public function seances() { return $this->hasMany(Seance::class, 'enseignant_id'); }
    public function seanceTemplates() { return $this->hasMany(SeanceTemplate::class, 'enseignant_id'); }
    public function rapports() { return $this->hasMany(RapportSeance::class, 'enseignant_id'); }
    public function notifications() { return $this->hasMany(Notification::class, 'destinataire_id'); }
}
```

### Filiere (Filière)

```php
class Filiere extends Model {
    protected $fillable = ['code', 'nom', 'enseignant_id'];
    
    // Relations
    public function enseignant() { return $this->belongsTo(User::class, 'enseignant_id'); }
    public function ues() { return $this->hasMany(Ue::class); }
    public function groupes() { return $this->hasMany(Groupe::class); }
    public function seanceTemplates() { return $this->hasMany(SeanceTemplate::class); }
}
```

### Ue (Unité d'Enseignement)

```php
class Ue extends Model {
    protected $fillable = ['code', 'nom', 'filiere_id', 'enseignant_id', 'heures', 'semestre'];
    
    // Relations
    public function filiere() { return $this->belongsTo(Filiere::class); }
    public function enseignant() { return $this->belongsTo(User::class, 'enseignant_id'); }
    public function seanceTemplates() { return $this->hasMany(SeanceTemplate::class); }
    public function seances() { return $this->hasMany(Seance::class); }
}
```

### Groupe (Groupe d'Étudiants)

```php
class Groupe extends Model {
    protected $fillable = ['nom', 'filiere_id', 'niveau'];
    
    // Relations
    public function filiere() { return $this->belongsTo(Filiere::class); }
    public function seanceTemplates() { return $this->hasMany(SeanceTemplate::class); }
    public function seances() { return $this->hasMany(Seance::class); }
    public function effectif() { return $this->hasOne(GroupeEffectif::class); }
}
```

### Salle (Salle de Cours)

```php
class Salle extends Model {
    protected $fillable = ['numero', 'capacite', 'etage', 'equipements'];
    
    // Relations
    public function seanceTemplates() { return $this->hasMany(SeanceTemplate::class); }
    public function seances() { return $this->hasMany(Seance::class); }
}
```

### SeanceTemplate (Template d'Emploi du Temps)

```php
class SeanceTemplate extends Model {
    protected $fillable = [
        'filiere_id', 'groupe_id', 'ue_id', 'salle_id', 'enseignant_id',
        'day_of_week', 'start_time', 'group_divisions'
    ];
    
    // Relations
    public function filiere() { return $this->belongsTo(Filiere::class); }
    public function groupe() { return $this->belongsTo(Groupe::class); }
    public function ue() { return $this->belongsTo(Ue::class); }
    public function salle() { return $this->belongsTo(Salle::class); }
    public function enseignant() { return $this->belongsTo(User::class, 'enseignant_id'); }
}
```

### Seance (Séance Datée)

```php
class Seance extends Model {
    protected $fillable = [
        'ue_id', 'groupe_id', 'salle_id', 'enseignant_id',
        'jour', 'heure_debut', 'heure_fin', 'statut'
    ];
    
    // Statuts : planifiée, effectuée, annulée
    
    // Relations
    public function ue() { return $this->belongsTo(Ue::class); }
    public function groupe() { return $this->belongsTo(Groupe::class); }
    public function salle() { return $this->belongsTo(Salle::class); }
    public function enseignant() { return $this->belongsTo(User::class, 'enseignant_id'); }
    public function rapport() { return $this->hasOne(RapportSeance::class); }
    public function demandes() { return $this->hasMany(DemandeModification::class); }
}
```

### RapportSeance (Rapport de Séance)

```php
class RapportSeance extends Model {
    protected $fillable = [
        'seance_id', 'enseignant_id', 'contenu',
        'effectif_present', 'effectif_attendu', 'statut_validation'
    ];
    
    // Statuts : en_attente, approuvé, rejeté
    
    // Relations
    public function seance() { return $this->belongsTo(Seance::class); }
    public function enseignant() { return $this->belongsTo(User::class, 'enseignant_id'); }
}
```

### Notification

```php
class Notification extends Model {
    protected $fillable = ['titre', 'contenu', 'type', 'destinataire_id', 'lu'];
    
    // Types : global, filiere, groupe, utilisateur
    
    // Relations
    public function destinataire() { return $this->belongsTo(User::class, 'destinataire_id'); }
}
```

### DemandeModification (Demande de Modification)

```php
class DemandeModification extends Model {
    protected $fillable = [
        'seance_id', 'demandeur_id', 'type',
        'raison', 'statut', 'reponse_admin'
    ];
    
    // Types : horaire, salle, enseignant, annulation
    // Statuts : en_attente, approuvé, rejeté
    
    // Relations
    public function seance() { return $this->belongsTo(Seance::class); }
    public function demandeur() { return $this->belongsTo(User::class, 'demandeur_id'); }
}
```

### GroupeEffectif (Effectif du Groupe)

```php
class GroupeEffectif extends Model {
    protected $fillable = ['groupe_id', 'effectif_total', 'effectif_present', 'date_maj'];
    
    // Relations
    public function groupe() { return $this->belongsTo(Groupe::class); }
}
```

---

## 3. Contrôleurs et Routes

### Routes Web (routes/web.php)

```php
// Routes publiques
Route::get('/', ...);
Route::get('/timetables', TimetableController@index);  // Emploi du temps public

// Routes authentifiées
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ...);  // Redirection selon rôle
});

// Routes Admin (Middleware: auth, verified, role:admin)
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController@index);
    Route::resource('users', AdminUserController);
    Route::resource('filieres', AdminFiliereController);
    Route::resource('ues', AdminUeController);
    Route::resource('salles', AdminSalleController);
    Route::resource('groupes', AdminGroupeController);
    Route::resource('seances', AdminSeanceController);
    Route::resource('groupe-effectifs', GroupeEffectifController);
    Route::resource('demandes-modifications', AdminDemandeModificationController);
    Route::resource('seance-templates', SeanceTemplateController);
    // ... autres routes
});

// Routes Enseignant
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', TeacherController@dashboard);
    Route::patch('/seances/{seance}/status', TeacherSeanceController@updateStatus);
    Route::get('/seances/{seance}/reports/create', SeanceReportController@create);
    // ... autres routes
});
```

### Contrôleur Admin Exemple : AdminSeanceController

```php
class AdminSeanceController extends Controller {
    public function index(Request $request) {
        // Filtrage avec queryBuilder
        $query = Seance::with(['ue', 'groupe', 'salle', 'enseignant']);
        
        if ($request->filiere_id) {
            $query->whereHas('groupe', fn($q) => $q->where('filiere_id', $request->filiere_id));
        }
        
        $seances = $query->paginate(15);
        return view('admin.seances.index', compact('seances'));
    }
    
    public function store(StoreSeanceRequest $request) {
        // Validation automatique via Form Request
        
        // Détection de conflits
        $conflicts = ConflictDetectorService::detect($request->all());
        if ($conflicts->isNotEmpty()) {
            return back()->withErrors('Conflits détectés');
        }
        
        // Créer la séance
        $seance = Seance::create($request->validated());
        
        // Notifier les parties prenantes
        NotificationService::notifySeanceCreation($seance);
        
        return redirect()->route('admin.seances.index')->with('success', 'Séance créée');
    }
}
```

---

## 4. Vues et Templates

### Structure des Vues

**Layout Principal (layouts/app.blade.php) :**
```blade
<html>
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>@include('partials.navbar')</nav>
    <main>@yield('content')</main>
    <footer>@include('partials.footer')</footer>
</body>
</html>
```

**Vue Admin Séances :**
```blade
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gestion des Séances</h1>
        
        <!-- Formulaire de filtres -->
        <form method="GET" class="filters">
            <!-- Filtres principaux -->
            <!-- Filtres avancés (toggle) -->
        </form>
        
        <!-- Tableau des séances -->
        <table>
            @foreach($seances as $seance)
                <tr>
                    <td>{{ $seance->ue->nom }}</td>
                    <td>{{ $seance->jour }}</td>
                    <td>
                        <a href="{{ route('admin.seances.edit', $seance) }}">Éditer</a>
                    </td>
                </tr>
            @endforeach
        </table>
        
        <!-- Pagination -->
        {{ $seances->links() }}
    </div>
@endsection
```

---

## 5. Services et Utilitaires

### ConflictDetectorService

```php
class ConflictDetectorService {
    public static function detect($seanceData) {
        $conflicts = collect();
        
        // Conflit de salle
        if (self::hasSalleConflict($seanceData)) {
            $conflicts->push('Conflit : Salle déjà occupée');
        }
        
        // Conflit d'enseignant
        if (self::hasTeacherConflict($seanceData)) {
            $conflicts->push('Conflit : Enseignant déjà assigné');
        }
        
        // Conflit de groupe
        if (self::hasGroupConflict($seanceData)) {
            $conflicts->push('Conflit : Groupe déjà assigné');
        }
        
        return $conflicts;
    }
    
    private static function hasSalleConflict($data) {
        return Seance::where('salle_id', $data['salle_id'])
            ->whereDate('jour', $data['jour'])
            ->whereTime('heure_debut', '<', $data['heure_fin'])
            ->whereTime('heure_fin', '>', $data['heure_debut'])
            ->exists();
    }
    // ... autres méthodes
}
```

### NotificationService

```php
class NotificationService {
    public static function notifySeanceCreation(Seance $seance) {
        // Notifier l'enseignant
        Notification::create([
            'titre' => 'Nouvelle séance assignée',
            'contenu' => "Séance de {$seance->ue->nom} le {$seance->jour}",
            'destinataire_id' => $seance->enseignant_id,
            'type' => 'utilisateur'
        ]);
        
        // Notifier le groupe
        // ...
    }
}
```

---

## 6. Middlewares

### RoleMiddleware

```php
class RoleMiddleware {
    public function handle(Request $request, Closure $next, ...$roles) {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
}

// Utilisation dans routes : middleware('role:admin,teacher')
```

---

## 7. Validations

### Form Request Exemple

```php
class StoreSeanceRequest extends FormRequest {
    public function authorize() {
        return Auth::user()->role === 'admin';
    }
    
    public function rules() {
        return [
            'ue_id' => 'required|exists:ues,id',
            'groupe_id' => 'required|exists:groupes,id',
            'salle_id' => 'required|exists:salles,id',
            'enseignant_id' => 'required|exists:users,id',
            'jour' => 'required|date|after:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'statut' => 'in:planifiée,effectuée,annulée',
        ];
    }
}
```

---

## 8. Événements et Notifications

### Observer Séances

```php
class SeanceObserver {
    public function created(Seance $seance) {
        // Envoyer notification de création
    }
    
    public function updated(Seance $seance) {
        if ($seance->isDirty('statut')) {
            // Statut changé → notification
        }
    }
    
    public function deleted(Seance $seance) {
        // Notifier de la suppression
    }
}

// Enregistrer dans AppServiceProvider
Seance::observe(SeanceObserver::class);
```

---

## 📊 Diagramme UML Simplifié

```
User (admin|teacher|delegate|student)
    ↓ hasMany
Filiere --→ (enseignant) User
    ↓ hasMany
Ue --→ (enseignant) User
    ↓ hasMany
Groupe
    ↓ hasMany
Seance ←-- SeanceTemplate
    ↓ belongsTo
Salle, Ue, Groupe, User(enseignant)
    ↓ hasOne
RapportSeance
    ↓ belongsTo
User(enseignant)
    ↓ hasMany
Notification, DemandeModification

GroupeEffectif ←-- Groupe
```

---

**Document généré :** 24 Janvier 2026
**Version :** 1.0
