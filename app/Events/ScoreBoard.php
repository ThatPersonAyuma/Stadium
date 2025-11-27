<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\QuizParticipant;

class ScoreBoard
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quiz_id;

    /**
     * Create a new event instance.
     */
    public function __construct($quiz_id)
    {
        $this->quiz_id = $quiz_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('quiz.' . $this->quizId),
        ];
    }
    public function broadcastAs(): string
    {
        return 'quiz.scoreboard';
    }
    public function broadcastWith(): array
    {
        return [
            'scoreboard' => QuizParticipant::where('quiz_id', $quizId)
                ->orderByDesc('score'),
        ];
    }
}
