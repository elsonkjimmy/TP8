@extends('layouts.app')

@section('title', 'Gestion des UE')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Gestion des Unités d'Enseignement (UE)</h1>
            <a href="{{ route('admin.ues.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                <i class="fas fa-plus mr-2"></i>Ajouter une UE
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 border-b">
                <form method="GET" class="flex gap-3 items-end">
                    <div>
                        <label class="text-sm text-gray-600">Filière</label>
                        <select name="filiere_id" class="block mt-1 border rounded px-3 py-2">
                            <option value="">Toutes</option>
                            @foreach($filieres as $f)
                                <option value="{{ $f->id }}" {{ request('filiere_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Groupe</label>
                        <select name="groupe_id" class="block mt-1 border rounded px-3 py-2">
                            <option value="">Tous</option>
                            @foreach($groupes as $g)
                                <option value="{{ $g->id }}" {{ request('groupe_id') == $g->id ? 'selected' : '' }}>{{ $g->nom }} ({{ $g->filiere?->nom }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded">Filtrer</button>
                    </div>
                    <div>
                        <a href="{{ route('admin.ues.index') }}" class="text-sm text-gray-600 underline">Réinitialiser</a>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Code
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nom
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Filière
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Enseignant
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ues as $ue)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $ue->code }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $ue->nom }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $ue->filiere->nom ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ $ue->enseignant->first_name ?? '' }} {{ $ue->enseignant->last_name ?? '' }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.ues.edit', $ue->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ues.destroy', $ue->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette UE ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
