<?php

namespace App\Listeners;

use App\Events\StatutCandidatureMis;
use Illuminate\Support\Facades\Log;

class LogStatutCandidatureMis
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
