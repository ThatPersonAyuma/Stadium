<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable =[
        'title',
        'order_index',
        'lesson_id',
    ];



    public function lesson():BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
    public function cards():HasMany
    {
        return $this->hasMany(Card::class);
    }
}
