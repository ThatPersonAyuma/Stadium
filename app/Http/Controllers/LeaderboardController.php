<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rank;
use App\Models\Student;
use App\Helpers\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

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
        $champions = Student::with('user')
            ->orderByDesc('experience')
            ->take(3)
            ->get()
            ->map(function ($u) {
                return (object) [
                    'id' => $u->user->id,
                    'name'   => $u->user?->name ?? $u->user?->username ?? 'Unknown',
                    'score'  => $u->experience ?? 0,
                    'avatar_filename' => $u->user?->avatar_filename,
                ];
            });
        $leaderboard = $champions != NULL
            ? $champions
            : collect([
                (object)[
                    'name' => 'Damrowr',
                    'score' => 2001,
                    'avatar_filename' => '/assets/icons/mascotss.png'
                ],
                (object)[
                    'name' => 'Denmit',
                    'score' => 2000,
                    'avatar_filename' => '/assets/icons/mascotss.png'
                ],
                (object)[
                    'name' => 'Darma',
                    'score' => 1999,
                    'avatar_filename' => '/assets/icons/mascotss.png'
                ],
            ]);
        // A. Ambil 3 Teratas buat Podium (Juara 1, 2, 3)
        // .pad(3, null) fungsinya biar arraynya tetap 3 slot meski user cuma 1
        $topThree = $leaderboard;//$allPlayers->take(3)->pad(3, null);

        // B. Ambil SISANYA (Mulai dari ranking 4 ke bawah)
        // .values() buat mereset urutan nomor array dari 0 lagi
        $others = $allPlayers->slice(3)->values(); 

        $ranks = Rank::orderByDesc('min_xp')->get();
        $perPage = 10;

        $allStudents = Student::with('user','rank')
            ->orderByDesc('experience')
            ->get()
            ->values();

        $globalRankMap = [];
        foreach ($allStudents as $i => $student) {
            $globalRankMap[$student->id] = $i + 1;
            $student->global_rank = $i + 1;
        }

        $grouped = $allStudents->groupBy('rank_id');

        $rankStudents = [];

        foreach ($ranks as $rank) {
            $rankStudents[$rank->id] = Utils::paginateCollection(
                $grouped->get($rank->id, collect())->values(),
                $perPage,
                null,
                ['pageName' => "page_{$rank->id}"]
            );
        }
        session(['globalRankMap' => $globalRankMap]);

        // ==========================================
        // 4. KIRIM KE VIEW
        // ==========================================
        return view('leaderboard.leaderboard', compact('topThree', 'ranks', 'rankStudents'));
    }
    public function index2(Request $request)
    {
        $ranks = Rank::orderByDesc('min_xp')->get();
        $perPage = 10;

        $allStudents = Student::with('user','rank')
            ->orderByDesc('experience')
            ->get()
            ->values();

        $globalRankMap = [];
        foreach ($allStudents as $i => $student) {
            $globalRankMap[$student->id] = $i + 1;
            $student->global_rank = $i + 1;
        }

        $grouped = $allStudents->groupBy('rank_id');

        $rankStudents = [];

        foreach ($ranks as $rank) {
            $rankStudents[$rank->id] = Utils::paginateCollection(
                $grouped->get($rank->id, collect())->values(),
                $perPage,
                null,
                ['pageName' => "page_{$rank->id}"]
            );
        }
        session(['globalRankMap' => $globalRankMap]);
        return view('leaderboard.test', compact('ranks', 'rankStudents'));
    }


    public function fetch(Request $request)
    {
        Log::info($request->all());
        $rankId = $request->rank_id;
        $page   = $request->page ?? 1;

        $students = Student::with('user','rank')
            ->where('rank_id', $rankId)
            ->orderByDesc('experience')
            ->paginate(10, ['*'], 'page', $page);
        $globalRankMap = session('globalRankMap', []);
        foreach ($students as $s) {
            $s->global_rank = $globalRankMap[$s->id] ?? null;
        }
        $html = view('leaderboard.items', compact('students'))->render();
        return response()->json([
            'html' => $html,
            'next_page' => $students->hasMorePages() ? $page + 1 : null
        ]);
    }

}