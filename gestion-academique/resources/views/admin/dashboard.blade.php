@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-primary mb-6">Tableau de bord Administrateur</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Total Utilisateurs</p>
                        <p class="text-3xl font-bold text-primary">{{ $totalUsers }}</p>
                    </div>
                    <i class="fas fa-users text-4xl text-primary opacity-50"></i>
                </div>
            </div>

            <!-- Total Teachers Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Enseignants</p>
                        <p class="text-3xl font-bold text-accent">{{ $totalTeachers }}</p>
                    </div>
                    <i class="fas fa-chalkboard-teacher text-4xl text-accent opacity-50"></i>
                </div>
            </div>

            <!-- Total Delegates Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-success">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Délégués</p>
                        <p class="text-3xl font-bold text-success">{{ $totalDelegates }}</p>
                    </div>
                    <i class="fas fa-user-tie text-4xl text-success opacity-50"></i>
                </div>
            </div>

            <!-- Total Filieres Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Filières</p>
                        <p class="text-3xl font-bold text-purple-500">{{ $totalFilieres }}</p>
                    </div>
                    <i class="fas fa-graduation-cap text-4xl text-purple-500 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total UEs Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Unités d'Enseignement</p>
                        <p class="text-3xl font-bold text-blue-500">{{ $totalUes }}</p>
                    </div>
                    <i class="fas fa-book text-4xl text-blue-500 opacity-50"></i>
                </div>
            </div>

            <!-- Total Salles Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Salles</p>
                        <p class="text-3xl font-bold text-orange-500">{{ $totalSalles }}</p>
                    </div>
                    <i class="fas fa-building text-4xl text-orange-500 opacity-50"></i>
                </div>
            </div>

            <!-- Total Groupes Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-teal-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Groupes</p>
                        <p class="text-3xl font-bold text-teal-500">{{ $totalGroupes }}</p>
                    </div>
                    <i class="fas fa-users-class text-4xl text-teal-500 opacity-50"></i>
                </div>
            </div>

            <!-- Timetables Templates Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-indigo-500 hover:shadow-xl transition-shadow cursor-pointer">
                <a href="{{ route('seance-templates.index') }}" class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Emplois du Temps</p>
                        <p class="text-sm text-indigo-600 mt-2"><i class="fas fa-arrow-right mr-1"></i>Gérer les templates</p>
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
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sessions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-gray-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Total Séances</p>
                        <p class="text-3xl font-bold text-gray-500">{{ $totalSeances }}</p>
                    </div>
                    <i class="fas fa-calendar-alt text-4xl text-gray-500 opacity-50"></i>
                </div>
            </div>

            <!-- Completed Sessions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Séances Effectuées</p>
                        <p class="text-3xl font-bold text-green-500">{{ $completedSeances }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl text-green-500 opacity-50"></i>
                </div>
            </div>

            <!-- Pending Sessions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Séances Planifiées</p>
                        <p class="text-3xl font-bold text-yellow-500">{{ $pendingSeances }}</p>
                    </div>
                    <i class="fas fa-hourglass-half text-4xl text-yellow-500 opacity-50"></i>
                </div>
            </div>

            <!-- Cancelled Sessions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-medium text-gray-600">Séances Annulées</p>
                        <p class="text-3xl font-bold text-red-500">{{ $cancelledSeances }}</p>
                    </div>
                    <i class="fas fa-times-circle text-4xl text-red-500 opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Overall UE Progress Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-700">
                <h2 class="text-xl font-bold text-primary mb-4">Avancement Global des UE</h2>
                <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700">
                    <div class="bg-blue-700 h-4 rounded-full" style="width: {{ $overallUeProgress }}%"></div>
                </div>
                <p class="text-lg font-bold text-blue-700 mt-2">{{ $overallUeProgress }}%</p>
            </div>

            <!-- Recent Notifications Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-gray-700">
                <h2 class="text-xl font-bold text-primary mb-4">Notifications Récentes</h2>
                <ul class="space-y-3">
                    @forelse ($recentNotifications as $notification)
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-bell text-gray-500 mt-1"></i>
                            <div>
                                <p class="text-gray-800">{{ Str::limit($notification->contenu, 100) }}</p>
                                <p class="text-sm text-gray-500">
                                    De {{ $notification->expediteur->first_name ?? 'N/A' }} à {{ $notification->destinataireUser->first_name ?? 'N/A' }}
                                    - {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </li>
                    @empty
                        <li><p class="text-gray-500">Aucune notification récente.</p></li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection