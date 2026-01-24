@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-primary mb-6">Tableau de bord Administrateur</h1>

        <!-- ALERTS SECTION -->
        @if(count($completeClasses) > 0)
            <div class="mb-8 p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                <div class="flex items-start gap-4">
                    <div class="text-3xl">⚠️</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-yellow-900 mb-2">Classes Complètes</h3>
                        <p class="text-sm text-yellow-800 mb-3">
                            Les classes suivantes ont tous leurs cours programmés pour ce semestre :
                        </p>
                        <div class="space-y-1">
                            @foreach($completeClasses as $complete)
                                <div class="text-sm text-yellow-900 font-medium">
                                    • {{ $complete['groupe']->nom }} - {{ $complete['semestre'] }} ({{ $complete['annee'] }}/{{ $complete['annee'] + 1 }})
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Table (Collapsible) -->
        <div class="bg-white rounded-xl shadow-lg mb-8" x-data="{ open: false }">
            <div class="p-6 cursor-pointer hover:bg-gray-50 transition" @click="open = !open">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-primary">Résumé des Statistiques</h2>
                    <i class="fas fa-chevron-down transition-transform" :class="{ 'rotate-180': open }"></i>
                </div>
            </div>

            <div x-show="open" @click.outside="open = false" class="border-t border-gray-200 p-6 overflow-auto">
                <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Catégorie</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Nombre</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Users Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-users text-primary mr-2"></i>Utilisateurs
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-primary text-lg">{{ $totalUsers }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Actifs</span>
                        </td>
                    </tr>

                    <!-- Teachers Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-chalkboard-teacher text-accent mr-2"></i>Enseignants
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-accent text-lg">{{ $totalTeachers }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Enseignement</span>
                        </td>
                    </tr>

                    <!-- Delegates Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-user-tie text-success mr-2"></i>Délégués
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-success text-lg">{{ $totalDelegates }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Actifs</span>
                        </td>
                    </tr>

                    <!-- Filieres Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-graduation-cap text-purple-500 mr-2"></i>Filières
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-purple-500 text-lg">{{ $totalFilieres }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Programmes</span>
                        </td>
                    </tr>

                    <!-- UEs Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-book text-blue-500 mr-2"></i>Unités d'Enseignement
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-blue-500 text-lg">{{ $totalUes }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Total</span>
                        </td>
                    </tr>

                    <!-- Salles Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-building text-orange-500 mr-2"></i>Salles
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-orange-500 text-lg">{{ $totalSalles }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Disponibles</span>
                        </td>
                    </tr>

                    <!-- Groupes Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-users-class text-teal-500 mr-2"></i>Groupes
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-teal-500 text-lg">{{ $totalGroupes }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Actifs</span>
                        </td>
                    </tr>

                    <!-- Sessions Summary -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50 bg-gray-50 font-semibold">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-calendar-alt text-gray-600 mr-2"></i>Total Séances
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-gray-700 text-lg">{{ $totalSeances }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="text-sm text-gray-500">Sessions</span>
                        </td>
                    </tr>

                    <!-- Completed Sessions Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>Séances Effectuées
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-green-500 text-lg">{{ $completedSeances }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Complétées</span>
                        </td>
                    </tr>

                    <!-- Pending Sessions Row -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-hourglass-half text-yellow-500 mr-2"></i>Séances Planifiées
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-yellow-500 text-lg">{{ $pendingSeances }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold">En attente</span>
                        </td>
                    </tr>

                    <!-- Cancelled Sessions Row -->
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>Séances Annulées
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="font-bold text-red-500 text-lg">{{ $cancelledSeances }}</span>
                        </td>
                        <td class="text-center py-3 px-4">
                            <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold">Annulées</span>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Timetables Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-indigo-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('seance-templates.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Emplois du Temps</p>
                        <p class="text-sm text-indigo-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les Emplois du Temps</p>
                    </div>
                    <i class="fas fa-calendar-check text-4xl text-indigo-500 opacity-50"></i>
                </a>
            </div>

            <!-- Reports Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('admin.reports.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Rapports de Séance</p>
                        <p class="text-sm text-green-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Consulter les rapports</p>
                    </div>
                    <i class="fas fa-file-alt text-4xl text-green-500 opacity-50"></i>
                </a>
            </div>

            <!-- Demandes de Modification Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-orange-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('admin.demandes-modifications.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Demandes de Modification</p>
                        <p class="text-sm text-orange-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les demandes</p>
                    </div>
                    <i class="fas fa-edit text-4xl text-orange-500 opacity-50"></i>
                </a>
            </div>

            <!-- Gestion des Utilisateurs Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Gestion des Utilisateurs</p>
                        <p class="text-sm text-blue-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les utilisateurs</p>
                    </div>
                    <i class="fas fa-user-cog text-4xl text-blue-500 opacity-50"></i>
                </a>
            </div>

            <!-- Gestion des Séances Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('admin.seances.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Gestion des Séances</p>
                        <p class="text-sm text-purple-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les séances</p>
                    </div>
                    <i class="fas fa-clock text-4xl text-purple-500 opacity-50"></i>
                </a>
            </div>

            <!-- Gestion des Effectifs Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-cyan-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('admin.groupe-effectifs.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Gestion des Effectifs</p>
                        <p class="text-sm text-cyan-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les effectifs</p>
                    </div>
                    <i class="fas fa-chart-bar text-4xl text-cyan-500 opacity-50"></i>
                </a>
            </div>

            <!-- Gestion des Salles Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-pink-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('admin.salles.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Gestion des Salles</p>
                        <p class="text-sm text-pink-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les salles</p>
                    </div>
                    <i class="fas fa-door-open text-4xl text-pink-500 opacity-50"></i>
                </a>
            </div>
        </div>

        <!-- Overall UE Progress Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-700 mt-6">
            <h2 class="text-xl font-bold text-primary mb-4">Avancement Global des UE</h2>
            <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700">
                <div class="bg-blue-700 h-4 rounded-full" style="width: {{ $overallUeProgress }}%"></div>
            </div>
            <p class="text-lg font-bold text-blue-700 mt-2">{{ $overallUeProgress }}%</p>
        </div>
    </div>
@endsection