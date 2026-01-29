<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <base target="_self">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Système de Gestion Académique - Département d'Informatique")</title>
    <meta name="description" content="Application web pour centraliser et automatiser la gestion des séances de cours et TD au département d'Informatique de l'Université de Yaoundé I">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#5D3FD3", // Deep Violet
                        secondary: "#1F2937", // Gray-800
                        accent: "#9333EA", // Purple-600
                        success: "#10B981", // Emerald-500
                        warning: "#F59E0B", // Amber-500
                        danger: "#EF4444", // Red-500
                        light: "#F3F4F6", // Gray-100
                        surface: "#FFFFFF",
                    },
                    fontFamily: {
                        'sans': ['Outfit', 'system-ui', 'sans-serif']
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(93, 63, 211, 0.5)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #F8FAFC;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-text {
            background: linear-gradient(135deg, #5D3FD3 0%, #9333EA 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="min-h-screen text-gray-800 flex flex-col" x-data="{ open: false, userDropdownOpen: false }">
    
    <!-- Navbar -->
    <header class="glass-nav fixed w-full top-0 z-50 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="relative w-10 h-10 overflow-hidden rounded-lg bg-gradient-to-br from-primary to-accent p-0.5 shadow-lg group-hover:shadow-glow transition-all duration-300">
                        <img src="{{ asset('images/Blason_uy1.png') }}" alt="Logo" class="w-full h-full object-cover bg-white rounded-md">
                    </div>
                    <div class="leading-tight">
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Département Info</h1>
                        <p class="text-xs text-primary font-medium tracking-wide">Université de Yaoundé I</p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="/" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Accueil</a>
                    <a href="{{ route('timetables.index') }}" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Emplois du temps</a>
                    
                    @auth
                        <!-- Admin Dropdown -->
                        @if(Auth::user()->role === 'admin')
                             <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-primary transition-colors focus:outline-none">
                                    <span>Administration</span>
                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                </button>
                                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50" style="display: none;">
                                    <div class="px-4 py-2 border-b border-gray-100 mb-2">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gestion</span>
                                    </div>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-tachometer-alt w-5 mr-2 text-gray-400"></i>Tableau de bord</a>
                                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-users w-5 mr-2 text-gray-400"></i>Utilisateurs</a>
                                    <a href="{{ route('admin.ues.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-book w-5 mr-2 text-gray-400"></i>Unités d'Enseignement</a>
                                    <a href="{{ route('admin.seances.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-calendar-alt w-5 mr-2 text-gray-400"></i>Séances</a>
                                    <div class="border-t border-gray-100 my-2"></div>
                                    <a href="{{ route('admin.desideratas.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary"><i class="fas fa-hand-paper w-5 mr-2 text-gray-400"></i>Désiratas</a>
                                </div>
                            </div>
                        @elseif(Auth::user()->role === 'teacher')
                             <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 bg-primary/10 text-primary rounded-lg text-sm font-bold hover:bg-primary hover:text-white transition-all">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>Espace Enseignant
                            </a>
                        @elseif(Auth::user()->role === 'delegate')
                             <a href="{{ route('delegate.dashboard') }}" class="px-4 py-2 bg-accent/10 text-accent rounded-lg text-sm font-bold hover:bg-accent hover:text-white transition-all">
                                <i class="fas fa-user-tie mr-2"></i>Espace Délégué
                            </a>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="relative ml-4">
                            <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="flex items-center gap-3 focus:outline-none">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary to-accent p-0.5">
                                    <div class="w-full h-full bg-white rounded-full flex items-center justify-center text-primary font-bold">
                                        {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="hidden lg:block text-left">
                                    <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->first_name }}</p>
                                    <p class="text-xs text-gray-500 mt-1 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 text-xs ml-1 transition-transform duration-200" :class="{'rotate-180': userDropdownOpen}"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 origin-top-right" style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-100 lg:hidden">
                                     <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                     <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                                    <i class="fas fa-user w-5 mr-2 text-gray-400"></i>Mon Profil
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5 mr-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <!-- Login Button -->
                        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary to-accent text-white font-bold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 transition-all duration-300 text-sm">
                            Connexion
                        </a>
                    @endauth
                </nav>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden text-gray-600 hover:text-primary focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="open" class="md:hidden fixed inset-0 bg-white z-[60] overflow-y-auto" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
            <div class="p-6">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">Menu</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <nav class="flex flex-col gap-4">
                    <a href="/" class="text-lg font-medium text-gray-800 py-2 border-b border-gray-100">Accueil</a>
                    <a href="{{ route('timetables.index') }}" class="text-lg font-medium text-gray-800 py-2 border-b border-gray-100">Emplois du temps</a>
                    @auth
                        <div class="py-2 border-b border-gray-100">
                            <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mon Compte</span>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-lg font-medium text-gray-800">Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button class="text-lg font-medium text-red-600">Déconnexion</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="mt-4 w-full py-4 rounded-xl bg-primary text-white text-center font-bold text-lg shadow-xl">Se connecter</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 relative z-0">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-secondary text-white pt-16 pb-8 border-t border-gray-800">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-12">
                <div class="md:col-span-5">
                    <a href="/" class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('images/Blason_uy1.png') }}" alt="Logo" class="h-12 w-auto brightness-0 invert opacity-80">
                        <div>
                            <h3 class="text-lg font-bold">Département d'Informatique</h3>
                            <p class="text-xs text-gray-400">Université de Yaoundé I</p>
                        </div>
                    </a>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        Plateforme numérique centralisée pour la gestion académique. Simplifiez la planification, le suivi et la communication au sein du département.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-primary transition-colors text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-primary transition-colors text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-primary transition-colors text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="md:col-span-3">
                    <h4 class="text-lg font-bold mb-6 text-white border-b-2 border-primary inline-block pb-1">Navigation</h4>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-primary"></i> Accueil</a></li>
                        <li><a href="{{ route('timetables.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-primary"></i> Emplois du temps</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-primary"></i> Annonces</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-primary"></i> Espace Membre</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4">
                     <h4 class="text-lg font-bold mb-6 text-white border-b-2 border-primary inline-block pb-1">Contact</h4>
                      <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-gray-400">
                            <i class="fas fa-map-marker-alt mt-1 text-primary"></i>
                            <span>Campus Ngoa-Ekélé, Yaoundé<br>B.P. 812 Yaoundé, Cameroun</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="fas fa-envelope text-primary"></i>
                            <span>contact@dept-info-uy1.cm</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <i class="fas fa-phone text-primary"></i>
                            <span>(+237) 222 23 45 67</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Département d'Informatique UY1. Tous droits réservés.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors">Confidentialité</a>
                    <a href="#" class="hover:text-white transition-colors">Mentions Légales</a>
                    <a href="#" class="hover:text-white transition-colors">Aide</a>
                </div>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>