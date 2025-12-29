@extends('layouts.app')

@section('title', 'Emplois du temps')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-primary mb-6">Emplois du temps</h1>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form action="{{ route('timetables.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                    <input type="date" id="date" name="date" value="{{ $selectedDate }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
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
                            <option value="{{ $groupe->id }}" {{ $selectedGroupe == $groupe->id ? 'selected' : '' }}>
                                {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="enseignant_id" class="block text-gray-700 text-sm font-bold mb-2">Enseignant</label>
                    <select id="enseignant_id" name="enseignant_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Tous les enseignants</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $selectedTeacher == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 bg-gray-100 border-b border-gray-200">
                <h2 class="text-xl font-bold text-primary">Séances du {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</h2>
            </div>
            <div class="table-responsive">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Heure
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Détails de la Séance
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hours as $hour)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-900">
                                    {{ $hour }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @forelse ($timetableGrid[$hour] as $seance)
                                        <div class="mb-2 p-3 rounded-lg bg-blue-100 text-blue-800 border border-blue-200">
                                            <p class="font-bold">{{ $seance->ue->nom ?? 'N/A' }} ({{ $seance->ue->code ?? 'N/A' }})</p>
                                            <p>Enseignant: {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}</p>
                                            <p>Salle: {{ $seance->salle->numero ?? 'N/A' }}</p>
                                            <p>Groupe: {{ $seance->groupe->nom ?? 'N/A' }}</p>
                                            <p>De {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} à {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</p>
                                        </div>
                                    @empty
                                        <p class="text-gray-500">Aucune séance prévue.</p>
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                    Aucune heure définie pour l'emploi du temps.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
