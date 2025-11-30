<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'description',
        'creator_id',
        'running_index',
        'is_finished',
        'code',
        'interval',
    ];

    public function creator()
    {
        return $this->belongsTo(Teacher::class, 'creator_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function participants()
    {
        return $this->hasMany(QuizParticipant::class);
    }
}
