@extends('layouts.app')

@section('title', 'Créer une Séance')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Créer une nouvelle Séance</h1>
            <a href="{{ route('admin.seances.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.seances.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- UE -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="ue_id">Unité d'Enseignement (UE)</label>
                        <select id="ue_id" name="ue_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('ue_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner une UE</option>
                            @foreach ($ues as $ue)
                                <option value="{{ $ue->id }}" {{ old('ue_id') == $ue->id ? 'selected' : '' }}>
                                    {{ $ue->nom }} ({{ $ue->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('ue_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jour -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="jour">Jour</label>
                        <input type="date" id="jour" name="jour" value="{{ old('jour') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('jour') border-red-500 @enderror" required>
                        @error('jour')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heure Début -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="heure_debut">Heure de début</label>
                        <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('heure_debut') border-red-500 @enderror" required>
                        @error('heure_debut')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heure Fin -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="heure_fin">Heure de fin</label>
                        <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('heure_fin') border-red-500 @enderror" required>
                        @error('heure_fin')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salle -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="salle_id">Salle</label>
                        <select id="salle_id" name="salle_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('salle_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner une salle</option>
                            @foreach ($salles as $salle)
                                <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->numero }} (Capacité: {{ $salle->capacite }})
                                </option>
                            @endforeach
                        </select>
                        @error('salle_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groupe -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_id">Groupe</label>
                        <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('groupe_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner un groupe</option>
                            @foreach ($groupes as $groupe)
                                <option value="{{ $groupe->id }}" {{ old('groupe_id') == $groupe->id ? 'selected' : '' }}>
                                    {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('groupe_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Enseignant -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="enseignant_id">Enseignant</label>
                        <select id="enseignant_id" name="enseignant_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('enseignant_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner un enseignant</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('enseignant_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('enseignant_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-save mr-2"></i>Créer la séance
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
