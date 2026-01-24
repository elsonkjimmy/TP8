@extends('layouts.app')

@section('title', 'Gestion des Séances')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Gestion des Séances</h1>
            <div class="flex gap-3">
                <a href="{{ route('admin.seances.generate.form') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors">
                    <i class="fas fa-magic mr-2"></i>Générer des séances
                </a>
                <a href="{{ route('admin.seances.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                    <i class="fas fa-plus mr-2"></i>Ajouter une séance
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filtre -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Filtrer les séances</h2>
            <form action="{{ route('admin.seances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="filiere_id" class="block text-sm font-medium text-gray-700 mb-2">Filière</label>
                    <select name="filiere_id" id="filiere_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">-- Toutes les filières --</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="groupe_id" class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select name="groupe_id" id="groupe_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">-- Tous les niveaux --</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}" {{ request('groupe_id') == $groupe->id ? 'selected' : '' }}>
                                {{ $groupe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semestre</label>
                    <select name="semester" id="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                        <option value="">-- Tous les semestres --</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>
                                {{ $sem }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.seances.index') }}" class="flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-500 transition-colors text-center">
                        <i class="fas fa-redo mr-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="table-responsive">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Filière
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                UE
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jour
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Heure Début
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Heure Fin
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Salle
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Niveau
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Division
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Enseignant
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seances as $seance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $seance->groupe->filiere->nom ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $seance->ue->nom ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($seance->jour)->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $seance->salle->numero ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $seance->groupe->nom ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $seance->group_divisions ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.seances.edit', $seance->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.seances.destroy', $seance->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
