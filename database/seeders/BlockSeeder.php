<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ContentType;
use App\Models\Block;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blocks = [
            // ============================
            // Content 1: "Apa itu Pemrograman?"
            // ============================

            // Slide 1 — Penjelasan pengantar
            [
                'type' => ContentType::TEXT,
                'data' => [
                    'content' => 'Pemrograman adalah proses menulis serangkaian instruksi agar komputer dapat melakukan tugas tertentu. Bahasa pemrograman digunakan agar manusia dapat berkomunikasi dengan komputer.',
                ],
                'order_index' => 1,
                'card_id' => 1,
            ],

            // Slide 2 — Ilustrasi visual
            [
                'type' => ContentType::IMAGE,
                'data' => [
                    'filename' => 'programming_flowchart.png',
                    'alt' => 'Ilustrasi alur berpikir dalam pemrograman.',
                ],
                'order_index' => 2,
                'card_id' => 1,
            ],

            // Slide 3 — Teks tambahan setelah gambar
            [
                'type' => ContentType::TEXT,
                'data' => [
                    'content' => 'Dengan pemrograman, kita bisa membuat aplikasi, situs web, sistem otomatisasi, dan masih banyak lagi.',
                ],
                'order_index' => 3,
                'card_id' => 1,
            ],

            // Slide 4 — Mini quiz
            [
                'type' => ContentType::QUIZ,
                'data' => [
                    'question' => 'Pemrograman digunakan untuk apa?',
                    'choices' => [
                        'A' => 'Menggambar di komputer',
                        'B' => 'Menulis instruksi untuk komputer',
                        'C' => 'Membuat musik',
                        'D' => 'Menjalankan hardware secara manual',
                    ],
                    'answer' => 'B',
                    'explanation' => 'Pemrograman adalah menulis instruksi agar komputer melakukan sesuatu secara otomatis.',
                ],
                'order_index' => 4,
                'card_id' => 1,
            ],
            [
                'type' => ContentType::CODE,
                'data' => [
                    'language' => 'php',
                    'code' => "<?php\necho 'Hello from PHP!';"
                ],
                'order_index' => 5,
                'card_id' => 1,
            ],

            // ============================
            // Content 2: "Tujuan dan Manfaat Pemrograman"
            // ============================

            // Slide 1 — Pembuka teks
            [
                'type' => ContentType::TEXT,
                'data' => [
                    'content' => 'Tujuan utama dari belajar pemrograman adalah untuk memecahkan masalah dan menciptakan solusi digital yang efisien.',
                ],
                'order_index' => 1,
                'card_id' => 2,
            ],

            // Slide 2 — GIF singkat
            [
                'type' => ContentType::GIF,
                'data' => [
                    'filename' => 'coding_process.gif',
                    'alt' => 'Contoh proses coding sederhana.',
                ],
                'order_index' => 2,
                'card_id' => 2,
            ],

            // Slide 3 — Penjelasan tambahan
            [
                'type' => ContentType::TEXT,
                'data' => [
                    'content' => 'Pemrograman membantu kita membuat otomatisasi tugas, seperti menghitung data atau mengirim pesan secara otomatis.',
                ],
                'order_index' => 3,
                'card_id' => 2,
            ],

            // Slide 4 — Quiz sederhana
            [
                'type' => ContentType::QUIZ,
                'data' => [
                    'question' => 'Salah satu manfaat belajar pemrograman adalah?',
                    'choices' => [
                        'A' => 'Menghafal kode tanpa makna',
                        'B' => 'Meningkatkan kemampuan berpikir logis dan pemecahan masalah',
                        'C' => 'Menghindari komputer',
                        'D' => 'Menjadi pengguna pasif teknologi',
                    ],
                    'answer' => 'B',
                    'explanation' => 'Pemrograman melatih logika dan kreativitas dalam membuat solusi digital.',
                ],
                'order_index' => 4,
                'card_id' => 2,
            ],
        ];
        // dd(json_encode($blocks[0]['data']));
        foreach ($blocks as $block) {
            Block::create($block);
        }
    }  
}
