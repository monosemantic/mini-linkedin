<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\CandidatureDeposee::class => [
            \App\Listeners\LogCandidatureDeposee::class,
        ],
        \App\Events\StatutCandidatureMis::class => [
            \App\Listeners\LogStatutCandidature::class,
        ],
    ];    

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
    }
}
