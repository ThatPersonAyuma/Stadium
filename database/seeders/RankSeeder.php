<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ranks')->insert([
            [
                'title' => 'Beginner',
                'min_xp' => 0,
                'max_xp' => 99,
            ],
            [
                'title' => 'Intermediate',
                'min_xp' => 100,
                'max_xp' => 499,
            ],
            [
                'title' => 'Advanced',
                'min_xp' => 500,
                'max_xp' => 999,
            ],
            [
                'title' => 'Expert',
                'min_xp' => 1000,
                'max_xp' => null,
            ],
        ]);
    }
}
