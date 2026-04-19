<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Candidature;

class StatutCandidatureMis
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    use Dispatchable;

    public Candidature $candidature;
    public string $ancienStatut;


    public function __construct(Candidature $candidature, string $ancienStatut)
    {
        $this->candidature = $candidature;
        $this->ancienStatut = $ancienStatut;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
