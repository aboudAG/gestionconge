<?php

namespace App\Providers;
use App\Models\Demande;
use Illuminate\Support\ServiceProvider;
use App\Observers\DemandeCongeObserver;

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
        Demande::observe(DemandeCongeObserver::class);
    }
}
