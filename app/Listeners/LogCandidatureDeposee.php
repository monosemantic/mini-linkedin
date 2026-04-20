<?php

namespace App\Listeners;

use App\Events\CandidatureDeposee;
use Illuminate\Support\Facades\Log;

class LogCandidatureDeposee
{
    /** Ecrit dans le log les details d une candidature deposee. */
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
