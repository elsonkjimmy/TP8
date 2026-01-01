@extends('layouts.app')

@section('title', 'Détails du Rapport de Séance')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Rapport de Séance du {{ \Carbon\Carbon::parse($rapportSeance->seance->jour)->format('d/m/Y') }}</h1>
            <a href="{{ route('teacher.dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold mb-2">UE :</p>
                <p class="text-gray-900">{{ $rapportSeance->seance->ue->nom ?? 'N/A' }} ({{ $rapportSeance->seance->ue->code ?? 'N/A' }})</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold mb-2">Enseignant :</p>
                <p class="text-gray-900">{{ $rapportSeance->seance->enseignant->first_name ?? '' }} {{ $rapportSeance->seance->enseignant->last_name ?? '' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold mb-2">Groupe :</p>
                <p class="text-gray-900">{{ $rapportSeance->seance->groupe->nom ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold mb-2">Date et Heure de la Séance :</p>
                <p class="text-gray-900">
                    {{ \Carbon\Carbon::parse($rapportSeance->seance->jour)->format('d/m/Y') }} de
                    {{ \Carbon\Carbon::parse($rapportSeance->seance->heure_debut)->format('H:i') }} à
                    {{ \Carbon\Carbon::parse($rapportSeance->seance->heure_fin)->format('H:i') }}
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold mb-2">Contenu du Rapport :</p>
                <div class="bg-gray-100 p-4 rounded-lg text-gray-800">
                    {{ $rapportSeance->contenu }}
                </div>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold mb-2">Statut du Rapport :</p>
                <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                    <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full 
                        @if($rapportSeance->status == 'validated') bg-green-200 text-green-800
                        @elseif($rapportSeance->status == 'rejected') bg-red-200 text-red-800
                        @else bg-yellow-200 text-yellow-800 @endif"></span>
                    <span class="relative">{{ ucfirst($rapportSeance->status) }}</span>
                </span>
            </div>
            @if($rapportSeance->delegue)
                <div class="mb-4">
                    <p class="text-gray-700 text-sm font-bold mb-2">Validé par le Délégué :</p>
                    <p class="text-gray-900">{{ $rapportSeance->delegue->first_name }} {{ $rapportSeance->delegue->last_name }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
