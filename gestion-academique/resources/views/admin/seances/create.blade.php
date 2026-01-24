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

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative mb-6">
                <strong class="font-bold">⚠️ Attention!</strong>
                <p class="text-sm mt-2">{{ session('warning') }}</p>
                
                @if (session('suggestions') && count(session('suggestions')) > 0)
                    <div class="mt-4 bg-white rounded p-3">
                        <p class="text-sm font-semibold mb-2">Salles recommandées:</p>
                        <ul class="space-y-1">
                            @foreach (session('suggestions') as $salle)
                                <li class="text-sm">
                                    <a href="{{ route('admin.seances.create') }}?salle_id={{ $salle['id'] }}" class="text-blue-600 hover:underline">
                                        Salle {{ $salle['numero'] }} (capacité: {{ $salle['capacite'] }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.seances.store') }}" class="mt-4">
                    @csrf
                    @foreach (old() as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="mt-2 bg-yellow-600 text-white px-4 py-2 rounded font-medium hover:bg-yellow-700 transition-colors">
                        Continuer quand même
                    </button>
                </form>
            </div>
        @endif

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

                    <!-- Niveau -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_id">Niveau</label>
                        <select id="groupe_id" name="groupe_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('groupe_id') border-red-500 @enderror" required>
                            <option value="">Sélectionner un niveau</option>
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

                    <!-- Semestre -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="semester">Semestre</label>
                        <select id="semester" name="semester" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('semester') border-red-500 @enderror">
                            <option value="">-- Aucun --</option>
                            <option value="S1" {{ old('semester') == 'S1' ? 'selected' : '' }}>Semestre 1 (S1)</option>
                            <option value="S2" {{ old('semester') == 'S2' ? 'selected' : '' }}>Semestre 2 (S2)</option>
                        </select>
                        @error('semester')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divisions de Groupe -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Divisions concernées</label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="group_divisions[]" value="G1" {{ (is_array(old('group_divisions')) && in_array('G1', old('group_divisions'))) ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-gray-700">Groupe 1 (G1)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="group_divisions[]" value="G2" {{ (is_array(old('group_divisions')) && in_array('G2', old('group_divisions'))) ? 'checked' : '' }} class="w-4 h-4">
                                <span class="text-gray-700">Groupe 2 (G2)</span>
                            </label>
                        </div>
                        @error('group_divisions')
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
