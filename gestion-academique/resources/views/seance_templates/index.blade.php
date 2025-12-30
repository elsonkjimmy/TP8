@extends('layouts.app')

@section('title', 'Templates d\'emploi du temps')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Templates d'emploi du temps</h1>
        <div class="flex gap-2">
            <a href="{{ route('seance-templates.export.show') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Exporter
            </a>
            <a href="{{ route('seance-templates.import') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-upload mr-2"></i>Importer
            </a>
            <a href="{{ route('seance-templates.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-accent transition-colors">
                <i class="fas fa-plus mr-2"></i>Ajouter
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    @if(session('errors'))
        <div class="mb-4 p-4 bg-yellow-100 text-yellow-700 rounded">
            <strong>Erreurs lors de l'import:</strong>
            <ul class="mt-2">
                @foreach(session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form action="{{ route('seance-templates.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="filiere_id" class="block text-gray-700 text-sm font-bold mb-2">Filière</label>
                <select id="filiere_id" name="filiere_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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
                <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Tous les groupes</option>
                    @foreach ($groupes as $groupe)
                        <option value="{{ $groupe->id }}" data-filiere-id="{{ $groupe->filiere_id }}" {{ $selectedGroupe == $groupe->id ? 'selected' : '' }}>
                            {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-start">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau horaire -->
    <div class="bg-white rounded-xl shadow-lg overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border border-gray-300 bg-gray-100 px-4 py-3 text-left font-bold text-gray-700 w-32">Horaires</th>
                    @foreach ($timetableGrid as $day => $slots)
                        @php
                            $isToday = $dayDates[$day]->format('Y-m-d') === $today->format('Y-m-d');
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
                                @forelse ($slots[$slotKey] as $template)
                                    <div class="mb-2 p-3 rounded-lg bg-blue-100 text-blue-900 border border-blue-300 text-sm">
                                        <p class="font-bold">{{ $template->ue->nom ?? 'N/A' }}</p>
                                        <p class="text-xs">{{ $template->ue->code ?? 'N/A' }}</p>
                                        <p class="text-xs mt-1">Salle: {{ $template->salle->numero ?? 'N/A' }}</p>
                                        <p class="text-xs">Groupe: {{ $template->groupe->nom ?? 'N/A' }}</p>
                                        <p class="text-xs">{{ $template->enseignant->first_name ?? '' }} {{ $template->enseignant->last_name ?? '' }}</p>
                                        <div class="mt-2 flex gap-1">
                                            <a href="{{ route('seance-templates.edit', $template) }}" class="text-blue-600 text-xs hover:underline">Edit</a>
                                            <form action="{{ route('seance-templates.destroy', $template) }}" method="POST" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 text-xs hover:underline">Suppr</button>
                                            </form>
                                        </div>
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
