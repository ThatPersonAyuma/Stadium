<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Enums\UserRole;
use App\Enums\SocialMediaType;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', UserRole::TEACHER)->get();
        foreach ($users as $user){
            Teacher::create(
                [
                    'user_id' => $user->id,
                    'phone_number' => '+6285643342009',
                    'social_media_type' => SocialMediaType::GITHUB,
                    'social_media' => 'ThatPersonAyuma',
                    'institution' => 'Universitas Jember',
                ]
            );
        }
    }
}
