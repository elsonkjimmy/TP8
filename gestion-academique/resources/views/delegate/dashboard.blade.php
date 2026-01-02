@extends('layouts.app')

@section('title', 'Tableau de bord Délégué')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-primary mb-6">Mon Tableau de bord Délégué</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($groupe)
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-bold text-primary mb-4">Mon Groupe : {{ $groupe->nom }} (Filière: {{ $groupe->filiere->nom ?? 'N/A' }})</h2>
                <p class="text-gray-700">Enseignant Responsable de la Filière : {{ $groupe->filiere->enseignantResponsable->first_name ?? 'N/A' }} {{ $groupe->filiere->enseignantResponsable->last_name ?? '' }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-4 bg-gray-100 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-primary">Séances de mon Groupe</h2>
                </div>
                <div class="table-responsive">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    UE
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Jour
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Heure
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Salle
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Enseignant
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Statut
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($seances as $seance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $seance->ue->nom ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($seance->jour)->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H:i') }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $seance->salle->numero ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            {{ $seance->enseignant->first_name ?? '' }} {{ $seance->enseignant->last_name ?? '' }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full 
                                                @if($seance->status == 'completed') bg-green-200 text-green-800
                                                @elseif($seance->status == 'cancelled') bg-red-200 text-red-800
                                                @else bg-yellow-200 text-yellow-800 @endif"></span>
                                            <span class="relative">{{ ucfirst($seance->status) }}</span>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                        Aucune séance prévue pour votre groupe.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-4 bg-gray-100 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-primary">Rapports de Séance à Valider</h2>
                </div>
                <div class="table-responsive">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    UE
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Jour
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Enseignant
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportsToValidate as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $report->seance->ue->nom ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($report->seance->jour)->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            {{ $report->seance->enseignant->first_name ?? '' }} {{ $report->seance->enseignant->last_name ?? '' }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full 
                                                @if($report->statut == 'approved') bg-green-200 text-green-800
                                                @elseif($report->statut == 'rejected') bg-red-200 text-red-800
                                                @else bg-yellow-200 text-yellow-800 @endif"></span>
                                            <span class="relative">{{ ucfirst($report->statut) }}</span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('teacher.reports.show', $report->id) }}" class="text-blue-600 hover:text-blue-900" title="Voir le rapport">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('delegate.reports.updateStatus', $report->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="statut" value="approved">
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Approuver le rapport">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('delegate.reports.updateStatus', $report->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="statut" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Rejeter le rapport">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                        Aucun rapport à valider pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-6 text-center text-gray-600">
                <p class="text-lg">Vous n'êtes pas assigné à un groupe. Veuillez contacter l'administration.</p>
            </div>
        @endif
    </div>
@endsection