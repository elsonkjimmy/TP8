<!DOCTYPE html>
<html lang="fr">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Système de Gestion Académique - Département d'Informatique | Université de Yaoundé I")</title>
    <meta name="description" content="Application web pour centraliser et automatiser la gestion des séances de cours et TD au département d'Informatique de l'Université de Yaoundé I">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#6B4C7A",
                        secondary: "#2E2E2E",
                        success: "#28A745",
                        accent: "#8A6BA8",
                        light: "#F8F9FA"
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    },
                    spacing: {
                        '128': '32rem',
                        '144': '36rem'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .mobile-menu.active {
            transform: translateX(0);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .calendar-day {
            transition: all 0.2s ease;
        }
        .calendar-day:hover {
            transform: scale(1.05);
        }
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-secondary" x-data="{ open: false }">
    <!-- Header -->
    <header class="bg-primary text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo et titre -->
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg">
                        <i class="fas fa-university text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Département d'Informatique</h1>
                        <p class="text-sm text-gray-200">Université de Yaoundé I</p>
                    </div>
                </div>
                
                <!-- Navigation principale -->
                <nav class="hidden md:flex space-x-6">
                    <a href="/" class="hover:text-accent transition-colors">Accueil</a>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                                        <div>Administration</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.dashboard')">
                                        Admin Dashboard
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.users.index')">
                                        Gestion Utilisateurs
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.filieres.index')">
                                        Gestion Filières
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.ues.index')">
                                        Gestion UE
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.salles.index')">
                                        Gestion Salles
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.groupes.index')">
                                        Gestion Groupes
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.users.import.form')">
                                        Importer Utilisateurs
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.seances.index')">
                                        Gestion Séances
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.notifications.index')">
                                        Gestion Notifications
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        @elseif(Auth::user()->role === 'teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="hover:text-accent transition-colors">Teacher Dashboard</a>
                        @elseif(Auth::user()->role === 'delegate')
                            <a href="{{ route('delegate.dashboard') }}" class="hover:text-accent transition-colors">Delegate Dashboard</a>
                        @endif                      
                    @endauth
                    <a href="{{ route('timetables.index') }}" class="hover:text-accent transition-colors">Emplois du temps</a>
                    <a href="#" class="hover:text-accent transition-colors">Annonces</a>
                    <a href="#" class="hover:text-accent transition-colors">À propos</a>
                </nav>
                
                <!-- Boutons d'authentification -->
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="hidden md:inline-block bg-white text-primary px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                        </a>
                    @else
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endguest
                    <button class="md:hidden text-white" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu fixed inset-y-0 left-0 w-64 bg-white shadow-xl z-50 md:hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-primary">Menu</h2>
                <button onclick="toggleMobileMenu()" class="text-secondary">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <nav class="space-y-4">
                <a href="/" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                    <i class="fas fa-home mr-3"></i>Accueil
                </a>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <h3 class="text-lg font-bold text-primary mt-4 mb-2">Administration</h3>
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-tachometer-alt mr-3"></i>Admin Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-users-cog mr-3"></i>Gestion Utilisateurs
                        </a>
                        <a href="{{ route('admin.filieres.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-folder-open mr-3"></i>Gestion Filières
                        </a>
                        <a href="{{ route('admin.ues.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-book-open mr-3"></i>Gestion UE
                        </a>
                        <a href="{{ route('admin.salles.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-building mr-3"></i>Gestion Salles
                        </a>
                        <a href="{{ route('admin.groupes.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-users mr-3"></i>Gestion Groupes
                        </a>
                        <a href="{{ route('admin.users.import.form') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-file-excel mr-3"></i>Importer Utilisateurs
                        </a>
                        <a href="{{ route('admin.seances.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-calendar-check mr-3"></i>Gestion Séances
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-bell mr-3"></i>Gestion Notifications
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-bell mr-3"></i>Gestion Notifications
                        </a>
                    @elseif(Auth::user()->role === 'teacher')
                        <a href="{{ route('teacher.dashboard') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-chalkboard-teacher mr-3"></i>Teacher Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'delegate')
                        <a href="{{ route('delegate.dashboard') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-user-tie mr-3"></i>Delegate Dashboard
                        </a>
                    @endif
                    <hr class="my-4">
                    <a href="{{ route('profile.edit') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                        <i class="fas fa-user-circle mr-3"></i>Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>Déconnexion
                        </button>
                    </form>
                @endauth
                <a href="{{ route('timetables.index') }}" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                    <i class="fas fa-calendar-alt mr-3"></i>Emplois du temps
                </a>
                <a href="#" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                    <i class="fas fa-bullhorn mr-3"></i>Annonces
                </a>
                <a href="#" class="block py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors">
                    <i class="fas fa-info-circle mr-3"></i>À propos
                </a>
                <hr class="my-4">
                @guest
                    <a href="{{ route('login') }}" class="w-full bg-primary text-white px-4 py-3 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                @endguest
            </nav>
        </div>
    </div>

    <!-- Overlay pour menu mobile -->
    <div class="mobile-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="toggleMobileMenu()"></div>

    <!-- Page Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Département d'Informatique</h3>
                    <p class="text-gray-200">
                        Université de Yaoundé I<br>
                        Système de Gestion Académique
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="hover:text-accent transition-colors">Accueil</a></li>
                        <li><a href="{{ route('timetables.index') }}" class="hover:text-accent transition-colors">Emplois du temps</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Annonces</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">À propos</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Ressources</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-accent transition-colors">Guide d'utilisation</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Politique de confidentialité</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">Conditions d'utilisation</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>info@dept-info-uy1.cm</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3"></i>
                            <span>+237 222 22 22 22</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            <span>Université de Yaoundé I</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; 2025 Département d'Informatique - Université de Yaoundé I. Tous droits réservés.</p>
                <p class="mt-2">Système de Gestion Académique - Version 1.0</p>
            </div>
        </div>
    </footer>

    <!-- Modal de connexion -->
    <div id="login-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-primary">Connexion</h3>
                    <button onclick="hideLoginModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <form id="login-form">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="role">Rôle</label>
                        <select id="role" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Sélectionner un rôle</option>
                            <option value="admin">Administrateur</option>
                            <option value="teacher">Enseignant</option>
                            <option value="delegate">Délégué</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="username">Identifiant</label>
                        <input type="text" id="username" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Votre identifiant">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2" for="password">Mot de passe</label>
                        <input type="password" id="password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Votre mot de passe">
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Les étudiants n'ont pas besoin de s'authentifier pour consulter les emplois du temps et annonces.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // This script will be moved to a separate file later
        let mobileMenuActive = false;

        function toggleMobileMenu() {
            const mobileMenu = document.querySelector(".mobile-menu");
            const overlay = document.querySelector(".mobile-overlay");
            
            mobileMenuActive = !mobileMenuActive;
            
            if (mobileMenuActive) {
                mobileMenu.classList.add("active");
                overlay.classList.remove("hidden");
                document.body.style.overflow = "hidden";
            } else {
                mobileMenu.classList.remove("active");
                overlay.classList.add("hidden");
                document.body.style.overflow = "auto";
            }
        }

        function showLoginModal() {
            document.getElementById("login-modal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        function hideLoginModal() {
            document.getElementById("login-modal").classList.add("hidden");
            document.body.style.overflow = "auto";
        }

        window.onclick = function(event) {
            const modal = document.getElementById("login-modal");
            if (event.target === modal) {
                hideLoginModal();
            }
        };
    </script>
    @stack('scripts')
</body>
</html>