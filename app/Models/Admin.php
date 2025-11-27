<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
