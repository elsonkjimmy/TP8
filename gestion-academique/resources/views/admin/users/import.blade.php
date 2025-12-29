@extends('layouts.app')

@section('title', 'Importer des Utilisateurs')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Importer des Utilisateurs</h1>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">Veuillez corriger les erreurs suivantes :</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="file">Fichier Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" id="file" name="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('file') border-red-500 @enderror" required>
                    @error('file')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-accent transition-colors">
                        <i class="fas fa-upload mr-2"></i>Importer les utilisateurs
                    </button>
                </div>
            </form>
            <div class="mt-6 border-t pt-6 text-gray-600 text-sm">
                <p class="font-bold mb-2">Format attendu du fichier Excel :</p>
                <p>Le fichier doit contenir les colonnes suivantes dans la première ligne (en-têtes) :</p>
                <ul class="list-disc list-inside ml-4">
                    <li><code class="font-mono">first_name</code> (Prénom)</li>
                    <li><code class="font-mono">last_name</code> (Nom)</li>
                    <li><code class="font-mono">email</code> (Adresse Email)</li>
                    <li><code class="font-mono">role</code> (Rôle : admin, teacher, delegate)</li>
                    <li><code class="font-mono">password</code> (Mot de passe)</li>
                </ul>
                <p class="mt-2">Assurez-vous que les rôles correspondent aux valeurs attendues.</p>
            </div>
        </div>
    </div>
@endsection
