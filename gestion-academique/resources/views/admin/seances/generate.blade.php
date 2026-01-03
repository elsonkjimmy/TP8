@extends('layouts.app')

@section('title', 'Générer les séances')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Générer les séances</h1>
        <a href="{{ route('admin.seances.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">Retour</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl">
        <div class="mb-6 p-4 bg-blue-50 border border-blue-300 rounded-lg">
            <p class="text-sm text-gray-700">
                <strong>Explication :</strong> Cette page génère automatiquement les séances à partir des templates d'emploi du temps 
                pour une période donnée. Les séances existantes ne sont pas recréées (doublons évités).
            </p>
        </div>

        <form action="{{ route('admin.seances.generate.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Date de début</label>
                <input 
                    type="date" 
                    name="start_date" 
                    id="start_date"
                    value="{{ old('start_date', now()->format('Y-m-d')) }}"
                    class="w-full border rounded px-4 py-2 @error('start_date') border-red-500 @enderror"
                    required
                >
                @error('start_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Date de fin</label>
                <input 
                    type="date" 
                    name="end_date" 
                    id="end_date"
                    value="{{ old('end_date', now()->addMonths(6)->format('Y-m-d')) }}"
                    class="w-full border rounded px-4 py-2 @error('end_date') border-red-500 @enderror"
                    required
                >
                @error('end_date')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="semester" class="block text-gray-700 text-sm font-bold mb-2">Semestre (optionnel)</label>
                <select 
                    name="semester" 
                    id="semester"
                    class="w-full border rounded px-4 py-2"
                >
                    <option value="">Tous les semestres</option>
                    @foreach ($semesters as $sem)
                        <option value="{{ $sem }}" {{ old('semester') == $sem ? 'selected' : '' }}>{{ $sem }}</option>
                    @endforeach
                </select>
                <p class="text-gray-600 text-xs mt-2">
                    Sélectionnez un semestre pour générer seulement les séances des templates associés. 
                    Laissez vide pour utiliser tous les templates.
                </p>
            </div>

            <div class="flex gap-3">
                <button 
                    type="submit" 
                    class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-accent transition-colors"
                    onclick="return confirm('Générer les séances pour cette période ? Les séances existantes ne seront pas recréées.');"
                >
                    Générer
                </button>
                <a href="{{ route('admin.seances.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Informations</h2>
        <ul class="space-y-3 text-sm text-gray-700">
            <li><strong>Génération par période :</strong> Spécifiez une plage de dates pour créer les séances.</li>
            <li><strong>Filtrage par semestre :</strong> Utilisez le champ semestre pour ne générer que les templates d'un semestre spécifique.</li>
            <li><strong>Prévention des doublons :</strong> Les séances déjà existantes (même jour/heure/groupe/enseignant) ne sont pas recréées.</li>
            <li><strong>Délégués :</strong> Les délégués assignés au template sont automatiquement copiés aux séances générées.</li>
            <li><strong>Exemple :</strong> Générez du 01/01/2026 au 30/06/2026 pour le semestre 1, puis du 01/07/2026 au 31/12/2026 pour le semestre 2.</li>
        </ul>
    </div>
</div>
@endsection
