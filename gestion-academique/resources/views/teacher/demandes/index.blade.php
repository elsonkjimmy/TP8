@extends('layouts.app')

@section('title', 'Mes demandes de modification')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Mes Demandes de Modification</h1>
            <p class="text-gray-600 mt-2">Suivez l'état de vos demandes auprès de l'administration</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-600 text-sm">Total</p>
                <p class="text-3xl font-bold text-primary">{{ $stats['pending'] + $stats['accepted'] + $stats['rejected'] }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4 text-center border-l-4 border-yellow-400">
                <p class="text-gray-600 text-sm">En attente</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4 text-center border-l-4 border-green-400">
                <p class="text-gray-600 text-sm">Acceptées</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['accepted'] }}</p>
            </div>
            <div class="bg-red-50 rounded-lg shadow p-4 text-center border-l-4 border-red-400">
                <p class="text-gray-600 text-sm">Rejetées</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        <!-- Bouton nouvelle demande -->
        <div class="mb-6">
            <a href="{{ route('teacher.demandes.create') }}" class="inline-block bg-primary text-white font-medium py-2 px-6 rounded-lg hover:bg-accent transition">
                + Nouvelle demande
            </a>
        </div>

        <!-- Messages de succès -->
        @if($message = Session::get('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700">✓ {{ $message }}</p>
            </div>
        @endif

        <!-- Tableau des demandes -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            @forelse($demandes as $demande)
                <div class="border-b last:border-b-0 p-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <!-- Titre -->
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-gray-900">
                                    @if($demande->seance)
                                        {{ $demande->seance->ue->nom ?? 'UE' }}
                                    @else
                                        {{ $demande->seanceTemplate->ue->nom ?? 'UE' }}
                                    @endif
                                </h3>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full
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

                            <!-- Type et infos -->
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>
                                    <span class="font-medium">Type:</span>
                                    {{ ucfirst($demande->type_demande) }}
                                </p>
                                <p>
                                    <span class="font-medium">Groupe:</span>
                                    @if($demande->seance)
                                        {{ $demande->seance->groupe->nom ?? 'N/A' }}
                                    @else
                                        {{ $demande->seanceTemplate->groupe->nom ?? 'N/A' }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Soumise le {{ $demande->created_at->format('d/m/Y à H:i') }}
                                </p>
                            </div>

                            <!-- Réponse admin -->
                            @if($demande->admin_response)
                                <div class="mt-3 p-3 bg-gray-100 rounded text-sm">
                                    <p class="font-medium text-gray-700 mb-1">Réponse de l'administration:</p>
                                    <p class="text-gray-700">{{ $demande->admin_response }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex gap-2">
                            <a href="{{ route('teacher.demandes.show', $demande) }}" class="inline-block bg-primary text-white px-4 py-2 rounded hover:bg-accent transition text-sm">
                                Détails
                            </a>
                            @if($demande->status === 'soumis')
                                <form action="{{ route('teacher.demandes.destroy', $demande) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette demande ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition text-sm">
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <p class="text-lg mb-2">Aucune demande trouvée</p>
                    <p class="text-sm mb-4">Vous n'avez pas encore soumis de demande de modification</p>
                    <a href="{{ route('teacher.demandes.create') }}" class="inline-block bg-primary text-white font-medium py-2 px-6 rounded-lg hover:bg-accent transition">
                        Créer une demande
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($demandes->hasPages())
            <div class="mt-6">
                {{ $demandes->links() }}
            </div>
        @endif
    </div>
@endsection
