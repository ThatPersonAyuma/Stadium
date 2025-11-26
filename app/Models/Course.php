<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Helpers\FileHelper;


class Course extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'description',
        'teacher_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($course) {
            // Fungsi yang ingin dijalankan sebelum delete
            if (!FileHelper::deleteFolder($course->id))
            {
                Log::error("Gagal menghapus folder untuk course ID: {$course->id}");
            }
        });
    }

    public function lessons():HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order_index');
    }
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['progress', 'last_lesson_id'])
            ->withTimestamps();
    }
    public function teacher():BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function contents():HasManyThrough
    {
        return $this->hasManyThrough(Content::class, Lesson::class);
    }
}
