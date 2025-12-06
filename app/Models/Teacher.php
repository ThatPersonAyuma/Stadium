<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\AccountStatus;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'phone_number',
        'social_media',
        'social_media_type',
        'institution',
        'status',
    ];

    protected $casts = [
        'status' => AccountStatus::class,
    ];

    public function courses():HasMany
    {
        return $this->hasMany(Course::class);
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
