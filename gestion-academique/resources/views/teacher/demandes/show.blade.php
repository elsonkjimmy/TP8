@extends('layouts.app')

@section('title', 'Détail de la demande')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('teacher.demandes.index') }}" class="text-primary hover:text-accent">
                ← Retour
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- En-tête -->
            <div class="flex items-start justify-between gap-4 mb-6 pb-6 border-b">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        @if($demande->seance)
                            {{ $demande->seance->ue->nom ?? 'UE' }}
                        @else
                            {{ $demande->seanceTemplate->ue->nom ?? 'UE' }}
                        @endif
                    </h1>
                    <p class="text-gray-600">Type: <span class="font-medium">{{ ucfirst($demande->type_demande) }}</span></p>
                </div>
                <span class="text-lg font-semibold px-4 py-2 rounded-full
                    @if($demande->status === 'soumis')
                        bg-yellow-100 text-yellow-800
                    @elseif($demande->status === 'accepté')
                        bg-green-100 text-green-800
                    @else
                        bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($demande->status) }}
                </span>
            </div>

            <!-- Informations de la séance -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h2 class="font-bold text-gray-900 mb-3">Informations de la séance</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">UE</p>
                        <p class="font-medium">
                            @if($demande->seance)
                                {{ $demande->seance->ue->nom ?? 'N/A' }}
                            @else
                                {{ $demande->seanceTemplate->ue->nom ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Niveau</p>
                        <p class="font-medium">
                            @if($demande->seance)
                                {{ $demande->seance->groupe->nom ?? 'N/A' }}
                            @else
                                {{ $demande->seanceTemplate->groupe->nom ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Salle</p>
                        <p class="font-medium">
                            @if($demande->seance)
                                {{ $demande->seance->salle->numero ?? 'N/A' }}
                            @else
                                {{ $demande->seanceTemplate->salle->numero ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Horaire</p>
                        <p class="font-medium">
                            @if($demande->seance)
                                {{ \Carbon\Carbon::parse($demande->seance->jour)->format('d/m/Y') }}
                                {{ \Carbon\Carbon::parse($demande->seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($demande->seance->heure_fin)->format('H:i') }}
                            @else
                                {{ ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][$demande->seanceTemplate->day_of_week - 1] ?? 'Jour' }}
                                {{ $demande->seanceTemplate->start_time }} - {{ $demande->seanceTemplate->end_time }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h2 class="font-bold text-gray-900 mb-2">Votre description</h2>
                <div class="p-4 bg-white border border-gray-300 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $demande->description }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    Soumise le {{ $demande->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>

            <!-- Réponse admin -->
            @if($demande->admin_response || $demande->admin_id)
                <div class="mb-6 p-4 rounded-lg
                    @if($demande->status === 'accepté')
                        bg-green-50 border border-green-200
                    @else
                        bg-red-50 border border-red-200
                    @endif">
                    <h2 class="font-bold mb-2
                        @if($demande->status === 'accepté')
                            text-green-900
                        @else
                            text-red-900
                        @endif">
                        Réponse de l'administration
                    </h2>
                    @if($demande->admin)
                        <p class="text-sm
                            @if($demande->status === 'accepté')
                                text-green-800
                            @else
                                text-red-800
                            @endif
                            mb-2">
                            Par {{ $demande->admin->first_name }} {{ $demande->admin->last_name }}
                        </p>
                    @endif
                    <p class="
                        @if($demande->status === 'accepté')
                            text-green-800
                        @else
                            text-red-800
                        @endif">
                        {{ $demande->admin_response ?? 'Pas de message' }}
                    </p>
                    <p class="text-xs mt-2
                        @if($demande->status === 'accepté')
                            text-green-700
                        @else
                            text-red-700
                        @endif">
                        {{ $demande->updated_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
            @endif

            <!-- Boutons d'action -->
            <div class="pt-4 border-t flex gap-3">
                <a href="{{ route('teacher.demandes.index') }}" class="inline-block px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">
                    ← Retour
                </a>

                {{-- Bouton annuler - seulement si demande en attente --}}
                @if($demande->status === 'soumis')
                    <form action="{{ route('teacher.demandes.destroy', $demande) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                            ✕ Annuler cette demande
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
