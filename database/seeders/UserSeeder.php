<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'beginner_user',
                'name' => 'Beginner User',
                'email' => 'beginner@example.com',
                // 'experience' => 50,
                // 'rank_id' => 1, // Beginner
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => 'student',
            ],
            [
                'username' => 'intermediate_user',
                'name' => 'Intermediate User',
                'email' => 'intermediate@example.com',
                // 'experience' => 250,
                // 'rank_id' => 2, // Intermediate
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => 'student'
            ],
            [
                'username' => 'advanced_user',
                'name' => 'Advanced User',
                'email' => 'advanced@example.com',
                // 'experience' => 750,
                // 'rank_id' => 3, // Advanced
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => 'student'
            ],
            [
                'username' => 'expert_user',
                'name' => 'Expert User',
                'email' => 'expert@example.com',
                // 'experience' => 1500,
                // 'rank_id' => 4, // Expert
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => 'teacher'
            ],
        ]);
    }
}
