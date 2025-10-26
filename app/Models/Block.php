<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ContentType;

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

    public function card():BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
