@extends('layouts.app')

@section('title', 'Modifier une UE')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Modifier l'UE: {{ $ue->nom }}</h1>
            <a href="{{ route('admin.ues.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.ues.update', $ue->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Code -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="code">Code de l'UE</label>
                        <input type="text" id="code" name="code" value="{{ old('code', $ue->code) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror" required>
                        @error('code')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">Nom de l'UE</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $ue->nom) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nom') border-red-500 @enderror" required>
                        @error('nom')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Filière -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="filiere_id">Filière</label>
                        <select id="filiere_id" name="filiere_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('filiere_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner une filière</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ old('filiere_id', $ue->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }} ({{ $filiere->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groupe -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_id">Groupe (Niveau)</label>
                        <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('groupe_id') border-red-500 @enderror">
                            <option value="">Sélectionner un groupe (optionnel)</option>
                            @foreach ($groupes as $groupe)
                                <option value="{{ $groupe->id }}" {{ old('groupe_id', $ue->groupe_id) == $groupe->id ? 'selected' : '' }}>
                                    {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('groupe_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Enseignant -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="enseignant_id">Enseignant Responsable</label>
                        <select id="enseignant_id" name="enseignant_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('enseignant_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner un enseignant</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('enseignant_id', $ue->enseignant_id) == $teacher->id ? 'selected' : '' }}>
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
                        <i class="fas fa-save mr-2"></i>Mettre à jour l'UE
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
