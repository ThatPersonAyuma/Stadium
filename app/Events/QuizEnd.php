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
use App\Models\Rank;
use Illuminate\Support\Facades\Log;

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
    function getRank($exp)
    {
        $ranks = Rank::orderBy('min_xp')->get();
        foreach ($ranks as $rank) {
            if ($rank->max_xp === null && $exp >= $rank->min_xp) {
                return $rank->title;
            }
            if ($exp >= $rank->min_xp && $exp <= $rank->max_xp) {
                return $rank->title;
            }
        }
        return $ranks->last()->title;
    }


    public function broadcastWith(): array
    {
        return [
            'scoreboard' => QuizParticipant::where('quiz_id', $this->quizId)
                ->with('student.user')
                ->orderByDesc('score')
                ->get()
                ->map(function ($p) {

                    $oldRank = $this->getRank($p->experience - $p->experience_got);
                    $newRank = $this->getRank($p->experience);

                    return [
                        'username'        => $p->student->user->username, 
                        'score'           => $p->score,
                        'experience_got'  => $p->experience_got,
                        'experience'      => $p->experience,

                        'rank_before'     => $oldRank,
                        'rank_after'      => $newRank,
                        'rank_up'         => $oldRank !== $newRank,
                    ];
                }),
        ];
    }
}
