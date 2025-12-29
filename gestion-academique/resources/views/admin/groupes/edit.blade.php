@extends('layouts.app')

@section('title', 'Modifier un Groupe')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Modifier le Groupe: {{ $groupe->nom }}</h1>
            <a href="{{ route('admin.groupes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.groupes.update', $groupe->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">Nom du groupe</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $groupe->nom) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nom') border-red-500 @enderror" required>
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
                                <option value="{{ $filiere->id }}" {{ old('filiere_id', $groupe->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }} ({{ $filiere->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-save mr-2"></i>Mettre à jour le groupe
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
