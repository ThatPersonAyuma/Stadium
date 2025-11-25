<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\FileHelper;

class Content extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable =[
        'title',
        'order_index',
        'lesson_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($content) {
            // Fungsi yang ingin dijalankan sebelum delete
            $content->load('lesson.course');
            if (!FileHelper::deleteFolder($content->lesson->course->id, $content->lesson->id, $content->id))
            {
                Log::error("Gagal menghapus folder untuk content ID: {$content->id}");
            }else{
                Content::where('lesson_id', $content->lesson->card_id)
                    ->where('order_index', '>', $content->order_index)
                    ->decrement('order_index');
            }
        });
    }

    public function lesson():BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
    public function cards():HasMany
    {
        return $this->hasMany(Card::class)->orderBy('order_index');
    }
}
