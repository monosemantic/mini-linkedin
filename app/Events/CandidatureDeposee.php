<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Candidature;

class CandidatureDeposee
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Candidature $candidature;

    /** Cree un evenement de candidature deposee. */
    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }
}
