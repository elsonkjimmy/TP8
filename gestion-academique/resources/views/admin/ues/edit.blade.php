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
                                <option value="{{ $groupe->id }}" data-filiere-id="{{ $groupe->filiere_id }}" {{ old('groupe_id', $ue->groupe_id) == $groupe->id ? 'selected' : '' }}>
                                    {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        @error('groupe_id')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                        <p class="text-red-500 text-xs italic mt-1" id="groupe-warning" style="display: none;">Le groupe doit appartenir à la filière sélectionnée</p>
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
                <!-- Server-rendered chapters container (fallback if JS insertion fails) -->
                <div id="chapters_container" class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    <h3 class="text-lg font-bold mb-3">Chapitres de l'UE</h3>
                    <div id="chapters_list" class="space-y-2"></div>
                    <div class="mt-3">
                        <button type="button" id="add_chapter" class="bg-green-600 text-white px-3 py-2 rounded">Ajouter un chapitre</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Validation du groupe selon la filière
        const filiereSelect = document.getElementById('filiere_id');
        const groupeSelect = document.getElementById('groupe_id');
        const groupeWarning = document.getElementById('groupe-warning');

        function updateGroupeOptions() {
            const selectedFiliereId = filiereSelect.value;
            groupeWarning.style.display = 'none';
            groupeSelect.classList.remove('border-red-500');

            // Afficher/masquer les groupes selon la filière
            Array.from(groupeSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const groupeFiliereId = option.dataset.filiereId;
                    option.style.display = (groupeFiliereId == selectedFiliereId) ? 'block' : 'none';
                }
            });

            // Réinitialiser le groupe si sélectionné n'est pas valide
            if (groupeSelect.value && !Array.from(groupeSelect.options).find(o => o.value === groupeSelect.value && o.style.display !== 'none')) {
                groupeSelect.value = '';
            }
        }

        filiereSelect.addEventListener('change', updateGroupeOptions);

        // Validation lors de la soumission
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedFiliereId = filiereSelect.value;
            const selectedGroupeId = groupeSelect.value;

            if (selectedGroupeId) {
                const selectedOption = groupeSelect.querySelector(`option[value="${selectedGroupeId}"]`);
                if (selectedOption.dataset.filiereId != selectedFiliereId) {
                    e.preventDefault();
                    groupeWarning.style.display = 'block';
                    groupeSelect.classList.add('border-red-500');
                    return false;
                }
            }
        });

        // Initialiser au chargement
        updateGroupeOptions();

        (function(){
            const container = document.getElementById('chapters_container');
            const list = container.querySelector('#chapters_list');

            function bindRemove(btn) {
                btn.addEventListener('click', function(){
                    const row = this.closest('div.flex');
                    if (row) row.remove();
                });
            }

            document.getElementById('add_chapter').addEventListener('click', function(){
                const row = document.createElement('div');
                row.className = 'flex gap-2 items-center';
                row.innerHTML = `<input type="text" name="chapters[]" class="flex-1 border rounded px-3 py-2" placeholder="Titre du chapitre"><button type="button" class="btn-remove text-red-600 px-3 py-1">Supprimer</button>`;
                list.appendChild(row);
                bindRemove(row.querySelector('.btn-remove'));
            });

            document.querySelectorAll('.btn-remove').forEach(bindRemove);

            // Prefill from server-side data
            const chapters = @json(old('chapters', $ue->chapters->pluck('title')->toArray()));
            if (Array.isArray(chapters) && chapters.length > 0) {
                list.innerHTML = '';
                chapters.forEach(title => {
                    const row = document.createElement('div');
                    row.className = 'flex gap-2 items-center';
                    row.innerHTML = `<input type="text" name="chapters[]" value="${(title||'').replace(/"/g,'&quot;')}" class="flex-1 border rounded px-3 py-2" placeholder="Titre du chapitre"><button type="button" class="btn-remove text-red-600 px-3 py-1">Supprimer</button>`;
                    list.appendChild(row);
                    bindRemove(row.querySelector('.btn-remove'));
                });
            }
        })();
    </script>
@endpush
