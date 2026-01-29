@extends('layouts.app')

@section('title', 'Mes Désiratas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary">Mes Désiratas</h1>
        <a href="{{ route('teacher.desideratas.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition">
            <i class="fas fa-plus mr-2"></i>Nouveau désirata
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4 border-b">UE / Groupe</th>
                        <th class="px-6 py-4 border-b">Jour / Heure</th>
                        <th class="px-6 py-4 border-b">Commentaire</th>
                        <th class="px-6 py-4 border-b">Statut</th>
                        <th class="px-6 py-4 border-b">Date demande</th>
                        <th class="px-6 py-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($desideratas as $desiderata)
                        <tr class="hover:bg-gray-50 transition">
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
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $desiderata->comment ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($desiderata->status === 'approved')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Accepté</span>
                                @elseif($desiderata->status === 'rejected')
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Rejeté</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">En attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $desiderata->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($desiderata->status === 'pending')
                                    <form action="{{ route('teacher.desideratas.destroy', $desiderata->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler ce désirata ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            <i class="fas fa-trash-alt mr-1"></i>Annuler
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">
                                Aucun désirata soumis pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
