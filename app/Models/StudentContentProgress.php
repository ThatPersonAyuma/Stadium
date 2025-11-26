<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentContentProgress extends Model
{
    use HasFactory;

    protected $fillable =[
            'student_id',
            'content_id',
            'is_completed',
    ];

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function content():BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

}
