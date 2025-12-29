@extends('layouts.app')

@section('title', 'Accueil - Système de Gestion Académique')

@section('content')
    <!-- Section d'accueil -->
    <section id="home-section" class="mb-12">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Système de Gestion Académique</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Centralisez et automatisez la gestion des séances de cours et TD pour le département d'Informatique de l'Université de Yaoundé I
            </p>
        </div>
        
        <!-- Cartes de rôles -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-primary">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-2">Administrateur</h3>
                    <p class="text-gray-600">Gestion globale, planification, rapports et notifications</p>
                </div>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-success mr-2"></i>Tableau de bord global</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Gestion des utilisateurs</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Planification des séances</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-accent">
                <div class="text-center mb-4">
                    <div class="bg-accent text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-accent mb-2">Enseignant</h3>
                    <p class="text-gray-600">Consultation emploi du temps, gestion séances, rapports</p>
                </div>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-success mr-2"></i>Emploi du temps personnel</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Gestion des séances</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Rédaction des rapports</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-success">
                <div class="text-center mb-4">
                    <div class="bg-success text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-tie text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-success mb-2">Délégué</h3>
                    <p class="text-gray-600">Validation séances, communication, coordination</p>
                </div>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-success mr-2"></i>Validation des séances</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Communication groupe</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Suivi des activités</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-gray-400">
                <div class="text-center mb-4">
                    <div class="bg-gray-400 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">Étudiant</h3>
                    <p class="text-gray-600">Consultation emplois du temps et annonces</p>
                </div>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-success mr-2"></i>Emplois du temps</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Annonces département</li>
                    <li><i class="fas fa-check text-success mr-2"></i>Accès sans authentification</li>
                </ul>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-12">
            <h3 class="text-2xl font-bold text-primary mb-6">Aperçu du système</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary mb-2" id="session-count">0</div>
                    <p class="text-gray-600">Séances planifiées</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-accent mb-2" id="teacher-count">0</div>
                    <p class="text-gray-600">Enseignants actifs</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success mb-2" id="group-count">0</div>
                    <p class="text-gray-600">Groupes d'étudiants</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-600 mb-2" id="room-count">0</div>
                    <p class="text-gray-600">Salles disponibles</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Animation des compteurs
    function animateCounter(elementId, target) {
        const element = document.getElementById(elementId);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 30);
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Initialiser les compteurs
        animateCounter("session-count", 124);
        animateCounter("teacher-count", 28);
        animateCounter("group-count", 16);
        animateCounter("room-count", 42);
    });
</script>
@endpush