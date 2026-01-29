<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('images/Blason_uy1.png') }}" alt="Logo" class="block h-9 w-auto transition-transform group-hover:scale-110">
                        <span class="font-bold text-xl tracking-tight text-gray-800 group-hover:text-primary transition-colors hidden md:block">
                            Gestion<span class="text-primary">Académique</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                     <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Accueil') }}
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Tableau de Bord') }}
                    </x-nav-link>

                    <x-nav-link :href="route('timetables.index')" :active="request()->routeIs('timetables.*')">
                        {{ __('Emplois du Temps') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-primary focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-primary to-accent text-white flex items-center justify-center text-xs">
                                     {{ substr(Auth::user()->first_name, 0, 1) }}
                                </div>
                                {{ Auth::user()->first_name }}
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <p class="text-xs text-gray-500">Connecté en tant que</p>
                            <p class="font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase rounded">{{ Auth::user()->role }}</span>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-primary/5 hover:text-primary transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-circle w-4"></i> {{ __('Mon Profil') }}
                            </div>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt w-4"></i> {{ __('Déconnexion') }}
                                </div>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
             <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Accueil') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Tableau de Bord') }}
            </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('timetables.index')" :active="request()->routeIs('timetables.*')">
                {{ __('Emplois du Temps') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50/50">
            <div class="px-4 mb-3">
                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <i class="fas fa-user-circle mr-2 opacity-50"></i> {{ __('Mon Profil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-red-600"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2 opacity-50"></i> {{ __('Déconnexion') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
