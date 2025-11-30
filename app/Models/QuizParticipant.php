<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class QuizParticipant extends Model
{
    protected $fillable = [
        'quiz_id',
        'participant_id',
        'score',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'participant_id');
    }
}
