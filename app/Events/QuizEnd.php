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

class QuizEnd implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quizId;

    /**
     * Create a new event instance.
     */
    public function __construct($quizId)
    {
        $this->quizId = $quizId;
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
        return 'quiz.ended';
    }
    public function broadcastWith(): array
    {
        return [
            'scoreboard' => QuizParticipant::where('quiz_id', $this->quizId)
                ->with('student.user')
                ->orderByDesc('score')
                ->get()
                ->map(function ($p) {
                    return [
                        'username' => $p->student->user->username, 
                        'score' => $p->score,
                    ];
                }),
        ];
    }
}
