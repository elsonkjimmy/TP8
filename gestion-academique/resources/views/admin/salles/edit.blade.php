@extends('layouts.app')

@section('title', 'Modifier une Salle')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Modifier la Salle: {{ $salle->numero }}</h1>
            <a href="{{ route('admin.salles.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.salles.update', $salle->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Numéro -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="numero">Numéro de la salle</label>
                        <input type="text" id="numero" name="numero" value="{{ old('numero', $salle->numero) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('numero') border-red-500 @enderror" required>
                        @error('numero')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Capacité -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="capacite">Capacité</label>
                        <input type="number" id="capacite" name="capacite" value="{{ old('capacite', $salle->capacite) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('capacite') border-red-500 @enderror" required min="1">
                        @error('capacite')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-save mr-2"></i>Mettre à jour la salle
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
