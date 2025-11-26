<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Enums\UserRole;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', UserRole::ADMIN)->get();
        foreach ($users as $user){
            Admin::create(
                ['user_id' => $user->id]
            );
        }
    }
}
