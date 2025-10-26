<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = $contents = [
            // Lesson 1
            ['title' => 'Apa itu Pemrograman?', 'order_index' => 1, 'lesson_id' => 1],
            ['title' => 'Tujuan dan Manfaat Pemrograman', 'order_index' => 2, 'lesson_id' => 1],
            ['title' => 'Contoh Aplikasi Pemrograman dalam Kehidupan Sehari-hari', 'order_index' => 3, 'lesson_id' => 1],
            ['title' => 'Perbedaan antara Algoritma dan Pemrograman', 'order_index' => 4, 'lesson_id' => 1],

            // Lesson 2
            ['title' => 'Apa itu Bahasa Pemrograman?', 'order_index' => 1, 'lesson_id' => 2],
            ['title' => 'Jenis-jenis Bahasa Pemrograman', 'order_index' => 2, 'lesson_id' => 2],
            ['title' => 'Bahasa Tingkat Tinggi vs Tingkat Rendah', 'order_index' => 3, 'lesson_id' => 2],
            ['title' => 'Pengertian Interpreter dalam Python', 'order_index' => 4, 'lesson_id' => 2],
            ['title' => 'Struktur Dasar Program Python', 'order_index' => 5, 'lesson_id' => 2],
            ['title' => 'Menjalankan Program Python Pertama', 'order_index' => 6, 'lesson_id' => 2],
        ];
        foreach ($contents as $content) {
            Content::create($content);
        }
    }
}
