<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'ADMIN',
                'name' => 'ADMINISTRATOR',
                'email' => 'admin@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('admin123'),
                'remember_token' => null,
                'role' => UserRole::ADMIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'beginner_user',
                'name' => 'Beginner User',
                'email' => 'beginner@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => UserRole::STUDENT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'intermediate_user',
                'name' => 'Intermediate User',
                'email' => 'intermediate@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => UserRole::STUDENT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'advanced_user',
                'name' => 'Advanced User',
                'email' => 'advanced@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => UserRole::STUDENT,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'expert_user',
                'name' => 'Expert User',
                'email' => 'expert@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'role' => UserRole::TEACHER,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
