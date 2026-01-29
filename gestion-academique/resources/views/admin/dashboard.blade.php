@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">
                    Tableau de bord Administrateur
                </h1>
                <p class="text-gray-500 mt-1">Vue d'ensemble de la gestion académique</p>
            </div>
            <div class="flex items-center gap-3">
                 <div class="bg-white p-2 rounded-xl shadow-soft border border-gray-100 flex items-center gap-2 text-sm font-medium text-gray-600">
                    <div class="w-2 h-2 rounded-full bg-success animate-pulse"></div>
                    Système actif
                 </div>
                 <button onclick="window.location.reload()" class="p-2 bg-white rounded-xl shadow-soft border border-gray-100 text-gray-500 hover:text-primary transition-colors">
                     <i class="fas fa-sync-alt"></i>
                 </button>
            </div>
        </div>

        <!-- ALERTS SECTION -->
        @if(count($completeClasses) > 0)
            <div class="mb-8 p-4 bg-orange-50 border border-orange-100 rounded-2xl flex items-start gap-4 shadow-sm animate-fade-in-down">
                <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Classes Complètes</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Les classes suivantes ont tous leurs cours programmés pour ce semestre :
                    </p>
                     <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($completeClasses as $complete)
                            <span class="px-2 py-1 bg-white border border-orange-200 text-orange-700 text-xs font-bold rounded-md">
                                {{ $complete['groupe']->nom }} - S{{ $complete['semestre'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Users Widget -->
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</span>
                        <span class="text-sm font-medium text-gray-500">Utilisateurs Totaux</span>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs font-medium">
                         <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg">{{ $totalTeachers }} Enseignants</span>
                         <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded-lg">{{ $totalDelegates }} Délégués</span>
                    </div>
                </div>
            </div>

            <!-- UEs Widget -->
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                        <i class="fas fa-book text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-gray-800">{{ $totalUes }}</span>
                        <span class="text-sm font-medium text-gray-500">Unités d'Enseignement</span>
                    </div>
                    <div class="mt-4 text-xs font-medium text-purple-600">
                        {{ $totalFilieres }} Filières actives
                    </div>
                </div>
            </div>

            <!-- Seances Widget -->
             <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                        <i class="fas fa-calendar-alt text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-gray-800">{{ $totalSeances }}</span>
                        <span class="text-sm font-medium text-gray-500">Séances au Total</span>
                    </div>
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-400 to-red-500 h-1.5 rounded-full" style="width: {{ $totalSeances > 0 ? ($completedSeances / $totalSeances) * 100 : 0 }}%"></div>
                    </div>
                    <div class="mt-2 text-xs text-gray-400 flex justify-between">
                        <span>{{ $completedSeances }} terminées</span>
                        <span>{{ $pendingSeances }} à venir</span>
                    </div>
                </div>
            </div>

             <!-- Rooms Widget -->
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                        <i class="fas fa-building text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-3xl font-bold text-gray-800">{{ $totalSalles }}</span>
                        <span class="text-sm font-medium text-gray-500">Salles de classe</span>
                    </div>
                    <div class="mt-4 text-xs font-medium text-teal-600">
                        {{ $totalGroupes }} Groupes d'étudiants
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-gradient-to-br from-primary to-accent rounded-2xl shadow-lg p-8 text-white mb-12 relative overflow-hidden">
             <div class="absolute top-0 left-0 w-full h-full bg-white/5 opacity-50 pattern-bg"></div> <!-- Optional pattern -->
             <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                 <div class="flex-1">
                     <h2 class="text-2xl font-bold mb-2">Avancement Global des UE</h2>
                     <p class="text-blue-100 opacity-90 text-sm">Progression moyenne sur l'ensemble des cours dispensés ce semestre.</p>
                 </div>
                 <div class="flex items-center gap-4 w-full md:w-1/2">
                    <div class="flex-grow bg-black/20 rounded-full h-4 overflow-hidden backdrop-blur-sm">
                        <div class="bg-white h-full rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)] transition-all duration-1000 ease-out" style="width: {{ $overallUeProgress }}%"></div>
                    </div>
                    <span class="text-2xl font-bold">{{ $overallUeProgress }}%</span>
                 </div>
             </div>
        </div>

        <!-- Quick Actions Grid -->
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-rocket text-primary"></i> Gestion Rapide
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Card Component Helper -->
            @php
                $actions = [
                    [
                        'title' => 'Emplois du Temps',
                        'desc' => 'Gérer les plannings hebdomadaires',
                        'icon' => 'calendar-check',
                        'color' => 'indigo',
                        'route' => route('seance-templates.index')
                    ],
                    [
                        'title' => 'Rapports de Séance',
                        'desc' => 'Consulter et valider les rapports',
                        'icon' => 'file-alt',
                        'color' => 'green',
                        'route' => route('admin.reports.index')
                    ],
                    [
                        'title' => 'Demandes de Modif.',
                        'desc' => 'Gérer les changements de cours',
                        'icon' => 'edit',
                        'color' => 'orange',
                        'route' => route('admin.demandes-modifications.index')
                    ],
                    [
                        'title' => 'Utilisateurs',
                        'desc' => 'Comptes enseignants, délégués...',
                        'icon' => 'user-cog',
                        'color' => 'blue',
                        'route' => route('admin.users.index')
                    ],
                    [
                        'title' => 'Séances',
                        'desc' => 'Vue globale de toutes les séances',
                        'icon' => 'clock',
                        'color' => 'purple',
                        'route' => route('admin.seances.index')
                    ],
                    [
                        'title' => 'Effectifs',
                        'desc' => 'Suivi des présences et nombres',
                        'icon' => 'chart-bar',
                        'color' => 'cyan',
                        'route' => route('admin.groupe-effectifs.index')
                    ],
                     [
                        'title' => 'Salles & Lieux',
                        'desc' => 'Disponibilité des salles',
                        'icon' => 'door-open',
                        'color' => 'pink',
                        'route' => route('admin.salles.index')
                    ],
                ];
            @endphp

            @foreach($actions as $action)
                <a href="{{ $action['route'] }}" class="group bg-white rounded-2xl p-6 shadow-soft border border-gray-100 hover:shadow-glow hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-{{ $action['color'] }}-50 text-{{ $action['color'] }}-600 flex items-center justify-center text-xl group-hover:bg-{{ $action['color'] }}-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-{{ $action['icon'] }}"></i>
                        </div>
                        <i class="fas fa-arrow-right text-gray-300 group-hover:text-{{ $action['color'] }}-500 transition-colors"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $action['title'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $action['desc'] }}</p>
                </a>
            @endforeach

        </div>
    </div>
@endsection