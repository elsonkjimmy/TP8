@extends('layouts.app')

@section('title', 'Accueil - Système de Gestion Académique')

@section('content')
    <!-- Hero Section with Glassmorphism -->
    <div class="relative min-h-[85vh] flex items-center justify-center overflow-hidden -mt-8">
        <!-- Abstract Background Shapes -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-900 via-primary to-purple-900"></div>
            <div class="absolute top-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-purple-500 opacity-20 blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-500 opacity-20 blur-[100px] animate-pulse delay-1000"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 text-center">
            <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-8 md:p-12 shadow-2xl max-w-4xl mx-auto transform hover:scale-[1.01] transition duration-500">
                <div class="mb-6 inline-block p-4 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 shadow-lg">
                    <i class="fas fa-graduation-cap text-5xl text-white drop-shadow-lg"></i>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight tracking-tight drop-shadow-md">
                    Gestion Académique <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200">Nouvelle Génération</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                    Simplifiez votre vie universitaire. Accédez à vos emplois du temps, gérez vos séances et restez connecté avec votre département en temps réel.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('timetables.index') }}" class="group relative px-8 py-4 bg-white text-primary font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
                            Emplois du Temps
                        </span>
                        <div class="absolute inset-0 bg-blue-50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="group px-8 py-4 bg-transparent border-2 border-white/50 text-white font-bold rounded-xl hover:bg-white/10 hover:border-white transition-all duration-300 backdrop-blur-sm flex items-center">
                            Tableau de Bord <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @else
                        <button onclick="openLoginModal()" class="group px-8 py-4 bg-transparent border-2 border-white/50 text-white font-bold rounded-xl hover:bg-white/10 hover:border-white transition-all duration-300 backdrop-blur-sm flex items-center cursor-pointer">
                            Connexion <i class="fas fa-sign-in-alt ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-white/50 text-2xl"></i>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50 relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <span class="text-primary font-semibold tracking-wider uppercase text-sm bg-purple-100 px-3 py-1 rounded-full">Fonctionnalités</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-4 mb-4">Tout ce dont vous avez besoin</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-primary to-accent mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Feature 1 -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2">
                    <div class="w-16 h-16 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 transform group-hover:rotate-6">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">Planification Intuitive</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Visualisez vos cours en un coup d'œil. Filtres dynamiques par niveau, filière et enseignant pour trouver exactement ce que vous cherchez.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2">
                    <div class="w-16 h-16 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300 transform group-hover:rotate-6">
                        <i class="fas fa-tasks text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-purple-600 transition-colors">Suivi Pédagogique</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Validation des séances, rapports numériques et desideratas pour les enseignants. Une gestion académique fluide et moderne.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:-translate-y-2">
                    <div class="w-16 h-16 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300 transform group-hover:rotate-6">
                        <i class="fas fa-bolt text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-green-600 transition-colors">Temps Réel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Modifications instantanées et notifications automatiques. Plus de confusion sur les changements de salle ou d'horaire.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats/CTA Section -->
    <section class="py-20 bg-gradient-to-br from-gray-900 to-gray-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h2 class="text-3xl md:text-5xl font-bold mb-8">Rejoignez la révolution numérique</h2>
            <p class="text-xl text-gray-300 mb-12 max-w-2xl mx-auto">
                Une plateforme conçue pour l'excellence académique à l'Université de Yaoundé I.
            </p>
            
            <a href="{{ route('timetables.index') }}" class="inline-flex items-center px-10 py-5 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full font-bold text-lg shadow-lg hover:shadow-purple-500/50 hover:scale-105 transition-all duration-300">
                Accéder maintenant <i class="fas fa-chevron-right ml-3"></i>
            </a>
        </div>
    </section>

    <!-- Login Modal -->
    <div id="login-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeLoginModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-gray-100 scale-95 opacity-0 animate-scale-in">
                    
                    <!-- Close Button -->
                    <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none z-10" onclick="closeLoginModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>

                    <!-- Modal Body -->
                    <div class="px-8 py-10">
                        <div class="text-center mb-8">
                            <div class="mx-auto h-16 w-16 bg-primary/10 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-user-circle text-3xl text-primary"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Connexion</h3>
                            <p class="text-gray-500 mt-2 text-sm">Accédez à votre espace personnel</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <!-- Role -->
                             <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select id="role" name="role" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Sélectionner votre rôle</option>
                                    <option value="admin">Administrateur</option>
                                    <option value="teacher">Enseignant</option>
                                    <option value="delegate">Délégué</option>
                                </select>
                            </div>

                            <!-- Email Address -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" :value="old('email')" required autofocus class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out" placeholder="Adresse Email">
                            </div>

                            <!-- Password -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="password" name="password" required autocomplete="current-password" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out" placeholder="Mot de passe">
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" name="remember">
                                    <span class="ms-2">Se souvenir de moi</span>
                                </label>
                                
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-primary hover:text-primary/80 font-medium" href="{{ route('password.request') }}">
                                        Mot de passe oublié ?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-primary to-accent hover:from-primary/90 hover:to-accent/90 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-700 transition duration-150 ease-in-out shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Se connecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Animation Styles -->
    <style>
        .animate-scale-in {
            animation: scaleIn 0.3s ease-out forwards;
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
        function openLoginModal() {
            document.getElementById('login-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Auto-focus email field
            setTimeout(() => {
                const emailInput = document.getElementById('email');
                if(emailInput) emailInput.focus();
            }, 100);
        }

        function closeLoginModal() {
            document.getElementById('login-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLoginModal();
            }
        });
        
        // Check for login errors to re-open modal
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openLoginModal();
            });
        @endif
    </script>
@endsection