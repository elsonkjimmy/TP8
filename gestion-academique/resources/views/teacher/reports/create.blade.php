@extends('layouts.app')

@section('title', 'Créer un Rapport de Séance')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Créer un Rapport pour la Séance du {{ \Carbon\Carbon::parse($seance->jour)->format('d/m/Y') }}</h1>
            <a href="{{ route('teacher.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">Veuillez corriger les erreurs suivantes :</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('teacher.seances.reports.store', $seance->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contenu">Contenu du Rapport</label>
                    <textarea id="contenu" name="contenu" rows="8" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('contenu') border-red-500 @enderror" required>{{ old('contenu') }}</textarea>
                    @error('contenu')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="statut">Statut du Rapport</label>
                    <select id="statut" name="statut" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('statut') border-red-500 @enderror" required>
                        <option value="pending" {{ old('statut') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="approved" {{ old('statut') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                        <option value="rejected" {{ old('statut') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                    @error('statut')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-save mr-2"></i>Enregistrer le Rapport
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
