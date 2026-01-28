@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Effectifs</h1>
        <p class="text-gray-600 mt-2">Définir l'effectif de chaque niveau par année académique</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-green-700">✓ {{ $message }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire d'ajout/modification -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold mb-4">Ajouter/Modifier</h2>
                
                <form action="{{ route('admin.groupe-effectifs.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="groupe_id" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                        <select name="groupe_id" id="groupe_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('groupe_id') border-red-500 @enderror" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($groupes->sortBy('nom') as $groupe)
                                <option value="{{ $groupe->id }}">{{ $groupe->nom }} ({{ $groupe->filiere->nom ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                        @error('groupe_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700 mb-1">Année</label>
                        <select name="annee" id="annee" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('annee') border-red-500 @enderror" required>
                            @foreach($annees as $annee)
                                <option value="{{ $annee }}" {{ $annee == $anneeActuelle ? 'selected' : '' }}>{{ $annee }}/{{ $annee + 1 }}</option>
                            @endforeach
                        </select>
                        @error('annee')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semestre" class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                        <select name="semestre" id="semestre" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semestre') border-red-500 @enderror" required>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem }}">{{ $sem }}</option>
                            @endforeach
                        </select>
                        @error('semestre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="effectif" class="block text-sm font-medium text-gray-700 mb-1">Effectif</label>
                        <input type="number" name="effectif" id="effectif" min="1" max="500" placeholder="ex: 45" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('effectif') border-red-500 @enderror" required>
                        @error('effectif')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        Enregistrer
                    </button>
                </form>
            </div>
        </div>

        <!-- Tableau des effectifs -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Filière</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Niveau</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Année</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Semestre</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Effectif</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupes as $groupe)
                                @if($groupe->effectifs->count() > 0)
                                    @foreach($groupe->effectifs->sortByDesc('annee')->sortBy('semestre') as $effectif)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $groupe->filiere->nom ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $groupe->nom }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $effectif->annee }}/{{ $effectif->annee + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $effectif->semestre }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 text-center font-semibold">{{ $effectif->effectif }}</td>
                                            <td class="px-4 py-3 text-center text-sm">
                                                <form action="{{ route('admin.groupe-effectifs.destroy', $effectif) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Aucun effectif défini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($groupes->flatMap(fn($g) => $g->effectifs)->count() > 0)
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h3 class="font-semibold text-blue-900 mb-2">💡 Info</h3>
                    <ul class="text-sm text-blue-800 space-y-1 ml-4 list-disc">
                        <li>Ces effectifs seront utilisés pour valider la capacité des salles</li>
                        <li>Un avertissement s'affichera si une salle est insuffisante</li>
                        <li>Vous pouvez quand même créer la séance si nécessaire</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .container {
        max-width: 1200px;
    }
</style>
@endsection
