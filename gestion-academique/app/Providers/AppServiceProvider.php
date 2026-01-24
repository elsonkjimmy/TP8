<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use App\Models\DemandeModification;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Schema::defaultStringLength(191);
       
       // Route model binding for DemandeModification
       Route::model('demande', DemandeModification::class);
       Route::model('demandeModification', DemandeModification::class);
       Route::model('demandes_modification', DemandeModification::class);
    }
}
