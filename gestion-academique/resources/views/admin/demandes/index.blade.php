@extends('layouts.app')

@section('title', 'Gestion des demandes de modification')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Demandes de Modification</h1>
            <p class="text-gray-600 mt-2">Gérez les demandes de modification soumises par les enseignants</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-gray-600 text-sm">Total</p>
                <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4 text-center border-l-4 border-yellow-400">
                <p class="text-gray-600 text-sm">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4 text-center border-l-4 border-green-400">
                <p class="text-gray-600 text-sm">Acceptées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['accepted'] }}</p>
            </div>
            <div class="bg-red-50 rounded-lg shadow p-4 text-center border-l-4 border-red-400">
                <p class="text-gray-600 text-sm">Rejetées</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
            <form action="{{ route('admin.demandes-modifications.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">-- Tous --</option>
                        <option value="soumis" {{ request('status') === 'soumis' ? 'selected' : '' }}>En attente</option>
                        <option value="accepté" {{ request('status') === 'accepté' ? 'selected' : '' }}>Acceptées</option>
                        <option value="rejeté" {{ request('status') === 'rejeté' ? 'selected' : '' }}>Rejetées</option>
                    </select>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">-- Tous --</option>
                        <option value="créneau" {{ request('type') === 'créneau' ? 'selected' : '' }}>Créneau</option>
                        <option value="salle" {{ request('type') === 'salle' ? 'selected' : '' }}>Salle</option>
                        <option value="enseignant" {{ request('type') === 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                        <option value="autre" {{ request('type') === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="flex gap-2 items-end">
                    <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-accent transition">
                        Filtrer
                    </button>
                    <a href="{{ route('admin.demandes-modifications.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Tableau des demandes -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">UE</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Soumise</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm">
                                    @if($demande->enseignant)
                                        <p class="font-medium">{{ $demande->enseignant->first_name ?? '' }} {{ $demande->enseignant->last_name ?? '' }}</p>
                                        <p class="text-xs text-gray-600">{{ $demande->enseignant->email }}</p>
                                    @else
                                        <p class="font-medium text-gray-400">Utilisateur supprimé</p>
                                        <p class="text-xs text-gray-400">N/A</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($demande->seance)
                                        {{ $demande->seance->ue->nom ?? 'N/A' }}
                                    @elseif($demande->seanceTemplate)
                                        {{ $demande->seanceTemplate->ue->nom ?? 'N/A' }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($demande->type_demande) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($demande->status === 'soumis')
                                            bg-yellow-100 text-yellow-800
                                        @elseif($demande->status === 'accepté')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($demande->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $demande->created_at ? $demande->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.demandes-modifications.show', $demande) }}" class="text-primary hover:text-accent font-medium text-sm">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Aucune demande trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($demandes->hasPages())
            <div class="mt-6">
                {{ $demandes->links() }}
            </div>
        @endif
    </div>
@endsection
