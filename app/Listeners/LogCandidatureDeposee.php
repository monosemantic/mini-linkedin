<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\CandidatureDeposee;
use Illuminate\Support\Facades\Log;

class LogCandidatureDeposee
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CandidatureDeposee $event): void
    {
        $candidature = $event->candidature->load('profil.user', 'offre');
        $candidat    = $candidature->profil->user->name;
        $offre       = $candidature->offre->titre;
        $date        = now()->toDateTimeString();

        Log::channel('candidatures')
            ->info("[CANDIDATURE] Date: {$date} | Candidat: {$candidat} | Offre: {$offre}");
    }
}
