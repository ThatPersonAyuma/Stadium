<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Enums\UserRole;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'experience',
        'rank_id',
        'password',
        'avatar_filename',
        'role',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'role' => UserRole::class,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class)
                    ->withPivot(['progress', 'last_lesson_id'])
                    ->withTimestamps();
    }
    public function student():HasOne
    {
        return $this->hasOne(Student::class);
    }
    public function teacher():HasOne
    {
        return $this->hasOne(Teacher::class);
    }
    public function admin():HasOne
    {
        return $this->hasOne(Admin::class);
    }

}
