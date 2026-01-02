@extends('layouts.app')

@section('title', 'Générer des Séances')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Générer des Séances</h1>
        <a href="{{ route('admin.seances.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    @if (session('alert'))
        <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg">
            <strong>Attention:</strong> {{ session('alert') }}
            <br><br>
                @php $genOption = session('option', old('option', 'template')); @endphp
                <form method="POST" action="{{ $genOption === 'template' ? route('admin.seances.generate-from-template') : route('admin.seances.generate-from-import') }}" class="inline">
                @csrf
                <input type="hidden" name="confirm" value="1">
                @foreach (request()->all() as $key => $value)
                    @if ($key !== '_token')
                        <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? json_encode($value) : $value }}">
                    @endif
                @endforeach
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-yellow-700 transition-colors">
                    Confirmer et générer
                </button>
            </form>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- OPTION A: Utiliser un template existant -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Option A: Emploi du temps existant</h2>
            </div>

            <form action="{{ route('admin.seances.generate-from-template') }}" method="POST" id="form-option-a">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="filiere_a">Filière <span class="text-red-500">*</span></label>
                    <select id="filiere_a" name="filiere_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_a">Groupe <span class="text-red-500">*</span></label>
                    <select id="groupe_a" name="groupe_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Sélectionner --</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="semester_a">Semestre <span class="text-red-500">*</span></label>
                    <select id="semester_a" name="target_semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="S1">Semestre 1 (S1)</option>
                        <option value="S2">Semestre 2 (S2)</option>
                    </select>
                </div>

                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800" id="templates_info">Sélectionnez filière, groupe et semestre pour voir les créneaux d'emploi du temps disponibles.</p>
                </div>

                <!-- Créneaux d'emploi du temps à sélectionner -->
                <div class="mb-4 bg-gray-50 border border-gray-300 rounded-lg p-4 hidden" id="templates_list_container">
                    <label class="block text-gray-700 text-sm font-bold mb-3 for="templates_select">Sélectionner un créneau horaire:</label>
                    <select id="templates_select" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 mb-3">
                        <option value="">-- Choisir un template --</option>
                    </select>
                    <div id="template_details" class="text-xs text-gray-600 bg-white p-2 rounded border border-gray-200 hidden">
                        <div><strong>UE:</strong> <span id="detail_ue"></span></div>
                        <div><strong>Horaires:</strong> <span id="detail_hours"></span></div>
                        <div><strong>Jour:</strong> <span id="detail_day"></span></div>
                        <div><strong>Semestre:</strong> <span id="detail_semester"></span></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date_a">Du <span class="text-red-500">*</span></label>
                        <input type="date" id="start_date_a" name="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date_a">Au <span class="text-red-500">*</span></label>
                        <input type="date" id="end_date_a" name="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors" id="btn_submit_a" disabled>
                    <i class="fas fa-play mr-2"></i>Générer
                </button>
            </form>

            <!-- DEBUG BOX - Détails de la génération -->
            <div class="mt-6 bg-gray-900 text-gray-100 rounded-lg overflow-hidden">
                <button type="button" class="w-full px-4 py-3 bg-gray-800 hover:bg-gray-700 text-left flex items-center justify-between font-mono text-sm transition-colors" id="debug_toggle_a">
                    <span><i class="fas fa-bug mr-2"></i><strong>DEBUG - Détails de génération</strong></span>
                    <i class="fas fa-chevron-down transition-transform" id="debug_icon_a"></i>
                </button>
                <div id="debug_box_a" class="hidden p-4 text-xs max-h-96 overflow-y-auto bg-gray-950">
                    <div id="debug_content_a">
                        <p class="text-gray-400">En attente de sélection...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- OPTION B: Importer un nouveau template -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-plus"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Option B: Importer nouvel emploi du temps</h2>
            </div>

            <form action="{{ route('admin.seances.generate-from-import') }}" method="POST" enctype="multipart/form-data" id="form-option-b">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="filiere_b">Filière <span class="text-red-500">*</span></label>
                    <select id="filiere_b" name="filiere_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="groupe_b">Groupe <span class="text-red-500">*</span></label>
                    <select id="groupe_b" name="groupe_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500" required>
                        <option value="">-- Sélectionner d'abord filière --</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="file_b">Fichier CSV <span class="text-red-500">*</span></label>
                    <input type="file" id="file_b" name="file" accept=".csv,.txt" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500" required>
                    <p class="text-xs text-gray-500 mt-1">Format: Jour, Heure Début, Heure Fin, Semestre, UE Code, UE Nom, Filière, Groupe, Salle, Enseignant, Commentaire</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date_b">Du <span class="text-red-500">*</span></label>
                        <input type="date" id="start_date_b" name="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date_b">Au <span class="text-red-500">*</span></label>
                        <input type="date" id="end_date_b" name="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="semester_b">Semestre <span class="text-red-500">*</span></label>
                    <select id="semester_b" name="target_semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-green-500" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="S1">Semestre 1 (S1)</option>
                        <option value="S2">Semestre 2 (S2)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Importer & Générer
                </button>
            </form>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-8">
        <h3 class="text-lg font-bold text-blue-900 mb-2">Conseils:</h3>
        <ul class="text-blue-800 text-sm list-disc list-inside space-y-1">
            <li><strong>Option A</strong> : Rapide si vous avez un template existant dans le système.</li>
            <li><strong>Option B</strong> : Plus flexible, permet d'importer un nouveau template et générer immédiatement.</li>
            <li>Les séances générées auront le semestre que vous sélectionnez (indépendant du template).</li>
            <li>Les séances existantes ne seront pas dupliquées.</li>
        </ul>
    </div>

    <!-- DEBUG: Tous les templates existants -->
    <div class="bg-red-50 border border-red-300 rounded-xl p-6 mt-8">
        <button type="button" class="w-full text-left flex items-center justify-between mb-4 hover:bg-red-100 p-2 rounded transition-colors" id="debug_all_templates_toggle">
            <h3 class="text-lg font-bold text-red-900">🔍 DEBUG: Tous les créneaux d'emploi du temps existants</h3>
            <i class="fas fa-chevron-down transition-transform" id="debug_all_templates_icon"></i>
        </button>
        
        <div id="debug_all_templates_content" class="hidden">
            @if($allTemplates->isEmpty())
                <div class="bg-red-100 border border-red-400 p-3 rounded text-red-700">
                    ⚠️ <strong>ATTENTION:</strong> Aucun créneau d'emploi du temps trouvé dans la base de données !
                </div>
            @else
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Sélectionner un créneau d'emploi du temps (<strong>{{ $allTemplates->count() }}</strong> créneaux):</label>
                    <select id="debug_templates_dropdown" class="w-full border-2 border-gray-400 rounded-lg px-3 py-2 focus:outline-none focus:border-red-500" size="12" style="min-height: 250px;">
                        <option value="">-- Choisir un template --</option>
                        @forelse($allTemplates as $t)
                            <option value="{{ $t['id'] }}" data-json="{{ htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8') }}">
                                {{ $t['display'] }}
                            </option>
                        @empty
                            <option disabled>Aucun template</option>
                        @endforelse
                    </select>
                </div>

                <div id="debug_template_details" class="hidden bg-gray-900 text-gray-100 rounded-lg p-4 font-mono text-xs max-h-64 overflow-y-auto">
                    <div><span class="text-blue-400">ID:</span> <span id="debug_detail_id"></span></div>
                    <div><span class="text-blue-400">Filière ID:</span> <span id="debug_detail_filiere_id"></span></div>
                    <div><span class="text-blue-400">Groupe ID:</span> <span id="debug_detail_groupe_id"></span></div>
                    <div><span class="text-blue-400">Semestre:</span> <span id="debug_detail_semester"></span></div>
                    <div><span class="text-blue-400">UE:</span> <span id="debug_detail_ue"></span></div>
                    <div><span class="text-blue-400">Jour:</span> <span id="debug_detail_jour"></span></div>
                    <div><span class="text-blue-400">Horaires:</span> <span id="debug_detail_horaires"></span></div>
                </div>

                <div class="mt-4 bg-gray-50 border border-gray-300 rounded p-3">
                    <p class="text-sm text-gray-700"><strong>Total:</strong> <span class="text-green-600 font-bold">{{ $allTemplates->count() }}</span> créneau(x) d'emploi du temps en base</p>
                    <p class="text-xs text-gray-500 mt-2">Cette liste affiche TOUS les créneaux d'emploi du temps existants. Si la liste de l'Option A est vide mais que vous voyez des créneaux ici, c'est un problème de filtrage par filière/groupe/semestre.</p>
                </div>
            @endif
        </div>
    </div>
</div>

    <script>
        // Pass data directly from PHP to JS
        const GROUPES_DATA = {!! json_encode($groupes->map(fn($g) => ['id' => $g->id, 'filiere_id' => $g->filiere_id, 'nom' => $g->nom])->toArray()) !!};
        const ALL_TEMPLATES_DATA = {!! json_encode($allTemplates) !!};

        // Utility: calculate days between dates (excluding Sundays)
        function calculateGenerationDays(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = [];
            const dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            
            for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                const dayOfWeek = d.getDay();
                if (dayOfWeek !== 0) { // skip Sunday (0)
                    days.push({
                        date: d.toISOString().split('T')[0],
                        day: dayNames[dayOfWeek],
                        dayNum: dayOfWeek
                    });
                }
            }
            return days;
        }

        function updateDebugBox(templates, startDate, endDate) {
            const debugBox = document.getElementById('debug_content_a');
            if (!debugBox) return;

            const days = calculateGenerationDays(startDate, endDate);
            const html = `
                <div class="space-y-3">
                    <div>
                        <span class="text-blue-400">📅 Plage horaire:</span>
                        <span class="text-yellow-300">${startDate}</span> → <span class="text-yellow-300">${endDate}</span>
                    </div>
                    <div>
                        <span class="text-blue-400">📊 Jours de génération (sans dimanche):</span>
                        <span class="text-green-400">${days.length} jour(s)</span>
                    </div>
                    <div class="mt-2 bg-gray-800 p-2 rounded">
                        ${days.map(d => `<div class="text-gray-300">• <span class="text-cyan-400">${d.date}</span> (${d.day})</div>`).join('')}
                    </div>
                    <div>
                        <span class="text-blue-400">📋 Créneaux d'emploi du temps trouvés:</span>
                        <span class="text-green-400">${templates.length}</span>
                    </div>
                    ${templates.length > 0 ? `
                    <div class="mt-2 bg-gray-800 p-2 rounded">
                        ${templates.map(t => `
                            <div class="text-gray-300 mb-2">
                                <span class="text-violet-400">▸ ${t.label}</span>
                                <div class="ml-4 text-xs text-gray-400">
                                    <div>UE: <span class="text-cyan-400">${t.ue_code}</span> - ${t.ue_nom}</div>
                                    <div>Horaires: <span class="text-yellow-300">${t.heure_debut}</span> - <span class="text-yellow-300">${t.heure_fin}</span></div>
                                    <div>Jours: <span class="text-green-400">${t.jours_semaine}</span></div>
                                    ${t.enseignant ? `<div>Enseignant: <span class="text-orange-400">${t.enseignant}</span></div>` : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="bg-green-900 border border-green-700 p-2 rounded mt-2">
                        <span class="text-green-300">✓ Génération: <strong>${templates.length}</strong> créneau(x) × <strong>${days.length}</strong> jour(s) = <strong>${templates.length * days.length}</strong> séance(s) à créer</span>
                    </div>
                    ` : `
                    <div class="bg-red-900 border border-red-700 p-2 rounded">
                        <span class="text-red-300">⚠ Aucun créneau d'emploi du temps trouvé pour ce semestre</span>
                    </div>
                    `}
                </div>
            `;
            debugBox.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // safe element refs
            const filiereA = document.getElementById('filiere_a');
            const groupeA = document.getElementById('groupe_a');
            const semesterA = document.getElementById('semester_a');
            const submitBtnA = document.getElementById('btn_submit_a');
            const startDateA = document.getElementById('start_date_a');
            const endDateA = document.getElementById('end_date_a');

            // Debug toggle
            const debugToggle = document.getElementById('debug_toggle_a');
            const debugBox = document.getElementById('debug_box_a');
            const debugIcon = document.getElementById('debug_icon_a');
            if (debugToggle) {
                debugToggle.addEventListener('click', function() {
                    debugBox.classList.toggle('hidden');
                    debugIcon.style.transform = debugBox.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            }

            function updateGroupes(filiereSelectId, groupeSelectId) {
                const filiereId = document.getElementById(filiereSelectId).value;
                const groupeSelect = document.getElementById(groupeSelectId);

                if (!groupeSelect) return;
                groupeSelect.innerHTML = '<option value="">-- Sélectionner --</option>';

                if (!filiereId) {
                    if (filiereSelectId === 'filiere_a') updateTemplateInfo();
                    return;
                }

                // Use global data
                GROUPES_DATA.forEach(groupe => {
                    if (String(groupe.filiere_id) === String(filiereId)) {
                        const option = document.createElement('option');
                        option.value = groupe.id;
                        option.text = groupe.nom;
                        groupeSelect.appendChild(option);
                    }
                });

                if (filiereSelectId === 'filiere_a') {
                    updateTemplateInfo();
                }
            }

            function updateTemplateInfo() {
                if (!filiereA || !groupeA || !semesterA || !submitBtnA) return;
                const filiereId = filiereA.value;
                const groupeId = groupeA.value;
                const semester = semesterA.value;
                const infoDiv = document.getElementById('templates_info');
                const listContainer = document.getElementById('templates_list_container');
                const templatesSelect = document.getElementById('templates_select');

                if (!filiereId || !groupeId || !semester) {
                    infoDiv.textContent = 'Sélectionnez filière, groupe et semestre pour voir les créneaux d\'emploi du temps disponibles.';
                    listContainer?.classList.add('hidden');
                    updateDebugBox([], startDateA?.value || '', endDateA?.value || '');
                    submitBtnA.disabled = true;
                    return;
                }

                fetch(`{{ route('admin.seances.get-templates-by-filtre') }}?filiere_id=${encodeURIComponent(filiereId)}&groupe_id=${encodeURIComponent(groupeId)}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        return response.json();
                    })
                    .then(templates => {
                        const matching = templates.filter(t => String(t.semester) === String(semester));
                        
                        if (matching.length === 0) {
                            infoDiv.innerHTML = '<strong class="text-red-600">❌ Aucun créneau d\'emploi du temps trouvé pour ce semestre.</strong>';
                            listContainer?.classList.add('hidden');
                            updateDebugBox([], startDateA?.value || '', endDateA?.value || '');
                            submitBtnA.disabled = true;
                        } else {
                            const list = matching.map(t => `<li>${t.label}</li>`).join('');
                            infoDiv.innerHTML = `<strong class="text-green-600">✓ ${matching.length} créneau(x) trouvé(s):</strong><ul class="mt-2 ml-4 text-sm">${list}</ul>`;
                            
                            templatesSelect.innerHTML = '<option value="">-- Choisir un template --</option>';
                            matching.forEach(t => {
                                const option = document.createElement('option');
                                option.value = t.id;
                                option.textContent = t.display_name;
                                option.dataset.json = JSON.stringify(t);
                                templatesSelect.appendChild(option);
                            });
                            
                            listContainer?.classList.remove('hidden');
                            updateDebugBox(matching, startDateA?.value || '', endDateA?.value || '');
                            submitBtnA.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error('Error fetching timetable slots:', err);
                        infoDiv.innerHTML = '<strong class="text-red-600">❌ Erreur lors de la récupération des créneaux d\'emploi du temps: ' + err.message + '</strong>';
                        listContainer?.classList.add('hidden');
                        updateDebugBox([], startDateA?.value || '', endDateA?.value || '');
                        submitBtnA.disabled = true;
                    });
            }

            // Listeners
            if (filiereA) filiereA.addEventListener('change', function() { updateGroupes('filiere_a', 'groupe_a'); });
            if (groupeA) groupeA.addEventListener('change', updateTemplateInfo);
            if (semesterA) semesterA.addEventListener('change', updateTemplateInfo);
            if (startDateA) startDateA.addEventListener('change', updateTemplateInfo);
            if (endDateA) endDateA.addEventListener('change', updateTemplateInfo);

            const templatesSelect = document.getElementById('templates_select');
            if (templatesSelect) {
                templatesSelect.addEventListener('change', function() {
                    const detailsDiv = document.getElementById('template_details');
                    if (!this.value) {
                        detailsDiv?.classList.add('hidden');
                        return;
                    }
                    
                    const option = this.options[this.selectedIndex];
                    const data = JSON.parse(option.dataset.json || '{}');
                    
                    document.getElementById('detail_ue').textContent = `${data.ue_code} - ${data.ue_nom}`;
                    document.getElementById('detail_hours').textContent = `${data.heure_debut} - ${data.heure_fin}`;
                    document.getElementById('detail_day').textContent = data.jours_semaine;
                    document.getElementById('detail_semester').textContent = data.semester;
                    
                    detailsDiv?.classList.remove('hidden');
                });
            }

            const filiereB = document.getElementById('filiere_b');
            if (filiereB) filiereB.addEventListener('change', function() { updateGroupes('filiere_b', 'groupe_b'); });

            document.querySelectorAll('input[type="date"]').forEach(input => {
                const today = new Date().toISOString().split('T')[0];
                if (!input.value) input.value = today;
            });

            if (filiereA && filiereA.value) {
                updateGroupes('filiere_a', 'groupe_a');
            }
            updateTemplateInfo();

            // DEBUG sections
            const debugToggleBtn = document.getElementById('debug_all_templates_toggle');
            const debugContent = document.getElementById('debug_all_templates_content');
            const debugDebugIcon = document.getElementById('debug_all_templates_icon');
            if (debugToggleBtn) {
                debugToggleBtn.addEventListener('click', function() {
                    debugContent.classList.toggle('hidden');
                    debugDebugIcon.style.transform = debugContent.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            }

            const debugDropdown = document.getElementById('debug_templates_dropdown');
            if (debugDropdown) {
                debugDropdown.addEventListener('change', function() {
                    const detailsDiv = document.getElementById('debug_template_details');
                    if (!this.value) {
                        detailsDiv?.classList.add('hidden');
                        return;
                    }
                    
                    const option = this.options[this.selectedIndex];
                    let data = {};
                    try {
                        const jsonStr = option.dataset.json || '{}';
                        data = JSON.parse(jsonStr);
                    } catch (e) {
                        console.error('Error parsing template data:', e);
                        detailsDiv?.classList.add('hidden');
                        return;
                    }
                    
                    document.getElementById('debug_detail_id').textContent = data.id;
                    document.getElementById('debug_detail_filiere_id').textContent = data.filiere_id;
                    document.getElementById('debug_detail_groupe_id').textContent = data.groupe_id;
                    document.getElementById('debug_detail_semester').textContent = data.semester;
                    document.getElementById('debug_detail_ue').textContent = `${data.ue_code} - ${data.ue_nom}`;
                    document.getElementById('debug_detail_jour').textContent = data.jour;
                    document.getElementById('debug_detail_horaires').textContent = `${data.heure_debut} - ${data.heure_fin}`;
                    
                    detailsDiv?.classList.remove('hidden');
                });
            }
        });
    </script>
@endsection
