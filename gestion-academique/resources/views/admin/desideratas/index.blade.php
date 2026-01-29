@extends('layouts.app')

@section('title', 'Gestion des Désiratas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Gestion des Désiratas</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-primary transition">
            <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4 border-b">Enseignant</th>
                        <th class="px-6 py-4 border-b">UE / Groupe</th>
                        <th class="px-6 py-4 border-b">Créneau Souhaité</th>
                        <th class="px-6 py-4 border-b">Commentaire</th>
                        <th class="px-6 py-4 border-b">Date demande</th>
                        <th class="px-6 py-4 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($desideratas as $desiderata)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $desiderata->enseignant->first_name }} {{ $desiderata->enseignant->last_name }}</div>
                                <div class="text-xs text-gray-500">{{ $desiderata->enseignant->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $desiderata->seanceTemplate->ue->code ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-600">{{ $desiderata->seanceTemplate->groupe->nom ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $dayNames = [1=>'Lundi', 2=>'Mardi', 3=>'Mercredi', 4=>'Jeudi', 5=>'Vendredi', 6=>'Samedi'];
                                @endphp
                                <div class="font-semibold">{{ $dayNames[$desiderata->seanceTemplate->day_of_week] ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($desiderata->seanceTemplate->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($desiderata->seanceTemplate->end_time)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 italic">
                                "{{ $desiderata->comment ?? 'Aucun commentaire' }}"
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $desiderata->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <form action="{{ route('admin.desideratas.accept', $desiderata->id) }}" method="POST" onsubmit="return confirm('Accepter cette demande et assigner l\'enseignant ?');">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition text-sm flex items-center">
                                            <i class="fas fa-check mr-1"></i> Accepter
                                        </button>
                                    </form>
                                    
                                    <button onclick="openRejectModal({{ $desiderata->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition text-sm flex items-center">
                                        <i class="fas fa-times mr-1"></i> Rejeter
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                Aucune demande en attente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Rejet -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
        <form id="reject-form" method="POST" action="">
            @csrf
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Rejeter la demande</h3>
                <p class="text-gray-600 mb-4">Veuillez indiquer le motif du rejet :</p>
                <textarea name="comment" class="w-full border rounded p-2 mb-4" rows="3" placeholder="Motif du rejet..." required></textarea>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="text-gray-600 hover:text-gray-800">Annuler</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Confirmer le rejet</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        const form = document.getElementById('reject-form');
        form.action = `/admin/desideratas/${id}/reject`;
        document.getElementById('reject-modal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').classList.add('hidden');
    }
</script>
@endsection
