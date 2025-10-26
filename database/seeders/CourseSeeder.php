<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create(
            [
                'title' => 'Algoritma dan Pemrograman 1',
                'description' => 'Module tentang pengenalan dasar-dasar algoritma dan pemrograman. Pada modul ini menggunakan bahasa Python. Diharapkan setelah mempelajari modul ini teman-teman dapat memiliki dasar tentang algoritma dan pemrograman sebagai dasar untuk mempelajari materi yang lebih dalam.',
            ],
        );
    }
}
