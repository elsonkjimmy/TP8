@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Tableau de bord Délégué</h1>
        </div>

        <div class="bg-white rounded-xl shadow p-4 mb-6">
            <h2 class="font-semibold mb-3">Séances assignées cette semaine</h2>
            @if($seances->isEmpty())
                <p class="text-gray-500">Aucune séance assignée cette semaine.</p>
            @else
                <ul class="space-y-2">
                    @foreach($seances as $s)
                        <li class="flex justify-between items-center border rounded p-3">
                            <div>
                                <div class="font-medium">{{ $s->ue->nom ?? 'N/A' }} — {{ $s->groupe->nom ?? '' }}</div>
                                <div class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($s->jour)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($s->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->heure_fin)->format('H:i') }}</div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(!$s->rapportSeance)
                                    <a href="{{ route('delegate.seances.reports.create', $s->id) }}" class="text-blue-600 text-sm">Créer rapport</a>
                                @else
                                    <a href="{{ route('delegate.reports.show', $s->rapportSeance->id) }}" class="text-purple-600 text-sm">Voir rapport</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="font-semibold mb-3">Mes brouillons</h2>
            @if($draftReports->isEmpty())
                <p class="text-gray-500">Aucun brouillon pour le moment.</p>
            @else
                <ul class="space-y-2">
                    @foreach($draftReports as $r)
                        <li class="flex justify-between items-center border rounded p-3">
                            <div>
                                <div class="font-medium">{{ $r->seance->ue->nom ?? 'Séance' }}</div>
                                <div class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($r->seance->jour)->format('d/m/Y') }}</div>
                            </div>
                            <div>
                                <a href="{{ route('delegate.reports.show', $r->id) }}" class="text-blue-600 text-sm">Continuer</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
