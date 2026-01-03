@extends('layouts.app')

@section('title', 'Créer un rapport')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Créer un rapport pour la séance du {{ \Carbon\Carbon::parse($seance->jour)->format('d/m/Y') }}</h1>
        <a href="{{ route('teacher.dashboard') }}" class="bg-gray-200 px-4 py-2 rounded">Retour</a>
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
        <h2 class="text-lg font-semibold mb-2">{{ $seance->ue->nom ?? 'N/A' }} — {{ $seance->groupe->nom ?? '' }}</h2>
        <p class="text-sm text-gray-600 mb-4">{{ \Carbon\Carbon::parse($seance->jour)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</p>

        @php $user = auth()->user(); @endphp
        <form action="{{ $user && isset($user->role) && $user->role === 'delegate' ? route('delegate.seances.reports.store', $seance->id) : route('teacher.seances.reports.store', $seance->id) }}" method="POST">
            @csrf

                <!-- Chapitre sélection -->
                @if(optional($seance->ue)->chapters && $seance->ue->chapters->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="chapter_id">Chapitre traité (optionnel)</label>
                        <select id="chapter_id" name="chapter_id" class="w-full border rounded p-2 @error('chapter_id') border-red-500 @enderror">
                            <option value="">-- Aucun --</option>
                            @foreach($seance->ue->chapters as $chapter)
                                <option value="{{ $chapter->id }}" {{ old('chapter_id', $seance->rapportSeance->chapter_id ?? '') == $chapter->id ? 'selected' : '' }}>{{ $chapter->title }}</option>
                            @endforeach
                        </select>
                        @error('chapter_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="contenu">Contenu du rapport</label>
                <textarea id="contenu" name="contenu" rows="8" class="w-full border rounded p-3 @error('contenu') border-red-500 @enderror">{{ old('contenu', $seance->rapportSeance->contenu ?? '') }}</textarea>
                @error('contenu')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Envoyer</button>
                <a href="{{ (auth()->user() && auth()->user()->role === 'delegate') ? route('delegate.dashboard') : route('teacher.dashboard') }}" class="bg-gray-200 px-4 py-2 rounded">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
