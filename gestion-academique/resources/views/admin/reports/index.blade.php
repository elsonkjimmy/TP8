@extends('layouts.app')

@section('title', 'Rapports de Séance')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Rapports de Séance</h1>

    <form method="GET" class="mb-4">
        <div class="flex space-x-2">
            <select name="filiere_id" class="border rounded px-2 py-1">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->id }}" @if(request('filiere_id') == $f->id) selected @endif>{{ $f->nom }}</option>
                @endforeach
            </select>

            <select name="groupe_id" class="border rounded px-2 py-1">
                <option value="">Tous les groupes</option>
                @foreach($groupes as $g)
                    <option value="{{ $g->id }}" @if(request('groupe_id') == $g->id) selected @endif>{{ $g->nom }}</option>
                @endforeach
            </select>

            <button class="bg-primary text-white px-3 py-1 rounded">Filtrer</button>
        </div>
    </form>

    <div class="bg-white rounded shadow">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">UE</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Enseignant</th>
                    <th class="px-4 py-2 text-left">Filière / Groupe</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $r->seance->ue->nom ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($r->seance->jour)->format('d/m/Y') }}</td>
                        @php
                            $reportAuthor = $r->enseignant;
                            $seanceTeacher = $r->seance->enseignant ?? null;
                            if ($reportAuthor) {
                                $teacherLabel = trim(($reportAuthor->first_name ?? '') . ' ' . ($reportAuthor->last_name ?? '')) ?: 'N/A';
                            } elseif ($seanceTeacher) {
                                $teacherLabel = trim(($seanceTeacher->first_name ?? '') . ' ' . ($seanceTeacher->last_name ?? '')) ?: 'N/A';
                            } else {
                                $teacherLabel = 'N/A';
                            }
                        @endphp
                        <td class="px-4 py-2">{{ $teacherLabel }}</td>
                        <td class="px-4 py-2">{{ $r->seance->groupe->filiere->nom ?? '' }} / {{ $r->seance->groupe->nom ?? '' }}</td>
                        <td class="px-4 py-2">{{ ucfirst($r->status) }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.reports.show', $r->id) }}" class="text-blue-600">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reports->appends(request()->query())->links() }}</div>
</div>
@endsection
