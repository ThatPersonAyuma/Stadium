<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rank extends Model
{
    use HasFactory;

    protected $fillable =[
            'title',
            'min_xp',
            'max_xp',
    ];

    public function users():HasMany
    {
        return $this->hasMany(UserRank::class);
    }
}
