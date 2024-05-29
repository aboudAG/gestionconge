<?php

namespace App\Providers;
use App\Models\Demande;
use Illuminate\Support\ServiceProvider;
use App\Observers\DemandeCongeObserver;
use App\Services\StructureService;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\NavigationComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        View::composer('layouts.navigation', NavigationComposer::class);
        
        $this->app->singleton(StructureService::class, function ($app) {
            return new StructureService();
        });
        
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Demande::observe(DemandeCongeObserver::class);
    }
}
