<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Candidature;

class StatutCandidatureMis
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Candidature $candidature;
    public string $ancienStatut;

    /** Cree un evenement de changement de statut de candidature. */
    public function __construct(Candidature $candidature, string $ancienStatut)
    {
        $this->candidature = $candidature;
        $this->ancienStatut = $ancienStatut;
    }
}
