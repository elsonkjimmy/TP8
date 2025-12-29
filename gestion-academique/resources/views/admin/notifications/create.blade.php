@extends('layouts.app')

@section('title', 'Envoyer une Notification')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Envoyer une nouvelle Notification</h1>
            <a href="{{ route('admin.notifications.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
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
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="recipient_type">Type de Destinataire</label>
                    <select id="recipient_type" name="recipient_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('recipient_type') border-red-500 @enderror" required>
                        <option value="">Sélectionner un type</option>
                        <option value="user" {{ old('recipient_type') == 'user' ? 'selected' : '' }}>Utilisateur (Enseignant/Délégué)</option>
                        <option value="filiere" {{ old('recipient_type') == 'filiere' ? 'selected' : '' }}>Filière</option>
                        <option value="groupe" {{ old('recipient_type') == 'groupe' ? 'selected' : '' }}>Groupe</option>
                    </select>
                    @error('recipient_type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4" id="recipient_id_container" style="display: {{ old('recipient_type') ? 'block' : 'none' }};">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="recipient_id">Destinataire</label>
                    <select id="recipient_id" name="recipient_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('recipient_id') border-red-500 @enderror" required>
                        <option value="">Sélectionner un destinataire</option>
                        {{-- Options will be dynamically loaded by JavaScript --}}
                    </select>
                    @error('recipient_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contenu">Contenu de la Notification</label>
                    <textarea id="contenu" name="contenu" rows="8" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('contenu') border-red-500 @enderror" required>{{ old('contenu') }}</textarea>
                    @error('contenu')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer la Notification
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const recipientTypeSelect = document.getElementById('recipient_type');
            const recipientIdContainer = document.getElementById('recipient_id_container');
            const recipientIdSelect = document.getElementById('recipient_id');

            const filieres = @json($filieres);
            const groupes = @json($groupes);
            const teachersAndDelegates = @json($teachersAndDelegates);

            function updateRecipientOptions() {
                recipientIdSelect.innerHTML = '<option value="">Sélectionner un destinataire</option>';
                const selectedType = recipientTypeSelect.value;

                if (selectedType === 'filiere') {
                    filieres.forEach(filiere => {
                        const option = document.createElement('option');
                        option.value = filiere.id;
                        option.textContent = filiere.nom + ' (' + filiere.code + ')';
                        if ({{ old('recipient_id', 'null') }} == filiere.id) {
                            option.selected = true;
                        }
                        recipientIdSelect.appendChild(option);
                    });
                    recipientIdContainer.style.display = 'block';
                } else if (selectedType === 'groupe') {
                    groupes.forEach(groupe => {
                        const option = document.createElement('option');
                        option.value = groupe.id;
                        option.textContent = groupe.nom + ' (' + (groupe.filiere ? groupe.filiere.nom : 'N/A') + ')';
                        if ({{ old('recipient_id', 'null') }} == groupe.id) {
                            option.selected = true;
                        }
                        recipientIdSelect.appendChild(option);
                    });
                    recipientIdContainer.style.display = 'block';
                } else if (selectedType === 'user') {
                    teachersAndDelegates.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.first_name + ' ' + user.last_name + ' (' + user.role + ')';
                        if ({{ old('recipient_id', 'null') }} == user.id) {
                            option.selected = true;
                        }
                        recipientIdSelect.appendChild(option);
                    });
                    recipientIdContainer.style.display = 'block';
                } else {
                    recipientIdContainer.style.display = 'none';
                }
            }

            recipientTypeSelect.addEventListener('change', updateRecipientOptions);

            // Initial call to set options if old input exists
            updateRecipientOptions();
        });
    </script>
@endsection
