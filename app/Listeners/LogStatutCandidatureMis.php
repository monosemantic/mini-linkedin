<?php

namespace App\Listeners;

use App\Events\StatutCandidatureMis;
use Illuminate\Support\Facades\Log;

class LogStatutCandidatureMis
{
    /** Ecrit dans le log les changements de statut de candidature. */
    public function handle(StatutCandidatureMis $event): void
    {
        $date         = now()->toDateTimeString();
        $ancienStatut = $event->ancienStatut;
        $nouveauStatut = $event->candidature->statut;

        Log::channel('candidatures')
            ->info("[STATUT] Date: {$date} | Ancien: {$ancienStatut} | Nouveau: {$nouveauStatut}");
    }
}
