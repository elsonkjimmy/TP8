@extends('layouts.app')

@section('title', 'Emplois du temps')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-primary mb-6">Emplois du temps - Semaine du {{ $monday->format('d/m/Y') }}</h1>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8">
            <form action="{{ route('timetables.index') }}" method="GET" class="space-y-4">
                <!-- Filtres principaux -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 items-end">
                    <div>
                        <label for="filiere_id" class="block text-gray-700 text-sm font-bold mb-2">Filière</label>
                        <select id="filiere_id" name="filiere_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                            <option value="">Toutes les filières</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ $selectedFiliere == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="groupe_id" class="block text-gray-700 text-sm font-bold mb-2">Niveau</label>
                        <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                            <option value="">Tous les Niveaux</option>
                            @foreach ($groupes as $groupe)
                                <option value="{{ $groupe->id }}" data-filiere-id="{{ $groupe->filiere_id }}" {{ $selectedGroupe == $groupe->id ? 'selected' : '' }}>
                                    {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-start gap-2">
                        <button type="submit" class="flex-1 sm:flex-none bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors text-sm">
                            <i class="fas fa-filter mr-2"></i>Filtrer
                        </button>
                        <button type="button" id="toggleAdvancedFilters" class="flex-1 sm:flex-none bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors text-sm">
                            <i class="fas fa-plus mr-2"></i>Plus de filtres
                        </button>
                        <a id="exportPdfBtn" href="#" class="flex-1 sm:flex-none bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed text-sm inline-flex items-center justify-center" disabled>
                            <i class="fas fa-file-pdf mr-2"></i>Exporter PDF
                        </a>
                    </div>
                </div>

                <!-- Filtres avancés (masqués par défaut) -->
                <div id="advancedFiltersSection" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-4 border-t">
                    <div>
                        <label for="enseignant_id" class="block text-gray-700 text-sm font-bold mb-2">Enseignant</label>
                        <select id="enseignant_id" name="enseignant_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                            <option value="">Tous les enseignants</option>
                            @foreach ($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ $selectedEnseignant == $enseignant->id ? 'selected' : '' }}>
                                    {{ $enseignant->first_name }} {{ $enseignant->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="salle_id" class="block text-gray-700 text-sm font-bold mb-2">Salle</label>
                        <select id="salle_id" name="salle_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                            <option value="">Toutes les salles</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}" {{ $selectedSalle == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->numero }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-x-auto">
            <div class="hidden md:block overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border border-gray-300 bg-gray-100 px-4 py-3 text-left font-bold text-gray-700 w-32">Horaires</th>
                        @foreach ($timetableGrid as $day => $slots)
                                @php
                                    $dayDate = $monday->copy()->addDays(array_search($day, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']));
                                    $isToday = $dayDate->format('Y-m-d') === $today->format('Y-m-d');
                                @endphp
                                <th class="border border-gray-300 {{ $isToday ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700' }} px-4 py-3 text-center font-bold w-48">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeSlots as $slotKey => $slot)
                        <tr>
                            <td class="border border-gray-300 bg-gray-50 px-4 py-3 font-bold text-gray-700 text-center">
                                {{ $slotKey }}
                            </td>
                            @foreach ($timetableGrid as $day => $slots)
                                <td class="border border-gray-300 px-4 py-3 align-top bg-white hover:bg-gray-50 transition-colors" style="min-height: 120px;">
                                    @forelse ($slots[$slotKey] as $seance)
                                        <div class="mb-2 p-3 rounded-lg bg-blue-100 text-blue-900 border border-blue-300 text-sm">
                                            <p class="font-bold">{{ $seance->ue->nom ?? 'N/A' }}</p>
                                            <p class="text-xs">{{ $seance->ue->code ?? 'N/A' }}</p>
                                            <p class="text-xs mt-1">Salle: {{ $seance->salle->numero ?? 'N/A' }}</p>
                                            <p class="text-xs">Niveau: {{ $seance->groupe->nom ?? 'N/A' }}</p>
                                            @if($seance->group_divisions)
                                                <p class="text-xs text-blue-700 font-semibold">Groupe: {{ $seance->group_divisions }}</p>
                                            @endif
                                            <p class="text-xs">Enseignant: {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}</p>
                                            
                                            {{-- Desiderata Button --}}
                                            @auth
                                                @if(Auth::user()->role === 'teacher' && !$seance->enseignant_id && !isset($seance->jour))
                                                    <div class="mt-2 pt-2 border-t border-blue-200 flex justify-end">
                                                        <button onclick="openDesiderataModal({{ $seance->id }}, '{{ addslashes($seance->ue->nom ?? '') }}', '{{ $seance->day_of_week }}', '{{ $seance->start_time }}')" 
                                                            class="bg-orange-500 hover:bg-orange-600 text-white text-xs px-2 py-1 rounded shadow transition-colors flex items-center gap-1" title="Se positionner sur ce créneau">
                                                            <i class="fas fa-plus"></i> Se positionner
                                                        </button>
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-xs italic">-</p>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <!-- Vue mobile: Cartes par jour -->
            <div class="md:hidden p-4 space-y-4">
                @foreach ($timetableGrid as $day => $slots)
                    @php
                        $dayDate = $monday->copy()->addDays(array_search($day, ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']));
                        $isToday = $dayDate->format('Y-m-d') === $today->format('Y-m-d');
                    @endphp
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-100 p-3 font-bold {{ $isToday ? 'bg-primary text-white' : 'text-gray-700' }}">
                            {{ $day }} - {{ $dayDate->format('d/m') }}
                        </div>
                        <div class="space-y-2 p-3">
                            @php
                                $hasSeances = false;
                                foreach ($slots as $slotSeances) {
                                    if (count($slotSeances) > 0) {
                                        $hasSeances = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($hasSeances)
                                @foreach ($timeSlots as $slotKey => $slot)
                                    @if(count($slots[$slotKey] ?? []) > 0)
                                        <div class="border-t pt-2">
                                            <p class="text-xs font-semibold text-gray-600 mb-2">{{ $slotKey }}</p>
                                            @foreach ($slots[$slotKey] as $seance)
                                                <div class="mb-2 p-2 rounded-lg bg-blue-100 text-blue-900 border border-blue-300 text-xs">
                                                    <p class="font-bold">{{ $seance->ue->nom ?? 'N/A' }}</p>
                                                    <p class="text-xs">{{ $seance->ue->code ?? 'N/A' }}</p>
                                                    <p class="text-xs mt-1">Salle: {{ $seance->salle->numero ?? 'N/A' }}</p>
                                                    <p class="text-xs">Niveau: {{ $seance->groupe->nom ?? 'N/A' }}</p>
                                                    @if($seance->group_divisions)
                                                        <p class="text-xs text-blue-700 font-semibold">Groupe: {{ $seance->group_divisions }}</p>
                                                    @endif
                                                    <p class="text-xs">Enseignant: {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}</p>
                                                    
                                                    {{-- Desiderata Button (Mobile) --}}
                                                    @auth
                                                        @if(Auth::user()->role === 'teacher' && !$seance->enseignant_id && !isset($seance->jour))
                                                            <div class="mt-2 pt-2 border-t border-blue-200 flex justify-end">
                                                                <button onclick="openDesiderataModal({{ $seance->id }}, '{{ addslashes($seance->ue->nom ?? '') }}', '{{ $seance->day_of_week }}', '{{ $seance->start_time }}')" 
                                                                    class="bg-orange-500 hover:bg-orange-600 text-white text-xs px-2 py-1 rounded shadow transition-colors flex items-center gap-1">
                                                                    <i class="fas fa-plus"></i> Se positionner
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-gray-400 text-xs italic text-center py-4">Aucune séance</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Toggle filtres avancés
        document.getElementById('toggleAdvancedFilters').addEventListener('click', function(e) {
            e.preventDefault();
            const advancedSection = document.getElementById('advancedFiltersSection');
            advancedSection.classList.toggle('hidden');
            
            // Changer le texte du bouton
            const icon = this.querySelector('i');
            if (advancedSection.classList.contains('hidden')) {
                icon.className = 'fas fa-plus mr-2';
                this.innerHTML = '<i class="fas fa-plus mr-2"></i>Plus de filtres';
            } else {
                icon.className = 'fas fa-minus mr-2';
                this.innerHTML = '<i class="fas fa-minus mr-2"></i>Moins de filtres';
            }
        });

        // Afficher les filtres avancés s'il y a une sélection
        document.addEventListener('DOMContentLoaded', function() {
            const enseignantSelect = document.getElementById('enseignant_id');
            const salleSelect = document.getElementById('salle_id');
            const advancedSection = document.getElementById('advancedFiltersSection');
            const toggleBtn = document.getElementById('toggleAdvancedFilters');

            if (enseignantSelect.value !== '' || salleSelect.value !== '') {
                advancedSection.classList.remove('hidden');
                toggleBtn.innerHTML = '<i class="fas fa-minus mr-2"></i>Moins de filtres';
            }
        });

        // Filtrage en cascade : filière -> groupe
        document.getElementById('filiere_id').addEventListener('change', function() {
            const selectedFiliereId = this.value;
            const groupeSelect = document.getElementById('groupe_id');
            const groupeOptions = groupeSelect.querySelectorAll('option[data-filiere-id]');

            groupeOptions.forEach(option => {
                if (selectedFiliereId === '' || option.dataset.filiereId === selectedFiliereId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            groupeSelect.value = '';
        });

        // Initialiser l'affichage des groupes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const event = new Event('change');
            document.getElementById('filiere_id').dispatchEvent(event);
            updateExportButtonState();
        });

        // Gérer l'activation du bouton Export PDF
        function updateExportButtonState() {
            const filiereId = document.getElementById('filiere_id').value;
            const groupeId = document.getElementById('groupe_id').value;
            const exportBtn = document.getElementById('exportPdfBtn');

            // Le bouton s'active que si : filière ET groupe sont sélectionnés
            if (filiereId !== '' && groupeId !== '') {
                // Activer le bouton
                exportBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                exportBtn.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
                exportBtn.href = `{{ route('timetables.export-pdf') }}?filiere_id=${filiereId}&groupe_id=${groupeId}`;
                exportBtn.removeAttribute('disabled');
                exportBtn.style.pointerEvents = 'auto';
            } else {
                // Désactiver le bouton
                exportBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                exportBtn.classList.remove('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
                exportBtn.href = '#';
                exportBtn.setAttribute('disabled', 'disabled');
                exportBtn.style.pointerEvents = 'none';
            }
        }

        // Mettre à jour l'état du bouton lors du changement de filière ou groupe
        document.getElementById('filiere_id').addEventListener('change', updateExportButtonState);
        document.getElementById('groupe_id').addEventListener('change', updateExportButtonState);

        // Modal Logic
        function openDesiderataModal(templateId, ueName, day, time) {
            document.getElementById('modal_template_id').value = templateId;
            document.getElementById('modal_ue_name').textContent = ueName;
            document.getElementById('desiderata-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }

        function closeDesiderataModal() {
            document.getElementById('desiderata-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>

    <!-- Modal Desiderata -->
    <div id="desiderata-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeDesiderataModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-primary p-6 flex justify-between items-center text-white">
                        <h3 class="text-xl font-bold" id="modal-title">
                            <i class="fas fa-hand-paper mr-2"></i>Se positionner
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200" onclick="closeDesiderataModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="px-6 py-6 bg-white">
                        <div class="mb-6 bg-blue-50 border border-blue-100 rounded-xl p-4">
                             <p class="text-sm text-gray-500 font-bold uppercase mb-1">Unité d'Enseignement</p>
                             <p id="modal_ue_name" class="font-bold text-gray-900 text-lg"></p>
                        </div>

                        <form action="{{ route('teacher.desideratas.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="seance_template_id" id="modal_template_id">
                            
                            <div class="mb-6">
                                <label for="comment" class="block text-sm font-bold text-gray-700 mb-2">Message (Facultatif)</label>
                                <textarea name="comment" id="comment" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary shadow-sm text-sm" placeholder="Ex: Ce créneau me convient parfaitement..."></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors" onclick="closeDesiderataModal()">Annuler</button>
                                <button type="submit" class="px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
