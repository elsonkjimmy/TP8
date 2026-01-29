@extends('layouts.app')

@section('title', 'Emplois du temps')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">
                Emplois du temps
            </h1>
            <p class="text-gray-500 mt-1">
                Semaine du <span class="font-bold text-gray-800">{{ $monday->format('d/m/Y') }}</span>
            </p>
        </div>
        
        <!-- Navigation Semaine (Fake for now, but visual) -->
        <div class="flex bg-white rounded-xl shadow-soft border border-gray-100 p-1">
            <button class="px-3 py-1 text-gray-400 hover:text-primary transition"><i class="fas fa-chevron-left"></i></button>
            <span class="px-4 py-1 text-sm font-bold text-gray-700 border-x border-gray-100">Cette semaine</span>
            <button class="px-3 py-1 text-gray-400 hover:text-primary transition"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>
        
        <form action="{{ route('timetables.index') }}" method="GET" class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-filter text-primary"></i> Filtres
                </h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="filiere_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Filière</label>
                    <div class="relative">
                        <select id="filiere_id" name="filiere_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer">
                            <option value="">Toutes les filières</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ $selectedFiliere == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                
                <div>
                     <label for="groupe_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Niveau / Groupe</label>
                    <div class="relative">
                        <select id="groupe_id" name="groupe_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer">
                             <option value="">Tous les Niveaux</option>
                            @foreach ($groupes as $groupe)
                                <option value="{{ $groupe->id }}" data-filiere-id="{{ $groupe->filiere_id }}" {{ $selectedGroupe == $groupe->id ? 'selected' : '' }}>
                                    {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                             <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                
                 <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 transition-all duration-300">
                        Filtrer
                    </button>
                    <button type="button" id="toggleAdvancedFilters" class="px-4 py-3 bg-white text-gray-600 border border-gray-200 font-bold rounded-xl hover:bg-gray-50 transition-colors" title="Plus de filtres">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                </div>
            </div>

            <!-- Advanced Filters -->
             <div id="advancedFiltersSection" class="hidden border-t border-gray-100 pt-4 mt-4 animate-fade-in-down">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                     <div>
                        <label for="enseignant_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Enseignant</label>
                        <div class="relative">
                            <select id="enseignant_id" name="enseignant_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2.5 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer text-sm">
                                <option value="">Tous les enseignants</option>
                                @foreach ($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" {{ $selectedEnseignant == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->first_name }} {{ $enseignant->last_name }}
                                    </option>
                                @endforeach
                            </select>
                             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                         <label for="salle_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Salle</label>
                          <div class="relative">
                            <select id="salle_id" name="salle_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2.5 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer text-sm">
                                <option value="">Toutes les salles</option>
                                @foreach ($salles as $salle)
                                    <option value="{{ $salle->id }}" {{ $selectedSalle == $salle->id ? 'selected' : '' }}>
                                        {{ $salle->numero }}
                                    </option>
                                @endforeach
                            </select>
                             <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Timetable Grid -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <div class="min-w-[1024px]"> <!-- Make sure it scrolls horizontally on small screens -->
                 <table class="w-full border-separate border-spacing-0">
                    <thead>
                        <tr>
                            <th class="w-20 p-4 sticky left-0 z-20 bg-gray-50 border-b border-gray-200 text-center font-bold text-gray-400 uppercase text-xs tracking-wider">
                                Heure
                            </th>
                             @foreach ($timetableGrid as $day => $slots)
                                @php
                                    $dayDate = $monday->copy()->addDays(array_search($day, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']));
                                    $isToday = $dayDate->format('Y-m-d') === $today->format('Y-m-d');
                                @endphp
                                <th class="p-4 border-b border-gray-200 text-center min-w-[160px] {{ $isToday ? 'bg-primary/5' : 'bg-gray-50' }}">
                                    <span class="block text-sm font-bold {{ $isToday ? 'text-primary' : 'text-gray-700' }}">{{ $day }}</span>
                                    <span class="block text-xs {{ $isToday ? 'text-primary/70' : 'text-gray-400' }}">{{ $dayDate->format('d/m') }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                         @foreach ($timeSlots as $slotKey => $slot)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <!-- Time Column -->
                                <td class="p-4 sticky left-0 z-10 bg-white border-r border-gray-100 text-center text-xs font-bold text-gray-500 group-hover:bg-gray-50/50">
                                    {{ $slotKey }}
                                </td>
                                
                                <!-- Slots -->
                                @foreach ($timetableGrid as $day => $slots)
                                    <td class="p-2 align-top h-32 border-r border-gray-100 last:border-r-0 relative">
                                        {{-- Empty State Placeholder (Optional, clean look preferred) --}}
                                        @forelse ($slots[$slotKey] as $seance)
                                             <div class="relative overflow-hidden rounded-xl border border-l-4 p-3 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 group/card
                                                {{ $seance->enseignant_id ? 'bg-white border-blue-500 border-gray-100' : 'bg-orange-50 border-orange-400 border-orange-100' }}
                                                mb-2"
                                             >
                                                <!-- Top Bar -->
                                                <div class="flex justify-between items-start mb-1">
                                                     <span class="text-[10px] font-bold px-1.5 py-0.5 rounded text-gray-500 bg-gray-100">
                                                        {{ $seance->salle->numero ?? '??' }}
                                                    </span>
                                                    
                                                     {{-- Request Button for Teachers --}}
                                                    @auth
                                                        @if(Auth::user()->role === 'teacher' && !$seance->enseignant_id && !isset($seance->jour))
                                                            <button onclick="openDesiderataModal({{ $seance->id }}, '{{ addslashes($seance->ue->nom ?? '') }}', '{{ $seance->day_of_week }}', '{{ $seance->start_time }}')" 
                                                                class="w-6 h-6 rounded-full bg-white text-orange-500 shadow-sm flex items-center justify-center hover:bg-orange-500 hover:text-white transition-colors" title="Se positionner">
                                                                <i class="fas fa-plus text-xs"></i>
                                                            </button>
                                                        @endif
                                                    @endauth
                                                </div>

                                                <!-- Content -->
                                                <h4 class="font-bold text-xs text-gray-800 leading-tight mb-0.5 line-clamp-2" title="{{ $seance->ue->nom ?? '' }}">
                                                    {{ $seance->ue->nom ?? 'N/A' }}
                                                </h4>
                                                <p class="text-[10px] text-gray-500 mb-1">{{ $seance->groupe->nom ?? 'N/A' }}</p>

                                                <!-- Bottom Info -->
                                                <div class="flex items-center gap-1 mt-2">
                                                     @if($seance->enseignant)
                                                        <div class="flex items-center gap-1 text-[10px] text-blue-600 font-medium bg-blue-50 px-1.5 py-0.5 rounded-md">
                                                            <i class="fas fa-user-circle"></i>
                                                            <span class="truncate max-w-[80px]">{{ $seance->enseignant->last_name }}</span>
                                                        </div>
                                                     @else
                                                        <div class="flex items-center gap-1 text-[10px] text-orange-600 font-medium bg-orange-100 px-1.5 py-0.5 rounded-md">
                                                            <i class="fas fa-question-circle"></i>
                                                            <span>Vacant</span>
                                                        </div>
                                                     @endif
                                                </div>
                                            </div>
                                        @empty
                                            <!-- Empty slot visual guide on hover -->
                                            <div class="w-full h-full rounded-xl border-2 border-dashed border-gray-100 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-gray-300">
                                                <i class="fas fa-plus"></i>
                                            </div>
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
    
     <!-- Mobile List View (Hidden on LG) -->
    <div class="md:hidden mt-8 space-y-6">
        @foreach ($timetableGrid as $day => $slots)
            @php
                $dayDate = $monday->copy()->addDays(array_search($day, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']));
                $isToday = $dayDate->format('Y-m-d') === $today->format('Y-m-d');
                
                // Check if day has sessions
                $hasSeances = false;
                foreach ($slots as $slotSeances) {
                    if (count($slotSeances) > 0) { $hasSeances = true; break; }
                }
            @endphp
            
            @if($hasSeances)
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex justify-between items-center {{ $isToday ? 'bg-primary/5' : '' }}">
                        <span class="font-bold text-gray-800">{{ $day }}</span>
                        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">{{ $dayDate->format('d M') }}</span>
                    </div>
                    <div class="p-4 space-y-3">
                         @foreach ($timeSlots as $slotKey => $slot)
                              @foreach ($slots[$slotKey] as $seance)
                                <div class="flex gap-4 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="flex flex-col items-center justify-center w-12 shrink-0 border-r border-gray-200 pr-3">
                                        @php
                                            $slotParts = explode(' - ', $slotKey);
                                        @endphp
                                        <span class="text-xs font-bold text-gray-500">{{ $slotParts[0] }}</span>
                                        @if(isset($slotParts[1]))
                                            <div class="w-px h-2 bg-gray-300 my-0.5"></div>
                                            <span class="text-xs font-bold text-gray-400">{{ $slotParts[1] }}</span>
                                        @endif
                                    </div>
                                    <div class="grow">
                                         <h4 class="font-bold text-sm text-gray-900">{{ $seance->ue->nom ?? 'UE Inconnue' }}</h4>
                                         <p class="text-xs text-gray-500 mb-1">{{ $seance->groupe->nom }} • Salle {{ $seance->salle->numero }}</p>
                                         @if($seance->enseignant)
                                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $seance->enseignant->first_name }} {{ $seance->enseignant->last_name }}</span>
                                         @else
                                             <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">Enseignant manquant</span>
                                         @endif
                                    </div>
                                </div>
                              @endforeach
                         @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

</div>

<script>
    // Toggle filtres avancés
    document.getElementById('toggleAdvancedFilters').addEventListener('click', function(e) {
        e.preventDefault();
        const advancedSection = document.getElementById('advancedFiltersSection');
        advancedSection.classList.toggle('hidden');
        
        // Update icon color/state if needed
        this.classList.toggle('bg-primary');
        this.classList.toggle('text-white');
        this.classList.toggle('bg-white');
        this.classList.toggle('text-gray-600');
    });

    // Afficher les filtres avancés s'il y a une sélection au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const enseignantSelect = document.getElementById('enseignant_id');
        const salleSelect = document.getElementById('salle_id');
        const advancedSection = document.getElementById('advancedFiltersSection');
        const toggleBtn = document.getElementById('toggleAdvancedFilters');

        if (enseignantSelect.value !== '' || salleSelect.value !== '') {
            advancedSection.classList.remove('hidden');
            // Toggle button state manually
             toggleBtn.classList.add('bg-primary', 'text-white');
             toggleBtn.classList.remove('bg-white', 'text-gray-600');
        }
    });

    // Filtrage en cascade : filière -> groupe
    document.getElementById('filiere_id').addEventListener('change', function() {
        const selectedFiliereId = this.value;
        const groupeSelect = document.getElementById('groupe_id');
        const groupeOptions = groupeSelect.querySelectorAll('option[data-filiere-id]');

        let firstVisible = null;
        groupeOptions.forEach(option => {
            if (selectedFiliereId === '' || option.dataset.filiereId === selectedFiliereId) {
                option.style.display = 'block';
                if(!firstVisible) firstVisible = option;
            } else {
                option.style.display = 'none';
            }
        });

        // Reset text if current selection is invalid
        const currentOption = groupeSelect.selectedOptions[0];
        if (currentOption && currentOption.dataset.filiereId && currentOption.dataset.filiereId !== selectedFiliereId && selectedFiliereId !== '') {
            groupeSelect.value = '';
        }
    });

    // Trigger change on load
    document.addEventListener('DOMContentLoaded', function() {
        const event = new Event('change');
        document.getElementById('filiere_id').dispatchEvent(event);
    });

    // Modal Logic
    function openDesiderataModal(templateId, ueName, day, time) {
        document.getElementById('modal_template_id').value = templateId;
        document.getElementById('modal_ue_name').textContent = ueName;
        document.getElementById('desiderata-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closeDesiderataModal() {
        document.getElementById('desiderata-modal').classList.add('hidden');
         document.body.style.overflow = 'auto'; // Enable scrolling
    }
</script>

<!-- Modal Desiderata (Premium Style) -->
<div id="desiderata-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeDesiderataModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary to-accent p-6 flex justify-between items-center text-white">
                    <h3 class="text-xl font-bold" id="modal-title">
                        <i class="fas fa-hand-paper mr-2 opacity-80"></i>Se positionner
                    </h3>
                    <button type="button" class="text-white/80 hover:text-white transition-colors" onclick="closeDesiderataModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 bg-white">
                    <div class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-3">
                         <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0 font-bold">UE</div>
                         <div>
                            <p class="text-sm text-gray-500">Unité d'Enseignement</p>
                            <p id="modal_ue_name" class="font-bold text-gray-900 text-lg leading-tight"></p>
                         </div>
                    </div>

                    <form action="{{ route('teacher.desideratas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="seance_template_id" id="modal_template_id">
                        
                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-bold text-gray-700 mb-2">Message (Facultatif)</label>
                            <textarea name="comment" id="comment" rows="3" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm" placeholder="Ex: Ce créneau me convient parfaitement car..."></textarea>
                        </div>
                        
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors" onclick="closeDesiderataModal()">Annuler</button>
                            <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
