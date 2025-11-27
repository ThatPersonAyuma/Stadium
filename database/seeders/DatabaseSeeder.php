<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
 
        $this->call([
            RankSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            StudentSeeder::class,
            TeacherSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            ContentSeeder::class,
            CardSeeder::class,
            BlockSeeder::class,
            QuizSeeder::class,
        ]);
    }
}
