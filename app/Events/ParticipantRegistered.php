<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $quizId;
    public $names;

    /**
     * Create a new event instance.
     */
    public function __construct($quizId, $names)
    {
        $this->quizId = $quizId;
        $this->names = $names;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // $this->quizId;
            new PrivateChannel('quiz.' . $this->quizId),
        ];
    }
    public function broadcastAs(): string
    {
        return 'quiz.participant.registered';
    }
    public function broadcastWith(): array
    {
        return [
            'participants' => $this->names,
        ];
    }
}
