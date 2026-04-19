<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\StatutCandidatureMis;
use Illuminate\Support\Facades\Log;

class LogStatutCandidature
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
    public function handle(StatutCandidatureMis $event): void
    {
        $date         = now()->toDateTimeString();
        $ancienStatut = $event->ancienStatut;
        $nouveauStatut = $event->candidature->statut;

        Log::channel('candidatures')
            ->info("[STATUT] Date: {$date} | Ancien: {$ancienStatut} | Nouveau: {$nouveauStatut}");
    }
}
