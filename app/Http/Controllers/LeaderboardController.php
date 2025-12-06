<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LeaderboardController extends Controller
{
    public function index()
    {
        // ==========================================
        // 1. AMBIL DATA DARI DATABASE (JIKA ADA)
        // // ==========================================
        // $hasUsersTable = Schema::hasTable('users');
        $allPlayers = collect();
        // $allPlayers = $hasUsersTable
        //     ? User::where('role', 'student')
        //         ->orderByDesc('xp') // Urutkan dari XP tertinggi
        //         ->limit(50)         // Ambil 50 besar aja
        //         ->get()
        //         ->map(function ($u) {
        //             // Standardisasi format data biar View gak bingung
        //             return (object)[
        //                 'name'   => $u->name ?? $u->username,
        //                 'score'  => $u->xp ?? 0,
        //                 'avatar' => $u->avatar ?? '/assets/icons/mascotss.png',
        //             ];
        //         })
        //     : collect(); // Kalau tabel gak ada, bikin array kosong

        // ==========================================
        // 2. DATA DUMMY (JIKA DATABASE KOSONG)
        // ==========================================
        // Ini otomatis jalan kalau kamu belum punya user student
        if ($allPlayers->isEmpty()) {
            $allPlayers = collect([
                // -- Juara 1, 2, 3 --
                (object)['name' => 'Si Paling Sepuh', 'score' => 9999, 'avatar' => null],
                (object)['name' => 'Murid Teladan', 'score' => 8500, 'avatar' => null],
                (object)['name' => 'Newbie Hoki', 'score' => 7200, 'avatar' => null],
                
                // -- Kategori: Jago Bangett (Rank 4-6) --
                (object)['name' => 'User Santuy', 'score' => 6000, 'avatar' => null],
                (object)['name' => 'Tukang AFK', 'score' => 5900, 'avatar' => null],
                (object)['name' => 'Cuma Numpang', 'score' => 5800, 'avatar' => null],

                // -- Kategori: Jago (Rank 7-9) --
                (object)['name' => 'Player Gabut', 'score' => 4000, 'avatar' => null],
                (object)['name' => 'Kang Rusuh', 'score' => 3900, 'avatar' => null],
                (object)['name' => 'Beban Tim', 'score' => 3800, 'avatar' => null],

                // -- Kategori: Lumayan Jago (Rank 10 dst) --
                (object)['name' => 'Adik Kelas', 'score' => 1000, 'avatar' => null],
                (object)['name' => 'Baru Daftar', 'score' => 500, 'avatar' => null],
                (object)['name' => 'Belum Main', 'score' => 100, 'avatar' => null],
            ]);
        }

        // ==========================================
        // 3. LOGIKA PEMBAGIAN KATEGORI (POTONG KUE)
        // ==========================================

        // A. Ambil 3 Teratas buat Podium (Juara 1, 2, 3)
        // .pad(3, null) fungsinya biar arraynya tetap 3 slot meski user cuma 1
        $topThree = $allPlayers->take(3)->pad(3, null);

        // B. Ambil SISANYA (Mulai dari ranking 4 ke bawah)
        // .values() buat mereset urutan nomor array dari 0 lagi
        $others = $allPlayers->slice(3)->values(); 

        // C. Masukkan ke Kotak Kategori
        $categories = [
            // Ambil 3 orang pertama dari sisa (Ranking 4, 5, 6)
            'Jago Bangett' => $others->take(3), 
            
            // Lewati 3 orang tadi, ambil 3 orang berikutnya (Ranking 7, 8, 9)
            'Jago' => $others->slice(3)->take(3), 
            
            // Lewati 6 orang pertama, ambil SEMUA sisanya (Ranking 10++)
            'Lumayan Jago' => $others->slice(6), 
        ];

        // ==========================================
        // 4. KIRIM KE VIEW
        // ==========================================
        return view('Leaderboard.leaderboard', compact('topThree', 'categories'));
    }
}