<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;

    public $timestamps = false;

    public function courses():HasMany
    {
        return $this->hasMany(Course::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
