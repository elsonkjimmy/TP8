@extends('layouts.app')

@section('title', 'Tableau de bord Enseignant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-primary mb-6">Mon Tableau de bord Enseignant</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-x-auto mb-8">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border border-gray-300 bg-gray-100 px-4 py-3 text-left font-bold text-gray-700 w-32">Horaires</th>
                    @foreach (array_keys($timetableGrid) as $dayLabel)
                        @php
                            $dayIndex = array_search($dayLabel, array_keys($timetableGrid));
                            $dayDate = $monday->copy()->addDays($dayIndex)->toDateString();
                            $isToday = $dayDate === \Carbon\Carbon::now()->toDateString();
                        @endphp
                        <th class="border border-gray-300 {{ $isToday ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700' }} px-4 py-3 text-center font-bold w-32">
                            {{ $dayLabel }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $slotLabel => $slot)
                    <tr>
                        <td class="border border-gray-300 bg-gray-50 px-4 py-3 font-bold text-gray-700 text-center">
                            {{ $slotLabel }}
                        </td>
                        @foreach ($timetableGrid as $day => $slots)
                            <td class="border border-gray-300 px-4 py-3 align-top bg-white hover:bg-gray-50 transition-colors" style="min-height: 120px;">
                                @forelse ($slots[$slotLabel] as $seance)
                                    <div class="mb-2 p-3 rounded-lg bg-blue-100 text-blue-900 border border-blue-300 text-sm">
                                        <p class="font-bold">{{ $seance->ue->nom ?? 'N/A' }}</p>
                                        <p class="text-xs">{{ $seance->ue->code ?? 'N/A' }}</p>
                                        <p class="text-xs mt-1">Salle: {{ $seance->salle->numero ?? 'N/A' }}</p>
                                        <p class="text-xs">Groupe: {{ $seance->groupe->nom ?? 'N/A' }}</p>
                                        <p class="text-xs">Statut: 
                                            <span class="px-2 py-0.5 rounded text-white text-[10px] @if($seance->status=='completed') bg-green-600 @elseif($seance->status=='cancelled') bg-red-600 @elseif($seance->status=='no_fait') bg-red-700 @else bg-yellow-600 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $seance->status)) }}
                                            </span>
                                        </p>
                                        <div class="mt-2 flex gap-1 flex-wrap">
                                            {{-- Report actions --}}
                                            @php
                                                $canReport = false;
                                                $now = \Carbon\Carbon::now();
                                                $seanceEnd = \Carbon\Carbon::parse($seance->heure_fin);
                                                if ($now->greaterThanOrEqualTo($seanceEnd->copy()->subMinutes(30)) && $now->toDateString() === \Carbon\Carbon::parse($seance->jour)->toDateString()) {
                                                    $canReport = true;
                                                }
                                            @endphp

                                            @if(!$seance->rapportSeance && $canReport)
                                                <a href="{{ route('teacher.seances.reports.create', $seance->id) }}" class="text-xs bg-white text-blue-900 px-2 py-1 rounded hover:bg-blue-50"><i class="fas fa-file-alt"></i></a>
                                            @elseif($seance->rapportSeance)
                                                <a href="{{ route('teacher.reports.show', $seance->rapportSeance->id) }}" class="text-xs bg-white text-blue-900 px-2 py-1 rounded hover:bg-blue-50"><i class="fas fa-eye"></i></a>
                                            @endif

                                            {{-- Cancel --}}
                                            <form action="{{ action([App\Http\Controllers\Teacher\SeanceController::class, 'updateStatus'], ['seance' => $seance->id]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button class="text-xs bg-white text-red-600 px-2 py-1 rounded hover:bg-red-50"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-400 text-xs italic">-</p>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8 grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <h3 class="font-bold mb-3">Mes UE</h3>
            <div class="max-h-52 overflow-auto pr-2">
            <ul class="space-y-2">
                @forelse($ues as $ue)
                    <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div class="break-words">
                            <div class="font-semibold text-sm">{{ $ue->nom }}</div>
                            <div class="text-xs text-gray-600">{{ $ue->code }} — {{ $ue->filiere->nom ?? 'N/A' }}</div>
                        </div>
                        <div class="text-xs text-gray-600 mt-1 sm:mt-0">Progress: {{ $ue->progress ?? 0 }}%</div>
                    </li>
                @empty
                    <li class="text-gray-500">Aucune UE assignée.</li>
                @endforelse
            </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4">
            <h3 class="font-bold mb-3">Rapports récents</h3>
            <div class="max-h-60 overflow-auto pr-2">
            <ul class="space-y-2 text-sm">
                @forelse($reports as $report)
                    <li class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $report->seance->ue->nom ?? 'Séance' }}</div>
                            <div class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($report->seance->jour)->format('d/m/Y') }} — {{ ucfirst($report->status) }}</div>
                        </div>
                        <div>
                            <a href="{{ route('teacher.reports.show', $report->id) }}" class="text-blue-600 text-xs">Voir</a>
                        </div>
                    </li>
                @empty
                    <li class="text-gray-500">Aucun rapport récent.</li>
                @endforelse
            </ul>
            </div>
        </div>
    </div>

        <div class="mt-8 bg-white rounded-xl shadow-lg p-4">
        <h3 class="font-bold mb-3">Gestion des délégués (par modèle de séance)</h3>

        @php
            $dayNames = [1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi',6=>'Samedi'];
            $grouped = $templates->groupBy('day_of_week');
        @endphp

        <div class="space-y-4">
            <div class="max-h-80 overflow-auto pr-2">
            @foreach($dayNames as $num => $label)
                @php $group = $grouped->get($num, collect()); @endphp
                <div class="border rounded">
                    <button type="button" class="w-full text-left px-4 py-3 bg-gray-100 flex items-center justify-between" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <div class="font-semibold">{{ $label }} <span class="text-sm text-gray-600">({{ $group->count() }} créneau(s))</span></div>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="p-3 hidden">
                        @if($group->isEmpty())
                            <div class="text-gray-500 text-sm">Aucun créneau pour ce jour.</div>
                        @else
                            <div class="space-y-3">
                                @foreach($group as $template)
                                    <div class="p-3 border rounded flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div class="flex-1">
                                            <div class="font-medium">{{ $template->ue->nom ?? 'UE' }} — {{ $template->groupe->nom ?? '' }} <span class="text-xs text-gray-500">({{ $template->start_time }} - {{ $template->end_time }})</span></div>
                                            <div class="text-xs text-gray-600">Délégués: @if($template->delegates->isEmpty()) <span class="text-gray-400">(aucun)</span> @else {{ $template->delegates->map(fn($d)=>trim(($d->first_name ?? '') . ' ' . ($d->last_name ?? '')))->join(', ') }} @endif</div>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <form action="{{ route('teacher.seance-templates.delegates.store', $template->id) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="_method" value="POST">
                                                <select name="delegate_id" class="text-sm border rounded px-2 py-2 w-full sm:w-56">
                                                    <option value="">Ajouter délégué...</option>
                                                    @foreach($delegates as $d)
                                                        <option value="{{ $d->id }}">{{ trim(($d->first_name ?? '') . ' ' . ($d->last_name ?? '')) }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="ml-0 sm:ml-2 w-full sm:w-auto text-sm bg-primary text-white px-3 py-2 rounded">Ajouter</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
    <div class="mt-6 grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <h3 class="font-bold mb-3">Rapports en attente de validation</h3>
            <div class="max-h-56 overflow-auto pr-2">
            <ul class="space-y-2 text-sm">
                @forelse($pendingReports as $pr)
                    <li class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $pr->seance->ue->nom ?? 'Séance' }} — {{ \Carbon\Carbon::parse($pr->seance->jour)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-600">Soumis par: {{ $pr->enseignant->name ?? $pr->filled_by_id }}</div>
                        </div>
                        <div>
                            <a href="{{ route('teacher.reports.show', $pr->id) }}" class="text-blue-600 text-xs">Voir / Valider</a>
                        </div>
                    </li>
                @empty
                    <li class="text-gray-500">Aucun rapport en attente.</li>
                @endforelse
            </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4">
            <h3 class="font-bold mb-3">Notifications</h3>
            <div class="max-h-56 overflow-auto pr-2">
            <ul class="space-y-2 text-sm">
                @forelse($notifications as $note)
                    <li class="text-sm">{{ $note->contenu }} <span class="text-xs text-gray-500">({{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }})</span></li>
                @empty
                    <li class="text-gray-500">Aucune notification.</li>
                @endforelse
            </ul>
            </div>
        </div>
    </div>
</div>
@endsection
