<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Helpers\FileHelper;

class Card extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable =[
        'order_index',
        'content_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($card) {
            // Fungsi yang ingin dijalankan sebelum delete
            $card->load('content.lesson.course');
            if (!FileHelper::deleteFolder($card->content->lesson->course->id, $card->content->lesson->id, $card->content->id, $card->id))
            {
                Log::error("Gagal menghapus folder untuk card ID: {$card->id}");
            }else{
                Card::where('content_id', $card->content->id)
                    ->where('order_index', '>', $card->order_index)
                    ->decrement('order_index');
            }

        });
    }

    public function content():BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
    public function blocks():HasMany
    {
        return $this->hasMany(Block::class)->orderBy('order_index');
    }
}
