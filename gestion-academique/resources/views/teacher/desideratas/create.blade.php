@extends('layouts.app')

@section('title', 'Nouveau Désirata')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Nouveau Désirata</h1>
            <a href="{{ route('teacher.desideratas.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-1"></i>Retour
            </a>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('teacher.desideratas.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="ue_id" class="block text-gray-700 font-bold mb-2">Unité d'Enseignement (UE)</label>
                    <select name="ue_id" id="ue_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        <option value="">Sélectionnez une UE</option>
                        @foreach($ues as $ue)
                            <option value="{{ $ue->id }}" {{ old('ue_id') == $ue->id ? 'selected' : '' }}>
                                {{ $ue->code }} - {{ $ue->nom }} ({{ $ue->filiere->code ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('ue_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if($ues->isEmpty())
                        <p class="text-yellow-600 text-xs mt-2">Aucune UE ne vous est assignée. Veuillez contacter l'administrateur.</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="day_of_week" class="block text-gray-700 font-bold mb-2">Jour</label>
                        <select name="day_of_week" id="day_of_week" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            <option value="">Sélectionnez un jour</option>
                            <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Lundi</option>
                            <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Mardi</option>
                            <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Mercredi</option>
                            <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Jeudi</option>
                            <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Vendredi</option>
                            <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Samedi</option>
                        </select>
                        @error('day_of_week')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="salle_id" class="block text-gray-700 font-bold mb-2">Salle (Optionnel)</label>
                        <select name="salle_id" id="salle_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="">Préférence de salle (si applicable)</option>
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->numero }} (Cap: {{ $salle->capacite }})
                                </option>
                            @endforeach
                        </select>
                        @error('salle_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_time" class="block text-gray-700 font-bold mb-2">Heure de début</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        @error('start_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-gray-700 font-bold mb-2">Heure de fin</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        @error('end_time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="comment" class="block text-gray-700 font-bold mb-2">Commentaire (Optionnel)</label>
                    <textarea name="comment" id="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-opacity-90 transition focus:outline-none focus:shadow-outline">
                        Soumettre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
