<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Rank;

class StudentFactory extends Factory
{
    public function definition()
    {
        $xp = $this->faker->numberBetween(0, 1100);

        // 2. Cari rank yang cocok untuk XP ini
        $rank = Rank::where('min_xp', '<=', $xp)
            ->orderByDesc('min_xp')
            ->first();

        return [
            'user_id' => User::factory(),      // create user baru
            'experience' => $xp,
            'rank_id' => $rank?->id ?? Rank::orderBy('min_xp')->first()->id,  
        ];
    }
}
