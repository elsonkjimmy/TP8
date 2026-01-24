@extends('layouts.app')

@section('title', 'Modifier Template')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Modifier un template</h1>

    @if($errors->any())<div class="mb-4 text-red-600">{{ implode(', ', $errors->all()) }}</div>@endif

    <form action="{{ route('seance-templates.update', $seanceTemplate) }}" method="POST" class="grid grid-cols-1 gap-4">
        @csrf
        @method('PUT')
        <div>
            <label>Filière (optionnel)</label>
            <select name="filiere_id" class="w-full border p-2">
                <option value="">--Aucune--</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->id }}" {{ $seanceTemplate->filiere_id == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Groupe (optionnel)</label>
            <select name="groupe_id" class="w-full border p-2">
                <option value="">--Aucun--</option>
                @foreach($groupes as $g)
                    <option value="{{ $g->id }}" {{ $seanceTemplate->groupe_id == $g->id ? 'selected' : '' }}>{{ $g->nom }} ({{ $g->filiere->nom ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>UE</label>
            <select name="ue_id" required class="w-full border p-2">
                @foreach($ues as $ue)
                    <option value="{{ $ue->id }}" {{ $seanceTemplate->ue_id == $ue->id ? 'selected' : '' }}>{{ $ue->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Salle (optionnel)</label>
            <select name="salle_id" class="w-full border p-2">
                <option value="">--Aucune--</option>
                @foreach($salles as $s)
                    <option value="{{ $s->id }}" {{ $seanceTemplate->salle_id == $s->id ? 'selected' : '' }}>{{ $s->numero }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Enseignant (optionnel)</label>
            <select name="enseignant_id" class="w-full border p-2">
                <option value="">--Aucun--</option>
                @foreach($enseignants as $e)
                    <option value="{{ $e->id }}" {{ $seanceTemplate->enseignant_id == $e->id ? 'selected' : '' }}>{{ $e->first_name }} {{ $e->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Jour</label>
            <select name="day_of_week" required class="w-full border p-2">
                <option value="1" {{ $seanceTemplate->day_of_week == 1 ? 'selected' : '' }}>Lundi</option>
                <option value="2" {{ $seanceTemplate->day_of_week == 2 ? 'selected' : '' }}>Mardi</option>
                <option value="3" {{ $seanceTemplate->day_of_week == 3 ? 'selected' : '' }}>Mercredi</option>
                <option value="4" {{ $seanceTemplate->day_of_week == 4 ? 'selected' : '' }}>Jeudi</option>
                <option value="5" {{ $seanceTemplate->day_of_week == 5 ? 'selected' : '' }}>Vendredi</option>
                <option value="6" {{ $seanceTemplate->day_of_week == 6 ? 'selected' : '' }}>Samedi</option>
            </select>
        </div>
        <div>
            <label>Semestre (optionnel)</label>
            <select name="semester" class="w-full border p-2">
                <option value="" {{ $seanceTemplate->semester ? '' : 'selected' }}>-- Aucun --</option>
                <option value="S1" {{ $seanceTemplate->semester == 'S1' ? 'selected' : '' }}>Semestre 1 (S1)</option>
                <option value="S2" {{ $seanceTemplate->semester == 'S2' ? 'selected' : '' }}>Semestre 2 (S2)</option>
            </select>
        </div>
        <div>
            <label>Divisions concernées</label>
            <div class="flex gap-4 mt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="group_divisions[]" value="G1" {{ (str_contains($seanceTemplate->group_divisions ?? '', 'G1')) ? 'checked' : '' }} class="w-4 h-4">
                    <span>Groupe 1 (G1)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="group_divisions[]" value="G2" {{ (str_contains($seanceTemplate->group_divisions ?? '', 'G2')) ? 'checked' : '' }} class="w-4 h-4">
                    <span>Groupe 2 (G2)</span>
                </label>
            </div>
        </div>
        <div>
            <div class="flex items-center justify-between mb-2">
                <label>Heure début</label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="checkbox" id="unlock_time" class="w-4 h-4">
                    <span>Déverrouiller édition</span>
                </label>
            </div>
            <input type="time" id="start_time" name="start_time_display" value="{{ is_object($seanceTemplate->start_time) ? $seanceTemplate->start_time->format('H:i') : $seanceTemplate->start_time }}" disabled class="w-full border p-2 bg-gray-100 cursor-not-allowed">
            <input type="hidden" id="start_time_hidden" name="start_time" value="{{ is_object($seanceTemplate->start_time) ? $seanceTemplate->start_time->format('H:i') : $seanceTemplate->start_time }}">
        </div>
        <div>
            <label>Heure fin</label>
            <input type="time" id="end_time" name="end_time_display" value="{{ is_object($seanceTemplate->end_time) ? $seanceTemplate->end_time->format('H:i') : $seanceTemplate->end_time }}" disabled class="w-full border p-2 bg-gray-100 cursor-not-allowed">
            <input type="hidden" id="end_time_hidden" name="end_time" value="{{ is_object($seanceTemplate->end_time) ? $seanceTemplate->end_time->format('H:i') : $seanceTemplate->end_time }}">
        </div>
        <div>
            <label>Commentaire</label>
            <textarea name="comment" class="w-full border p-2">{{ $seanceTemplate->comment }}</textarea>
        </div>

        <div>
            <button class="bg-primary text-white px-4 py-2 rounded">Enregistrer</button>
            <a href="{{ route('seance-templates.index') }}" class="ml-2 text-gray-600">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unlockCheckbox = document.getElementById('unlock_time');
        const startTimeDisplay = document.getElementById('start_time');
        const endTimeDisplay = document.getElementById('end_time');
        const startTimeHidden = document.getElementById('start_time_hidden');
        const endTimeHidden = document.getElementById('end_time_hidden');

        unlockCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // Enable editing - copy value to hidden field when changed
                startTimeDisplay.disabled = false;
                endTimeDisplay.disabled = false;
                startTimeDisplay.classList.remove('bg-gray-100', 'cursor-not-allowed');
                endTimeDisplay.classList.remove('bg-gray-100', 'cursor-not-allowed');
                
                // Update hidden fields as user types
                startTimeDisplay.addEventListener('input', function() {
                    startTimeHidden.value = this.value;
                });
                endTimeDisplay.addEventListener('input', function() {
                    endTimeHidden.value = this.value;
                });
            } else {
                // Disable editing - restore original values
                startTimeDisplay.disabled = true;
                endTimeDisplay.disabled = true;
                startTimeDisplay.classList.add('bg-gray-100', 'cursor-not-allowed');
                endTimeDisplay.classList.add('bg-gray-100', 'cursor-not-allowed');
                
                // Reset hidden fields to original values
                startTimeHidden.value = startTimeDisplay.value;
                endTimeHidden.value = endTimeDisplay.value;
            }
        });
    });
</script>
@endsection
