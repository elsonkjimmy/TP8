@extends('layouts.app')

@section('title', 'Examen de la demande')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.demandes-modifications.index') }}" class="text-primary hover:text-accent">
                ← Retour
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- En-tête -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                @if($demande->seance && $demande->seance->ue)
                                    {{ $demande->seance->ue->nom ?? 'UE' }}
                                @elseif($demande->seanceTemplate && $demande->seanceTemplate->ue)
                                    {{ $demande->seanceTemplate->ue->nom ?? 'UE' }}
                                @else
                                    <span class="text-gray-400">UE Inconnue</span>
                                @endif
                            </h1>
                            <p class="text-gray-600">Enseignant: <span class="font-medium">
                                @if($demande->enseignant)
                                    {{ $demande->enseignant->first_name ?? '' }} {{ $demande->enseignant->last_name ?? '' }}
                                @else
                                    <span class="text-gray-400">Utilisateur supprimé</span>
                                @endif
                            </span></p>
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

                    <div class="grid grid-cols-2 gap-4 text-sm pt-4 border-t">
                        <div>
                            <p class="text-gray-600 mb-1">Type de demande</p>
                            <p class="font-semibold">{{ ucfirst($demande->type_demande) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Soumise le</p>
                            <p class="font-semibold">{{ $demande->created_at ? $demande->created_at->format('d/m/Y à H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informations de la séance -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="font-bold text-lg text-gray-900 mb-4">Informations de la séance</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm mb-1">UE</p>
                            <p class="font-medium">
                                @if($demande->seance && $demande->seance->ue)
                                    {{ $demande->seance->ue->nom ?? 'N/A' }} ({{ $demande->seance->ue->code ?? '' }})
                                @elseif($demande->seanceTemplate && $demande->seanceTemplate->ue)
                                    {{ $demande->seanceTemplate->ue->nom ?? 'N/A' }} ({{ $demande->seanceTemplate->ue->code ?? '' }})
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Niveau</p>
                            <p class="font-medium">
                                @if($demande->seance && $demande->seance->groupe)
                                    {{ $demande->seance->groupe->nom ?? 'N/A' }}
                                @elseif($demande->seanceTemplate && $demande->seanceTemplate->groupe)
                                    {{ $demande->seanceTemplate->groupe->nom ?? 'N/A' }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Salle</p>
                            <p class="font-medium">
                                @if($demande->seance && $demande->seance->salle)
                                    {{ $demande->seance->salle->numero ?? 'N/A' }} ({{ $demande->seance->salle->capacite ?? '' }} places)
                                @elseif($demande->seanceTemplate && $demande->seanceTemplate->salle)
                                    {{ $demande->seanceTemplate->salle->numero ?? 'N/A' }} ({{ $demande->seanceTemplate->salle->capacite ?? '' }} places)
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm mb-1">Horaire</p>
                            <p class="font-medium">
                                @if($demande->seance)
                                    {{ \Carbon\Carbon::parse($demande->seance->jour)->format('d/m/Y') }}
                                    {{ \Carbon\Carbon::parse($demande->seance->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($demande->seance->heure_fin)->format('H:i') }}
                                @elseif($demande->seanceTemplate)
                                    {{ ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'][$demande->seanceTemplate->day_of_week - 1] ?? 'Jour' }}
                                    {{ $demande->seanceTemplate->start_time }} - {{ $demande->seanceTemplate->end_time }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="font-bold text-lg text-gray-900 mb-4">Description de la demande</h2>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $demande->description }}</p>
                    </div>
                </div>

                <!-- Réponse existante -->
                @if($demande->status !== 'soumis')
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4
                        @if($demande->status === 'accepté')
                            border-green-500
                        @else
                            border-red-500
                        @endif">
                        <h2 class="font-bold text-lg text-gray-900 mb-4">Réponse de l'administration</h2>
                        <div class="p-4 rounded-lg
                            @if($demande->status === 'accepté')
                                bg-green-50
                            @else
                                bg-red-50
                            @endif">
                            <p class="font-medium mb-2
                                @if($demande->status === 'accepté')
                                    text-green-900
                                @else
                                    text-red-900
                                @endif">
                                {{ ucfirst($demande->status) }} par 
                                @if($demande->admin)
                                    {{ $demande->admin->first_name ?? '' }} {{ $demande->admin->last_name ?? '' }}
                                @else
                                    <span class="text-gray-400">Admin supprimé</span>
                                @endif
                            </p>
                            <p class="
                                @if($demande->status === 'accepté')
                                    text-green-800
                                @else
                                    text-red-800
                                @endif">
                                {{ $demande->admin_response ?? 'Pas de message' }}
                            </p>
                            <p class="text-xs mt-3
                                @if($demande->status === 'accepté')
                                    text-green-700
                                @else
                                    text-red-700
                                @endif">
                                {{ $demande->updated_at ? $demande->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne latérale - Actions -->
            <div class="lg:col-span-1">
                @if($demande->status === 'soumis')
                    <div class="space-y-4">
                        <!-- Bouton Accepter -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="font-bold text-gray-900 mb-4">Accepter la demande</h3>
                            <form action="{{ route('admin.demandes-modifications.accept', $demande) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="accept_response" class="block text-sm font-medium text-gray-700 mb-2">Message (optionnel)</label>
                                    <textarea name="admin_response" id="accept_response" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Votre réponse..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-green-700 transition">
                                    ✓ Accepter
                                </button>
                            </form>
                        </div>

                        <!-- Bouton Rejeter -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="font-bold text-gray-900 mb-4">Rejeter la demande</h3>
                            <form action="{{ route('admin.demandes-modifications.reject', $demande) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr?');">
                                @csrf
                                <div class="mb-4">
                                    <label for="reject_response" class="block text-sm font-medium text-gray-700 mb-2">Motif (requis)</label>
                                    <textarea name="admin_response" id="reject_response" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Expliquez pourquoi..." required></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-red-700 transition">
                                    ✗ Rejeter
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Infos supplémentaires -->
                <div class="bg-white rounded-lg shadow-lg p-6 mt-4">
                    <h3 class="font-bold text-gray-900 mb-3">Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Email enseignant</p>
                            <p class="font-medium break-all">
                                @if($demande->enseignant)
                                    {{ $demande->enseignant->email }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600">ID demande</p>
                            <p class="font-medium text-xs text-gray-500">#{{ $demande->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
