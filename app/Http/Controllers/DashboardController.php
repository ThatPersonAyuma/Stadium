<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function student()
    {
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'username'  => 'student',
                'name'      => 'Student',
                'email'     => 'student@example.com',
                'password'  => bcrypt('password'),
                'xp'        => 10,
                'energy'    => 0,
            ]);
        }


        $courses = collect([
            (object)[
                'id' => 1,
                'name' => 'Algoritma',
                'topic' => 'Binary Search',
                'color' => '#FF3B3B',
                'pivot' => (object)['progress' => 90]
            ],
            (object)[
                'id' => 2,
                'name' => 'PBO',
                'topic' => 'CRUD',
                'color' => '#0047FF',
                'pivot' => (object)['progress' => 50]
            ],
            (object)[
                'id' => 3,
                'name' => 'PWEB',
                'topic' => 'Laravel',
                'color' => '#B000F7',
                'pivot' => (object)['progress' => 10]
            ]
        ]);

        $leaderboard = collect([
            (object)[
                'name'   => 'Damrowr',
                'score'  => 2001,
                'avatar' => '/assets/icons/mascotss.png',
                'pos'    => 1
            ],
            (object)[
                'name'   => 'Denmit',
                'score'  => 2000,
                'avatar' => '/assets/icons/mascotss.png',
                'pos'    => 2
            ],
            (object)[
                'name'   => 'Darma',
                'score'  => 1999,
                'avatar' => '/assets/icons/mascotss.png',
                'pos'    => 3
            ],
        ]);

        $recentActivity = [
            [
                'title' => 'Completed Quiz: Binary Search',
                'time'  => '2 hours ago',
            ],
            [
                'title' => 'Unlocked Badge: Fast Learner',
                'time'  => 'Yesterday',
            ],
            [
                'title' => 'Finished Module: Laravel Basics',
                'time'  => '3 days ago',
            ],
        ];

        return view('dashboard.student', [
            'user'           => $user,
            'courses'        => $courses,
            'leaderboard'    => $leaderboard,
            'recentActivity' => $recentActivity,
        ]);
    }

    public function teacher()
    {
        $teacher = (object) [
            'name'            => 'Bu Rani',
            'totalCourses'    => 3,
            'totalStudents'   => 74,
            'completedToday'  => 9,
        ];

        $courses = collect([
            (object) [
                'id'               => 11,
                'title'            => 'Algoritma Lanjut',
                'status'           => 'published',
                'color'            => '#2563EB',
                'total_students'   => 26,
                'completed_count'  => 18,
                'recent_completes' => [
                    ['name' => 'Sari', 'score' => 95, 'time' => '2 jam lalu'],
                    ['name' => 'Dedi', 'score' => 90, 'time' => 'Hari ini'],
                    ['name' => 'Rudi', 'score' => 84, 'time' => 'Kemarin'],
                ],
            ],
            (object) [
                'id'               => 12,
                'title'            => 'Dasar PBO',
                'status'           => 'draft',
                'color'            => '#F97316',
                'total_students'   => 20,
                'completed_count'  => 8,
                'recent_completes' => [
                    ['name' => 'Tika', 'score' => 87, 'time' => '1 hari lalu'],
                    ['name' => 'Yuda', 'score' => 81, 'time' => '2 hari lalu'],
                ],
            ],
            (object) [
                'id'               => 13,
                'title'            => 'Laravel Web',
                'status'           => 'published',
                'color'            => '#10B981',
                'total_students'   => 28,
                'completed_count'  => 14,
                'recent_completes' => [
                    ['name' => 'Nina', 'score' => 93, 'time' => '30 menit lalu'],
                    ['name' => 'Andi', 'score' => 89, 'time' => 'Hari ini'],
                ],
            ],
        ]);

        $summary = [
            'courses'   => $courses->count(),
            'students'  => $courses->sum('total_students'),
            'completed' => $courses->sum('completed_count'),
        ];

        return view('dashboard.teacher', compact('teacher', 'courses', 'summary'));
    }
}
