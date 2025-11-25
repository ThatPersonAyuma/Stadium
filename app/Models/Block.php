<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ContentType;
use App\Helpers\FileHelper;


class Block extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable =[
        'type',
        'data',
        'order_index',
        'card_id',
    ];

    protected $casts = [
        'type' => ContentType::class,
        'data' => 'array',
    ];

    protected static function booted()
    {
        static::deleting(function ($block) {
            // Fungsi yang ingin dijalankan sebelum delete
            $block->load('card.content.lesson.course');
            if ($block->type == ContentType::IMAGE || $block->type == ContentType::GIF || $block->type == ContentType::VIDEO){
                $result = FileHelper::deleteBlockFile($block->card->content->lesson->course->id, $block->card->content->lesson->id, $block->card->content->id, $block->card->id, $block->id);
                if (!$result){
                    Log::error("Gagal menghapus folder untuk block ID: {$block->id}");
                }
                Block::where('card_id', $block->card_id)
                    ->where('order_index', '>', $block->order_index)
                    ->decrement('order_index');
            }
        });
    }

    public function card():BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

}
