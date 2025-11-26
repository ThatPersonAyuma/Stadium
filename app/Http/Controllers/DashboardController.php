<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    
    public function student()
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'student') {
            $user = User::where('role', 'student')->first();
        }
        // Fallback demo user
        if (! $user) {
            $user = User::create([
                'username'  => 'student_demo',
                'name'      => 'Student Demo',
                'email'     => 'student@example.com',
                'password'  => bcrypt('password'),
                'role'      => 'student',
                'xp'        => 10,
                'energy'    => 0,
            ]);
        }

        $hasPivot = Schema::hasTable('course_user');
        $courses = $hasPivot
            ? $user->courses()
                ->select('courses.*')
                ->withPivot('progress')
                ->get()
            : collect();

        if ($courses->isEmpty()) {
            // Demo data if belum ada relasi
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
        }

        $leaderboard = $hasPivot
            ? User::where('role', 'student')
                ->select('name', 'xp', 'avatar')
                ->orderByDesc('xp')
                ->take(5)
                ->get()
                ->map(function ($u) {
                    return (object)[
                        'name'   => $u->name ?? $u->username,
                        'score'  => $u->xp ?? 0,
                        'avatar' => $u->avatar ?? '/assets/icons/mascotss.png',
                    ];
                })
            : collect([
                (object)[ 'name' => 'Damrowr', 'score' => 2001, 'avatar' => '/assets/icons/mascotss.png' ],
                (object)[ 'name' => 'Denmit',  'score' => 2000, 'avatar' => '/assets/icons/mascotss.png' ],
                (object)[ 'name' => 'Darma',   'score' => 1999, 'avatar' => '/assets/icons/mascotss.png' ],
            ]);

        $recentActivity = [
            ['title' => 'Completed Quiz: Binary Search',     'time' => '2 hours ago'],
            ['title' => 'Unlocked Badge: Fast Learner',      'time' => 'Yesterday'],
            ['title' => 'Finished Module: Laravel Basics',   'time' => '3 days ago'],
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
        $authUser = Auth::user();
        $teacher = $authUser && $authUser->role === 'teacher'
            ? $authUser
            : User::where('role', 'teacher')->first();

        // Fallback supaya halaman tetap bisa dibuka saat belum ada teacher.
        if (! $teacher) {
            $teacher = User::create([
                'username' => 'teacher_demo',
                'name'     => 'Demo Teacher',
                'email'    => 'teacher@example.com',
                'password' => bcrypt('password'),
            ]);
            $teacher->role = 'teacher';
            $teacher->save();
        }

        $hasEnrollmentPivot = Schema::hasTable('course_user');

        $courses = Course::query()
            ->where('teacher_id', $teacher->id)
            ->when($hasEnrollmentPivot, function ($query) {
                $query->withCount([
                    'users as total_students' => fn ($q) => $q->where('role', 'student'),
                    'users as completed_count' => fn ($q) => $q->where('role', 'student')->wherePivot('progress', '>=', 100),
                ])->with(['users' => fn ($q) => $q
                    ->where('role', 'student')
                    ->orderByPivot('updated_at', 'desc')
                ]);
            })
            ->get()
            ->map(function ($course) use ($hasEnrollmentPivot) {
                if (! $hasEnrollmentPivot) {
                    $course->total_students = 0;
                    $course->completed_count = 0;
                    $course->recent_completes = collect();
                    return $course;
                }

                $course->recent_completes = $course->users
                    ->take(5)
                    ->map(function ($student) {
                        $updatedAt = $student->pivot?->updated_at;
                        return [
                            'name'  => $student->name ?? $student->username,
                            'score' => $student->pivot?->progress ?? 0,
                            'time'  => $updatedAt ? Carbon::parse($updatedAt)->diffForHumans() : '-',
                        ];
                    });

                return $course;
            });

        $summary = [
            'courses'   => $courses->count(),
            'students'  => $courses->sum('total_students'),
            'completed' => $courses->sum('completed_count'),
        ];

        $completedToday = 0;
        if ($hasEnrollmentPivot) {
            $completedToday = $courses->sum(function ($course) {
                return $course->users
                    ->filter(function ($student) {
                        $progress = (int) ($student->pivot?->progress ?? 0);
                        $updatedAt = $student->pivot?->updated_at;
                        return $progress >= 100 && $updatedAt && Carbon::parse($updatedAt)->isToday();
                    })
                    ->count();
            });
        }

        $teacherMeta = (object) [
            'name'           => $teacher->name ?? 'Teacher',
            'totalCourses'   => $summary['courses'],
            'totalStudents'  => $summary['students'],
            'completedToday' => $completedToday,
        ];

        return view('dashboard.teacher', [
            'teacher' => $teacherMeta,
            'courses' => $courses,
            'summary' => $summary,
        ]);
    }
}
