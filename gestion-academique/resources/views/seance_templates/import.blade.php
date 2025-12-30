@extends('layouts.app')

@section('title', 'Importer un emploi du temps')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Importer un emploi du temps</h1>
        <a href="{{ route('seance-templates.index') }}" class="text-gray-600 hover:text-gray-800">Retour</a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Format du fichier CSV</h2>
        <p class="mb-4 text-gray-700">Le fichier doit contenir les colonnes suivantes (dans cet ordre exact):</p>
        <div class="bg-gray-50 p-4 rounded border border-gray-200 font-mono text-sm">
            Jour, Heure Début, Heure Fin, UE Code, UE Nom, Filière, Groupe, Salle, Enseignant, Commentaire
        </div>
        <p class="mt-4 text-gray-700"><strong>Exemple:</strong></p>
        <div class="bg-gray-50 p-4 rounded border border-gray-200 font-mono text-sm">
            Lundi,08:00,11:00,INFO101,Informatique,Génie Informatique,Groupe 1,Salle 101,Dupont Martin,
        </div>
        <p class="mt-4 text-gray-700"><strong>Notes:</strong></p>
        <ul class="list-disc list-inside text-gray-700">
            <li>Jour: Lundi, Mardi, Mercredi, Jeudi, Vendredi, Samedi</li>
            <li>Heures: Format HH:MM (24h)</li>
            <li>UE Code: Doit correspondre à un code UE existant</li>
            <li>Filière, Groupe, Salle, Enseignant: Optionnels (laissez vide si non applicable)</li>
            <li>Enseignant: Format "Prénom Nom"</li>
        </ul>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('seance-templates.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="filiere_id">Filière <span class="text-red-500">*</span></label>
                    <select id="filiere_id" name="filiere_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('filiere_id') border-red-500 @enderror">
                        <option value="">-- Sélectionner une filière --</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">La filière est obligatoire</p>
                    @error('filiere_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_id">Groupe / Niveau <span class="text-red-500">*</span></label>
                    <select id="groupe_id" name="groupe_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('groupe_id') border-red-500 @enderror">
                        <option value="">-- Sélectionner un groupe --</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}" data-filiere-id="{{ $groupe->filiere_id }}" {{ old('groupe_id') == $groupe->id ? 'selected' : '' }}>{{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Le groupe est obligatoire</p>
                    @error('groupe_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="file">Sélectionner un fichier CSV</label>
                <input type="file" id="file" name="file" accept=".csv,.txt" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('file') border-red-500 @enderror">
                @error('file')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Importer
                </button>
                <a href="{{ route('seance-templates.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
        <h3 class="text-lg font-bold text-blue-900 mb-2">Conseil:</h3>
        <p class="text-blue-800">
            Commencez par <strong>exporter</strong> votre emploi du temps actuel pour voir le format exact. 
            Vous pourrez alors le modifier facilement dans Excel ou un autre tableur, puis le réimporter.
            <br><br>
            Si vous importez pour une filière/groupe spécifique, sélectionnez-la ci-dessus pour remplacer les valeurs du CSV.
        </p>
    </div>
</div>

<script>
    document.getElementById('filiere_id').addEventListener('change', function() {
        const selectedFiliereId = this.value;
        const groupeSelect = document.getElementById('groupe_id');
        const groupeOptions = groupeSelect.querySelectorAll('option[data-filiere-id]');

        // Afficher/masquer les options de groupe selon la filière sélectionnée
        groupeOptions.forEach(option => {
            if (selectedFiliereId === '' || option.dataset.filiereId === selectedFiliereId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });

        // Réinitialiser la sélection du groupe
        groupeSelect.value = '';
    });

    // Initialiser l'affichage des groupes au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const event = new Event('change');
        document.getElementById('filiere_id').dispatchEvent(event);
    });
</script>
@endsection
