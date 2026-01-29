@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-10 text-center">
                <div class="inline-block p-4 rounded-full bg-primary/10 mb-4">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-3xl text-white font-bold shadow-lg mx-auto">
                         {{ substr(Auth::user()->first_name, 0, 1) }}
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">{{ Auth::user()->name }}</h1>
                <p class="text-gray-500">{{ ucfirst(Auth::user()->role) }} • Université de Yaoundé I</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Navigation/Status (Optional in future, now just spacing or sticky menu) -->
                <div class="hidden lg:block lg:col-span-1">
                    <div class="sticky top-24 space-y-4">
                        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Navigation Rapide</h3>
                            <nav class="space-y-2">
                                <a href="#profile-info" class="flex items-center text-primary font-medium p-2 rounded-lg bg-primary/5">
                                    <i class="fas fa-user-circle w-6"></i> Informations
                                </a>
                                <a href="#update-password" class="flex items-center text-gray-600 hover:text-primary hover:bg-gray-50 p-2 rounded-lg transition-colors">
                                    <i class="fas fa-lock w-6"></i> Sécurité
                                </a>
                                <a href="#delete-account" class="flex items-center text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                    <i class="fas fa-trash-alt w-6"></i> Zone Danger
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Forms -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Profile Info -->
                    <div id="profile-info" class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 md:p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -mr-8 -mt-8"></div>
                        <div class="relative z-10">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Password Update -->
                    <div id="update-password" class="bg-white rounded-2xl shadow-soft border border-gray-100 p-6 md:p-8 relative overflow-hidden">
                         <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-8 -mt-8"></div>
                        <div class="relative z-10">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete Account -->
                    <div id="delete-account" class="bg-red-50/50 rounded-2xl shadow-soft border border-red-100 p-6 md:p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection