@extends('layouts.app')

@section('title', 'Nouvelle demande de modification')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('teacher.demandes.index') }}" class="text-primary hover:text-accent">
                ← Retour
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2">Demande de Modification</h1>
            <p class="text-gray-600 mt-2">Soumettez une demande pour modifier une séance</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <form action="{{ route('teacher.demandes.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Sélection de la séance -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Sélectionner une séance*</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Seances datées -->
                        @if($seances->count() > 0)
                            <div>
                                <h3 class="font-semibold text-gray-700 mb-2">Séances programmées</h3>
                                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3">
                                    @foreach($seances as $seance)
                                        <label class="flex items-start p-2 hover:bg-gray-50 rounded cursor-pointer">
                                            <input type="radio" name="seance_id" value="{{ $seance->id }}" 
                                                   class="mt-1 mr-3" {{ old('seance_id') == $seance->id ? 'checked' : '' }}>
                                            <div class="text-sm flex-1">
                                                <p class="font-medium">{{ $seance->ue->nom ?? 'UE' }}</p>
                                                <p class="text-xs text-gray-600">{{ $seance->groupe->nom ?? 'Groupe' }} - Salle {{ $seance->salle->numero ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($seance->jour)->format('d/m/Y') }} {{ $seance->heure_debut }} - {{ $seance->heure_fin }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Templates -->
                        @if($templates->count() > 0)
                            <div>
                                <h3 class="font-semibold text-gray-700 mb-2">Modèles récurrents</h3>
                                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3">
                                    @foreach($templates as $template)
                                        <label class="flex items-start p-2 hover:bg-gray-50 rounded cursor-pointer">
                                            <input type="radio" name="seance_template_id" value="{{ $template->id }}" 
                                                   class="mt-1 mr-3" {{ old('seance_template_id') == $template->id ? 'checked' : '' }}>
                                            <div class="text-sm flex-1">
                                                <p class="font-medium">{{ $template->ue->nom ?? 'UE' }}</p>
                                                <p class="text-xs text-gray-600">{{ $template->groupe->nom ?? 'Groupe' }} - Salle {{ $template->salle->numero ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-600">{{ ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][$template->day_of_week - 1] ?? 'Jour' }} {{ $template->start_time }} - {{ $template->end_time }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    @error('seance')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type de demande -->
                <div>
                    <label for="type_demande" class="block text-sm font-bold text-gray-700 mb-2">Type de demande*</label>
                    <select name="type_demande" id="type_demande" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('type_demande') border-red-500 @enderror" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="créneau" {{ old('type_demande') == 'créneau' ? 'selected' : '' }}>Changement de créneau horaire</option>
                        <option value="salle" {{ old('type_demande') == 'salle' ? 'selected' : '' }}>Changement de salle</option>
                        <option value="enseignant" {{ old('type_demande') == 'enseignant' ? 'selected' : '' }}>Changement d'enseignant</option>
                        <option value="autre" {{ old('type_demande') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_demande')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description*</label>
                    <textarea name="description" id="description" rows="6" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('description') border-red-500 @enderror"
                              placeholder="Décrivez votre demande en détail..."
                              required>{{ old('description') }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Minimum 10 caractères</p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons -->
                <div class="flex gap-3 pt-4 border-t">
                    <a href="{{ route('teacher.demandes.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white font-medium rounded-lg hover:bg-accent transition">
                        Soumettre la demande
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-bold text-blue-900 mb-2">💡 Info</h3>
            <ul class="text-sm text-blue-800 space-y-1 list-disc ml-5">
                <li>Votre demande sera examinée par un administrateur</li>
                <li>Vous recevrez une réponse dans votre tableau de bord</li>
                <li>Soyez clair et précis dans votre description</li>
            </ul>
        </div>
    </div>
@endsection
