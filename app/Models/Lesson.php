<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\FileHelper;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable =[
            'title',
            'description',
            'order_index',
            'course_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($lesson) {
            // Fungsi yang ingin dijalankan sebelum delete
            $lesson->load('course');
            if (!FileHelper::deleteFolder($lesson->course->id, $lesson->id))
            {
                Log::error("Gagal menghapus folder untuk lesson ID: {$lesson->id}");
            }else{
                Lesson::where('course_id', $lesson->course->card_id)
                    ->where('order_index', '>', $lesson->order_index)
                    ->decrement('order_index');
            }
        });
    }

    public function course():BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function contents():HasMany
    {
        return $this->hasMany(Content::class)->orderBy('order_index');
    }
}
