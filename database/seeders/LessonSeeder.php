<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            [
                'title' => 'Pengenalan Pemrograman',
                'description' => 'Membahas tentang apa itu pemrograman serta contohnya',
                'order_index' => 1,
                'course_id' => 1,
            ],
            [
                'title' => 'Bahasa Pemrograman Python',
                'description' => 'Membahas tentang apa bahasa pemrograman, intepreter, bahasa pemrograman Python, dan dasar-dasar Python',
                'order_index' => 2,
                'course_id' => 1,
            ],
        ];
        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }
    }
}
