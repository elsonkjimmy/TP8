@extends('layouts.app')

@section('title', 'Ajouter Template')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Ajouter un template</h1>

    @if($errors->any())<div class="mb-4 text-red-600">{{ implode(', ', $errors->all()) }}</div>@endif

    <form action="{{ route('seance-templates.store') }}" method="POST" class="grid grid-cols-1 gap-4">
        @csrf
        <div>
            <label>Filière (optionnel)</label>
            <select name="filiere_id" class="w-full border p-2">
                <option value="">--Aucune--</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->id }}">{{ $f->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Groupe (optionnel)</label>
            <select name="groupe_id" class="w-full border p-2">
                <option value="">--Aucun--</option>
                @foreach($groupes as $g)
                    <option value="{{ $g->id }}">{{ $g->nom }} ({{ $g->filiere->nom ?? '' }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>UE</label>
            <select name="ue_id" required class="w-full border p-2">
                @foreach($ues as $ue)
                    <option value="{{ $ue->id }}">{{ $ue->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Salle (optionnel)</label>
            <select name="salle_id" class="w-full border p-2">
                <option value="">--Aucune--</option>
                @foreach($salles as $s)
                    <option value="{{ $s->id }}">{{ $s->numero }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Enseignant (optionnel)</label>
            <select name="enseignant_id" class="w-full border p-2">
                <option value="">--Aucun--</option>
                @foreach($enseignants as $e)
                    <option value="{{ $e->id }}">{{ $e->first_name }} {{ $e->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Jour</label>
            <select name="day_of_week" required class="w-full border p-2">
                <option value="1">Lundi</option>
                <option value="2">Mardi</option>
                <option value="3">Mercredi</option>
                <option value="4">Jeudi</option>
                <option value="5">Vendredi</option>
                <option value="6">Samedi</option>
            </select>
        </div>
        <div>
            <label>Heure début</label>
            <input type="time" name="start_time" required class="w-full border p-2">
        </div>
        <div>
            <label>Heure fin</label>
            <input type="time" name="end_time" required class="w-full border p-2">
        </div>
        <div>
            <label>Commentaire</label>
            <textarea name="comment" class="w-full border p-2"></textarea>
        </div>

        <div>
            <button class="bg-primary text-white px-4 py-2 rounded">Créer</button>
            <a href="{{ route('seance-templates.index') }}" class="ml-2 text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
