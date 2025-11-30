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

class AnswerSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $participants;
    public $quizId;

    public function __construct($quizId, $participants,)
    {
        $this->participants = $participants;
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
            new PrivateChannel('quiz.' . $this->quizId),
        ];
    }
    public function broadcastAs(): string
    {
        return 'quiz.answer.submitted';
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
