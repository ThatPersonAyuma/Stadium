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
}
