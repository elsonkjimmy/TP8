@extends('layouts.app')

@section('title', 'Tableau de bord Délégué')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">
                    Espace Délégué
                </h1>
               @if ($groupe)
                   <p class="text-gray-500 mt-1 flex items-center gap-2">
                       <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg text-sm font-bold border border-blue-100">{{ $groupe->nom }}</span>
                       <span class="text-xs">•</span>
                       <span class="text-sm">Filière {{ $groupe->filiere->nom ?? 'N/A' }}</span>
                   </p>
               @endif
            </div>
            <div class="flex items-center gap-3">
                 <div class="bg-white p-2 rounded-xl shadow-soft border border-gray-100 flex items-center gap-2 text-sm font-medium text-gray-600">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-tie text-xs"></i>
                    </div>
                    <div class="flex flex-col leading-tight mr-2">
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Rôle</span>
                        <span class="text-xs font-bold text-gray-800">Délégué</span>
                    </div>
                 </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if ($groupe)
             <!-- Stats Overview -->
             <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- My Group Info -->
                <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                     <div class="relative z-10">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-users text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Mon Groupe</h3>
                        <p class="text-sm text-gray-500 mb-4">Informations générales</p>
                        
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Responsable</span>
                                <span class="font-bold text-gray-800 bg-gray-50 px-2 py-0.5 rounded">{{ $groupe->filiere->enseignantResponsable->last_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Niveau</span>
                                <span class="font-bold text-gray-800">L{{ substr($groupe->nom, 1, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Stats -->
                <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                     <div class="relative z-10">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-file-signature text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Rapports à Valider</h3>
                        <div class="flex items-end gap-2 mt-2">
                            <span class="text-4xl font-bold text-gray-800">{{ $reportsToValidate->count() }}</span>
                            <span class="text-sm text-gray-500 mb-1.5">en attente</span>
                        </div>
                         @if($reportsToValidate->count() > 0)
                            <div class="mt-3 text-xs font-bold text-orange-600 bg-orange-50 inline-block px-2 py-1 rounded-lg animate-pulse">
                                Requires Action
                            </div>
                         @else
                            <div class="mt-3 text-xs font-bold text-green-600 bg-green-50 inline-block px-2 py-1 rounded-lg">
                                Tout est à jour
                            </div>
                         @endif
                    </div>
                </div>

                 <!-- Drafts Stats -->
                <div class="bg-white rounded-2xl p-6 shadow-soft border border-gray-100 relative overflow-hidden group hover:shadow-glow transition-all duration-300">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gray-100 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                     <div class="relative z-10">
                        <div class="w-12 h-12 bg-gray-200 text-gray-600 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-pencil-alt text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Mes Brouillons</h3>
                         <div class="flex items-end gap-2 mt-2">
                            <span class="text-4xl font-bold text-gray-800">{{ $draftReports->count() }}</span>
                            <span class="text-sm text-gray-500 mb-1.5">sauvegardés</span>
                        </div>
                         <a href="#" class="mt-3 text-xs font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1 transition-colors">
                            Voir les brouillons <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Upcoming Sessions -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-calendar-day text-primary"></i> Séances du Groupe
                            </h2>
                             <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cette semaine</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">UE / Info</th>
                                        <th class="px-6 py-4 bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Horaire</th>
                                        <th class="px-6 py-4 bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($seances as $seance)
                                        <tr class="hover:bg-gray-50/80 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                                        {{ substr($seance->ue->code ?? 'UE', 0, 3) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-900">{{ $seance->ue->nom ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $seance->salle->numero ?? 'Salle N/A' }} • 
                                                            <span class="text-primary">{{ $seance->enseignant->last_name ?? 'Enseignant N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($seance->jour)->format('d/m') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                @php
                                                    $canReport = false;
                                                    $now = \Carbon\Carbon::now();
                                                    try {
                                                        $seanceEnd = \Carbon\Carbon::parse($seance->heure_fin);
                                                        // 30 mins before end logic
                                                        if ($now->greaterThanOrEqualTo($seanceEnd->copy()->subMinutes(30)) && $now->toDateString() === \Carbon\Carbon::parse($seance->jour)->toDateString()) {
                                                            $canReport = true;
                                                        }
                                                    } catch (\Throwable $e) { $canReport = false; }
        
                                                    $report = $seance->rapportSeance ?? null;
                                                @endphp
        
                                                @if(!$report && $canReport)
                                                    <a href="{{ route('delegate.seances.reports.create', $seance->id) }}" class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-lg shadow-sm hover:shadow-md hover:bg-primary/90 transition-all transform hover:-translate-y-0.5">
                                                        <i class="fas fa-plus mr-1"></i> Rapport
                                                    </a>
                                                @elseif($report)
                                                    <a href="{{ route('delegate.reports.show', $report->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200 transition-colors">
                                                        <i class="fas fa-eye mr-1"></i> Voir
                                                    </a>
                                                @else
                                                     <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">Pas encore</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                                Aucune séance prévue cette semaine.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Validation & Drafts -->
                <div class="space-y-8">
                     <!-- Reports to Validate -->
                    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-orange-50/50 flex justify-between items-center">
                            <h2 class="font-bold text-orange-900 flex items-center gap-2">
                                <i class="fas fa-check-double text-orange-500"></i> À Valider
                            </h2>
                        </div>
                         <div class="divide-y divide-gray-100">
                             @forelse ($reportsToValidate as $report)
                                <div class="p-4 hover:bg-orange-50/30 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-900">{{ $report->seance->ue->nom ?? 'UE Inconnue' }}</h4>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($report->seance->jour)->format('d/m/Y') }} • Par {{ $report->seance->enseignant->last_name }}</p>
                                        </div>
                                        <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-bold rounded uppercase">En attente</span>
                                    </div>
                                    <div class="flex gap-2 mt-3">
                                         <a href="{{ route('delegate.reports.show', $report->id) }}" class="flex-1 text-center px-3 py-1.5 bg-white border border-gray-200 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-50 transition-colors">
                                            Vérifier
                                        </a>
                                         <a href="{{ route('delegate.reports.edit', $report->id) }}" class="px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-bold rounded-lg hover:bg-orange-200 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                             @empty
                                <div class="p-8 text-center text-gray-400 text-sm">
                                    <i class="fas fa-clipboard-check text-4xl mb-2 text-gray-200"></i>
                                    <p>Aucun rapport en attente.</p>
                                </div>
                             @endforelse
                         </div>
                    </div>

                    <!-- Drafts -->
                    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                         <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-save text-gray-400"></i> Brouillons
                            </h2>
                        </div>
                         <div class="divide-y divide-gray-100">
                            @forelse($draftReports as $r)
                                <a href="{{ route('delegate.reports.show', $r->id) }}" class="block p-4 hover:bg-gray-50 transition-colors group">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-sm text-gray-800 group-hover:text-primary transition-colors">{{ $r->seance->ue->nom ?? 'Séance' }}</h4>
                                            <p class="text-xs text-gray-500">Modifié le {{ $r->updated_at->format('d/m H:i') }}</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-300 group-hover:text-primary transition-colors text-xs"></i>
                                    </div>
                                </a>
                            @empty
                                 <div class="p-6 text-center text-gray-400 text-xs">
                                    Pas de brouillons.
                                </div>
                            @endforelse
                         </div>
                    </div>

                </div>
            </div>
            
        @else
            <!-- No Group Assigned State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center max-w-2xl mx-auto border border-gray-100">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-user-slash text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Compte non assigné</h2>
                <p class="text-gray-600 mb-6">Votre compte Délégué n'est actuellement rattaché à aucun groupe académique. Veuillez contacter l'administration pour régulariser votre situation.</p>
                <a href="#" class="inline-flex items-center px-6 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-gray-900 transition-colors">
                    Contacter le support
                </a>
            </div>
        @endif
    </div>
@endsection