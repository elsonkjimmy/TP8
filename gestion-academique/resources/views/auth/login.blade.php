@extends('layouts.app')

@section('title', 'Connexion - Système de Gestion Académique')

@section('content')
    <div class="flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-primary">Connexion</h3>
                    <a href="/" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </a>
                </div>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Role -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="role">Rôle</label>
                        <select id="role" name="role" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="admin">Administrateur</option>
                            <option value="teacher">Enseignant</option>
                            <option value="delegate">Délégué</option>
                        </select>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="email">Identifiant (Email)</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Votre identifiant">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2" for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Votre mot de passe">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <button type="submit" class="ms-3 w-full bg-primary text-white py-3 rounded-lg font-medium hover:bg-accent transition-colors">
                            <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Les étudiants n'ont pas besoin de s'authentifier pour consulter les emplois du temps et annonces.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
