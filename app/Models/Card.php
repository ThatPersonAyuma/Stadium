<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable =[
        'order_index',
        'content_id',
    ];
    public function content():BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
    public function blocks():HasMany
    {
        return $this->hasMany(Card::class);
    }
}
