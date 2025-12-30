@extends('layouts.app')

@section('title', 'Exporter un emploi du temps')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Exporter un emploi du temps</h1>
        <a href="{{ route('seance-templates.index') }}" class="text-gray-600 hover:text-gray-800">Retour</a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <p class="mb-6 text-gray-700">Sélectionnez les paramètres pour filtrer l'export:</p>

        <form action="{{ route('seance-templates.export') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="filiere_id">Filière (optionnel)</label>
                    <select id="filiere_id" name="filiere_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Toutes les filières --</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_id">Groupe / Niveau (optionnel)</label>
                    <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Tous les groupes --</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}">{{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Télécharger CSV
                </button>
                <a href="{{ route('seance-templates.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
        <h3 class="text-lg font-bold text-blue-900 mb-2">Conseil:</h3>
        <p class="text-blue-800">
            Laissez les filtres vides pour exporter <strong>tous</strong> les templates. 
            Sélectionnez une filière ou un groupe pour exporter uniquement les templates concernés.
        </p>
    </div>
</div>
@endsection
