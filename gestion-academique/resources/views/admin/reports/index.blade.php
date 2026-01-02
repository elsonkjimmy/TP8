@extends('layouts.app')

@section('title', 'Rapports de Séance')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Rapports de Séance</h1>

    <form method="GET" class="mb-4">
        <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-2 space-y-2 sm:space-y-0">
            <select name="filiere_id" class="border rounded px-2 py-1 w-full sm:w-auto">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $f)
                    <option value="{{ $f->id }}" @if(request('filiere_id') == $f->id) selected @endif>{{ $f->nom }}</option>
                @endforeach
            </select>
            <select name="groupe_id" class="border rounded px-2 py-1 w-full sm:w-auto">
                <option value="">Tous les groupes</option>
                @foreach($groupes as $g)
                    <option value="{{ $g->id }}" @if(request('groupe_id') == $g->id) selected @endif>{{ $g->nom }}</option>
                @endforeach
            </select>

            <div class="w-full sm:w-auto">
                <button class="w-full sm:w-auto bg-primary text-white px-3 py-2 rounded">Filtrer</button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded shadow">
        <!-- Desktop / Table (md+) -->
        <div class="hidden md:block overflow-x-auto">
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

        <!-- Mobile: Cards -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($reports as $r)
                @php
                    // reuse teacherLabel logic for mobile
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
                <div class="border rounded-lg p-3 bg-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($r->seance->jour)->format('d/m/Y') }}</p>
                            <h3 class="font-semibold text-lg">{{ $r->seance->ue->nom ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-600">{{ $r->seance->groupe->filiere->nom ?? '' }} / {{ $r->seance->groupe->nom ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium">{{ ucfirst($r->status) }}</p>
                            <a href="{{ route('admin.reports.show', $r->id) }}" class="text-blue-600 text-sm">Voir</a>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-700">Enseignant : {{ $teacherLabel }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-4">{{ $reports->appends(request()->query())->links() }}</div>
</div>
@endsection
