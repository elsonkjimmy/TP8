@extends('layouts.app')

@section('title', 'Tableau de bord Enseignant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Bonjour, {{ Auth::user()->first_name }} 👋</h1>
            <p class="text-gray-500 mt-1">Gérez vos cours et vos demandes depuis cet espace.</p>
        </div>
        <div class="flex gap-3">
            <span class="px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-semibold text-gray-600 shadow-sm flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-success animate-pulse"></div>
                En ligne
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 flex items-center gap-3 shadow-sm animate-fade-in-down" role="alert">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <strong class="font-bold block">Succès!</strong>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Quick Stats / Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Modification Requests Card -->
        <div class="group relative overflow-hidden bg-white rounded-2xl shadow-soft hover:shadow-glow transition-all duration-300 border border-gray-100 p-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-500"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 text-xl shadow-sm">
                    <i class="fas fa-edit"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">Modifications</h2>
                <p class="text-sm text-gray-500 mb-4">Demandes de changement de salle ou d'horaire</p>
                <div class="flex gap-2">
                    <a href="{{ route('teacher.demandes.index') }}" class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-semibold hover:bg-purple-100 transition-colors">Gérer</a>
                    <a href="{{ route('teacher.demandes.create') }}" class="px-4 py-2 border border-purple-200 text-purple-700 rounded-lg text-sm font-semibold hover:bg-white hover:border-purple-300 transition-colors">+ Nouveau</a>
                </div>
            </div>
        </div>

        <!-- Desideratas Card -->
        <div class="group relative overflow-hidden bg-white rounded-2xl shadow-soft hover:shadow-glow transition-all duration-300 border border-gray-100 p-6">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-500"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 text-xl shadow-sm">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800 mb-1">Désiratas</h2>
                <p class="text-sm text-gray-500 mb-4">Positionnez-vous sur les créneaux libres</p>
                <div class="flex gap-2">
                    <a href="{{ route('teacher.desideratas.index') }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors">Mes vœux</a>
                    <a href="{{ route('timetables.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-blue-200 shadow-lg block">+ Choisir</a>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
         <div class="bg-gradient-to-br from-primary to-accent rounded-2xl shadow-lg shadow-purple-200 text-white p-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <h2 class="text-lg font-bold opacity-90">Mes Unités d'Enseignement</h2>
                    <p class="text-sm opacity-75">Assignées ce semestre</p>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <span class="text-4xl font-extrabold">{{ count($ues) }}</span>
                    <a href="#my-ues" class="text-sm font-medium hover:underline opacity-80 mb-1">Voir les détails <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Timetable Section -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clock text-primary"></i> Mon Emploi du Temps Hebdomadaire
            </h3>
            <span class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">Semaine en cours</span>
        </div>
        <div class="overflow-x-auto">
            <div class="min-w-[1000px] p-6">
                <table class="w-full border-separate border-spacing-2">
                    <thead>
                        <tr>
                            <th class="w-24 p-3 text-left text-xs font-bold text-gray-500 uppercase bg-gray-50 rounded-lg">Heure</th>
                            @foreach (array_keys($timetableGrid) as $dayLabel)
                                @php
                                    $dayIndex = array_search($dayLabel, array_keys($timetableGrid));
                                    $dayDate = $monday->copy()->addDays($dayIndex)->toDateString();
                                    $isToday = $dayDate === \Carbon\Carbon::now()->toDateString();
                                @endphp
                                <th class="p-3 text-center text-sm font-bold {{ $isToday ? 'bg-primary text-white shadow-md transform -translate-y-1' : 'bg-gray-50 text-gray-600' }} rounded-xl transition-all duration-300">
                                    {{ $dayLabel }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timeSlots as $slotLabel => $slot)
                            <tr>
                                <td class="p-3 bg-gray-50 rounded-lg text-xs font-bold text-gray-500 align-middle text-center border border-gray-100">
                                    {{ $slotLabel }}
                                </td>
                                @foreach ($timetableGrid as $day => $slots)
                                    <td class="p-2 align-top h-32 bg-gray-50/50 rounded-xl border border-dashed border-gray-200 hover:bg-gray-50 transition-colors">
                                        @forelse ($slots[$slotLabel] as $seance)
                                            <div class="h-full p-3 rounded-xl bg-white border-l-4 {{ $seance->status == 'completed' ? 'border-green-500' : ($seance->status == 'cancelled' ? 'border-red-500' : 'border-primary') }} shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 group">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $seance->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                        {{ $seance->salle->numero ?? 'N/A' }}
                                                    </span>
                                                    
                                                     {{-- Actions Menu (Hover) --}}
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                                        @php
                                                            $canReport = false;
                                                            $now = \Carbon\Carbon::now();
                                                            $seanceEnd = \Carbon\Carbon::parse($seance->heure_fin);
                                                            if ($now->greaterThanOrEqualTo($seanceEnd->copy()->subMinutes(30)) && $now->toDateString() === \Carbon\Carbon::parse($seance->jour)->toDateString()) {
                                                                $canReport = true;
                                                            }
                                                        @endphp
                                                        @if(!$seance->rapportSeance && $canReport)
                                                            <a href="{{ route('teacher.seances.reports.create', $seance->id) }}" class="w-6 h-6 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100" title="Remplir le rapport"><i class="fas fa-pen text-xs"></i></a>
                                                        @elseif($seance->rapportSeance)
                                                            <a href="{{ route('teacher.reports.show', $seance->rapportSeance->id) }}" class="w-6 h-6 flex items-center justify-center rounded-full bg-green-50 text-green-600 hover:bg-green-100" title="Voir le rapport"><i class="fas fa-eye text-xs"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <p class="font-bold text-sm text-gray-800 leading-tight mb-1">{{ $seance->ue->nom ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $seance->groupe->nom ?? 'N/A' }}</p>
                                                
                                                @if($seance->status == 'cancelled')
                                                    <span class="block mt-2 text-[10px] font-bold text-red-500 uppercase tracking-wide">Annulé</span>
                                                @endif
                                            </div>
                                        @empty
                                            
                                        @endforelse
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Secondary Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Mes UE -->
        <div id="my-ues" class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-book-reader text-accent"></i> Mes Unitées d'Enseignement
            </h3>
            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($ues as $ue)
                    <div class="flex items-center p-3 rounded-xl bg-gray-50 border border-gray-100 hover:bg-white hover:border-primary/30 hover:shadow-sm transition-all">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-4 shrink-0">
                            {{ substr($ue->code, 0, 3) }}
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $ue->nom }}</h4>
                            <p class="text-xs text-gray-500">{{ $ue->code }} • {{ $ue->filiere->nom ?? 'Sans filière' }}</p>
                        </div>
                        <div class="text-right">
                             <div class="text-xs font-bold text-primary">{{ $ue->progress ?? rand(10, 90) }}%</div>
                             <div class="w-16 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                 <div class="h-full bg-primary rounded-full" style="width: {{ $ue->progress ?? rand(10, 90) }}%"></div>
                             </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 italic">
                        Aucune UE assignée pour le moment.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bell text-warning"></i> Notifications Récentes
            </h3>
            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($notifications as $note)
                    <div class="flex gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-2 h-2 mt-2 rounded-full bg-warning shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $note->contenu }}</p>
                            <span class="text-xs text-gray-400 mt-1 block">{{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 italic">
                        Tout est calme ici.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
    
    <!-- Management Section -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Delegate Management -->
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                 <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users-cog text-gray-600"></i> Gestion des Délégués
                </h3>
            </div>
           
            @php
                $dayNames = [1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi',6=>'Samedi'];
                $grouped = $templates->groupBy('day_of_week');
            @endphp

            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($dayNames as $num => $label)
                    @php $group = $grouped->get($num, collect()); @endphp
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <button type="button" class="w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 transition flex items-center justify-between group" onclick="this.nextElementSibling.classList.toggle('hidden')">
                            <span class="font-semibold text-sm text-gray-700 group-hover:text-primary transition-colors">{{ $label }} <span class="bg-white px-2 py-0.5 rounded-md border border-gray-200 text-xs ml-2 text-gray-500">{{ $group->count() }}</span></span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform transform"></i>
                        </button>
                        <div class="p-4 hidden bg-white">
                            @if($group->isEmpty())
                                <p class="text-gray-400 text-xs italic">Aucun créneau.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($group as $template)
                                        <div class="flex flex-col gap-3 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                                            <div>
                                                <div class="font-bold text-sm text-gray-800">{{ $template->ue->nom ?? 'UE' }}</div>
                                                <div class="text-xs text-gray-500 flex items-center gap-2">
                                                    <span>{{ $template->groupe->nom ?? '' }}</span>
                                                    <span>•</span>
                                                    <span>{{ substr($template->start_time, 0, 5) }} - {{ substr($template->end_time, 0, 5) }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Delegate List -->
                                            <div class="bg-gray-50 rounded-lg p-2 text-xs">
                                                <span class="font-semibold text-gray-600 block mb-1">Délégués actuels:</span>
                                                @if($template->delegates->isEmpty())
                                                    <span class="text-gray-400 italic">Aucun délégué assigné</span>
                                                @else
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($template->delegates as $d)
                                                            <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-gray-700">{{ $d->first_name }} {{ $d->last_name }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <form action="{{ route('teacher.seance-templates.delegates.store', $template->id) }}" method="POST" class="flex gap-2">
                                                @csrf
                                                <input type="hidden" name="_method" value="POST">
                                                <select name="delegate_id" class="flex-grow text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary">
                                                    <option value="">Sélectionner délégué</option>
                                                    @foreach($delegates as $d)
                                                        <option value="{{ $d->id }}">{{ $d->first_name }} {{ $d->last_name }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="px-3 py-1.5 bg-gray-800 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition">Ajouter</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pending Reports -->
         <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-clipboard-check text-success"></i> Rapports à valider
            </h3>
            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($pendingReports as $pr)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-orange-50 border border-orange-100">
                        <div class="flex items-center gap-3">
                             <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                                <i class="fas fa-file-alt text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $pr->seance->ue->nom ?? 'Séance' }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pr->seance->jour)->format('d/m/Y') }} • Par {{ $pr->enseignant->first_name ?? 'Délégué' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('teacher.reports.show', $pr->id) }}" class="px-3 py-1 bg-white text-orange-600 text-xs font-bold rounded-lg shadow-sm border border-orange-100 hover:bg-orange-50 transition">
                            Examiner
                        </a>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500 mb-3">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-gray-500 text-sm">Aucun rapport en attente.</p>
                        <p class="text-gray-400 text-xs">Bon travail !</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection
