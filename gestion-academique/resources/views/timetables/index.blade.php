@extends('layouts.app')

@section('title', 'Emplois du temps')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-primary mb-6">Emplois du temps - Semaine du {{ $monday->format('d/m/Y') }}</h1>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-8">
            <form action="{{ route('timetables.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 items-end">
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
                    <label for="groupe_id" class="block text-gray-700 text-sm font-bold mb-2">Groupe</label>
                    <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                        <option value="">Tous les groupes</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}" data-filiere-id="{{ $groupe->filiere_id }}" {{ $selectedGroupe == $groupe->id ? 'selected' : '' }}>
                                {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-start">
                    <button type="submit" class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors text-sm">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
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
                                            <p class="text-xs">Groupe: {{ $seance->groupe->nom ?? 'N/A' }}</p>
                                            <p class="text-xs">Enseignant: {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}</p>
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
                                                    <p class="text-xs">Groupe: {{ $seance->groupe->nom ?? 'N/A' }}</p>
                                                    <p class="text-xs">Enseignant: {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}</p>
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
        document.getElementById('filiere_id').addEventListener('change', function() {
            const selectedFiliereId = this.value;
            const groupeSelect = document.getElementById('groupe_id');
            const groupeOptions = groupeSelect.querySelectorAll('option[data-filiere-id]');

            // Afficher/masquer les options de groupe selon la filière sélectionnée
            groupeOptions.forEach(option => {
                if (selectedFiliereId === '' || option.dataset.filiereId === selectedFiliereId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });

            // Réinitialiser la sélection du groupe
            groupeSelect.value = '';
        });

        // Initialiser l'affichage des groupes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const event = new Event('change');
            document.getElementById('filiere_id').dispatchEvent(event);
        });
    </script>
@endsection
