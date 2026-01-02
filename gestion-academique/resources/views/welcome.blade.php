@extends('layouts.app')

@section('title', 'Accueil - Système de Gestion Académique')

@section('content')
    <!-- Hero Section - Full Width & Responsive -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary via-accent to-primary text-white w-screen relative left-1/2 right-1/2 -mx-[50vw] -mt-12 pt-12">
        <div class="container mx-auto text-center max-w-4xl px-4 sm:px-6 md:px-8">
            <div class="mb-8">
                <i class="fas fa-calendar-alt text-6xl mb-6 opacity-90"></i>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 leading-tight">
                Bienvenue au Système<br/>de Gestion Académique
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl mb-8 opacity-90 max-w-2xl mx-auto">
                Consultez facilement vos emplois du temps, accédez à vos séances et restez organisé
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('timetables.index') }}" class="inline-block bg-white text-primary font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-all transform hover:scale-105 text-lg">
                    <i class="fas fa-calendar-check mr-2"></i>Consulter l'emploi du temps
                </a>
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="inline-block bg-transparent border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-primary transition-all transform hover:scale-105 text-lg">
                        <i class="fas fa-arrow-right mr-2"></i>Aller au tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-block bg-transparent border-2 border-white text-white font-bold py-4 px-8 rounded-xl hover:bg-white hover:text-primary transition-all transform hover:scale-105 text-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <section class="py-16 sm:py-20 px-4 bg-white">
        <div class="container mx-auto max-w-4xl">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary mb-12 text-center">À Propos du Système</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Emplois du Temps</h3>
                    <p class="text-gray-600">Consultez facilement vos horaires de cours, TD et TP. Organisez votre semaine sans stress.</p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cube text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Gestion Centralisée</h3>
                    <p class="text-gray-600">Tous vos emplois du temps, rapports et informations au même endroit pour un accès rapide.</p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-success text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sync-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">À Jour en Temps Réel</h3>
                    <p class="text-gray-600">Les modifications et annonces sont mises à jour instantanément pour tous les utilisateurs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 px-4 bg-gray-50">
        <div class="container mx-auto max-w-4xl text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-primary mb-6">Prêt à commencer ?</h2>
            <p class="text-lg text-gray-600 mb-8">Accédez immédiatement aux emplois du temps publics ou connectez-vous pour plus de fonctionnalités.</p>
            <a href="{{ route('timetables.index') }}" class="inline-block bg-primary text-white font-bold py-3 px-8 rounded-xl hover:bg-accent transition-all transform hover:scale-105 text-lg">
                <i class="fas fa-arrow-right mr-2"></i>Consulter l'emploi du temps public
            </a>
        </div>
    </section>
@endsection