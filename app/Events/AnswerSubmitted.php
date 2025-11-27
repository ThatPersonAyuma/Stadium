<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnswerSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $userId;
    public $answer;
    public $quizId;

    public function __construct($userId, $answer, $quizId)
    {
        $this->userId = $userId;
        $this->answer = $answer;
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
        return 'quiz.answer.submintted';
    }
    public function broadcastWith(): array
    {
        return [
            'question' => $this->question,
            'options' => $this->options,
        ];
    }
}
