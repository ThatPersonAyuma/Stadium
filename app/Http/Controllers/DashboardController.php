<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Quiz;
use App\Models\Student;
use App\Enums\UserRole;
use App\Enums\CourseStatus;
use App\Enums\AccountStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    
    public function student()
    {
        $user = Auth::user();
        if (!$user && $user->role !== UserRole::STUDENT) {
            return respone()->json("Silakan login dengan aku student",403);
            // $user = User::where('role', 'student')->first();
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
        $teacher = $authUser && $authUser->role === UserRole::TEACHER
            ? $authUser->teacher
            : User::where('role', 'teacher')->first();
        // return $teacher;
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
            ->with([
                'contents.student_content_progress',
                'contents',
            ])
            ->when($hasEnrollmentPivot, function ($query) {
                $query->with([
                    'users' => fn ($q) => $q
                        ->where('role', 'student')
                        ->orderByPivot('updated_at', 'desc')
                ]);
            })
            ->get()
            ->map(function ($course) use ($hasEnrollmentPivot) {

                if (! $hasEnrollmentPivot) {
                    $course->recent_completes = collect();
                } else {
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
                }

                $contents = $course->contents;
                $totalContents = $contents->count();

                $progressByStudent = $contents
                    ->flatMap(fn($c) => $c->student_content_progress)
                    ->groupBy('student_id');

                $course->total_students = $progressByStudent->keys()->count();

                $course->completed_count = $progressByStudent
                    ->filter(function ($progress) use ($totalContents) {
                        return $progress->where('is_completed', true)->count() === $totalContents;
                    })
                    ->count();

                return $course;
            });

        $totalCourses = $courses->count();

        $allStudents = collect();
        $totalCompleted = 0;

        foreach ($courses as $course) {
            $contents = $course->contents;
            $totalContents = $contents->count();

            $progressByStudent = $contents
                ->flatMap(fn($c) => $c->student_content_progress)
                ->groupBy('student_id');

            $allStudents = $allStudents->merge($progressByStudent->keys());

            $completedInCourse = $progressByStudent
                ->filter(fn($progress) => $progress->where('is_completed', true)->count() === $totalContents)
                ->count();

            $totalCompleted += $completedInCourse;
        }

        $summary = [
            'courses'   => $totalCourses,
            'students'  => $allStudents->unique()->count(),
            'completed' => $totalCompleted,
        ];
        $teacherMeta = (object) [
            'name'           => $teacher->name ?? 'Teacher',
            'totalCourses'   => $summary['courses'],
            'totalStudents'  => $summary['students'],
        ];

        return view('dashboard.teacher', [
            'teacher' => $teacherMeta,
            'courses' => $courses,
            'summary' => $summary,
        ]);
    }
    
    public function admin()
        {
            // 1. STATISTIK UTAMA (Angka Karangan)
            $stats = [
                'total_quizzes'      => 24, 
                'total_courses'      => 12,
                'active_users'       => 1543,
                'total_teachers'     => 45,
                'new_teachers_today' => 3,
                'new_courses_week'   => 5,
            ];

            // 2. DUMMY LIST TEACHER (Pura-pura ada di database)
            // Struktur object disesuaikan biar cocok sama View ($t->user->name)
            $pendingTeachers = User::where('role', 'teacher')
                ->with('teacher')
                ->whereHas('teacher', function ($q) {
                    $q->where('status', AccountStatus::WAITING);
                })
                ->latest()
                ->get();

            // $pendingTeachers = collect([
            //     (object)[
            //         'id' => 1,
            //         'expertise' => 'Matematika Murni',
            //         'experience_years' => 5,
            //         'created_at' => Carbon::now()->subHours(2),
            //         'user' => (object)[ 'name' => 'Dr. Budi Santoso' ]
            //     ],
            //     (object)[
            //         'id' => 2,
            //         'expertise' => 'Fisika Kuantum',
            //         'experience_years' => 8,
            //         'created_at' => Carbon::now()->subDays(1),
            //         'user' => (object)[ 'name' => 'Prof. Sarah Wijaya' ]
            //     ],
            //     (object)[
            //         'id' => 3,
            //         'expertise' => 'Sastra Inggris',
            //         'experience_years' => 3,
            //         'created_at' => Carbon::now()->subDays(2),
            //         'user' => (object)[ 'name' => 'Andi Pratama, M.Pd' ]
            //     ],
            // ]);

            // 3. DUMMY LIST COURSE
            // Struktur: $c->teacher->user->name
            $pendingCourses = Course::where('status', CourseStatus::PENDING)->get();
            // $pendingCourses = collect([
            //     (object)[
            //         'id' => 101,
            //         'title' => 'Mastering Laravel 11',
            //         'category' => 'Web Development',
            //         'created_at' => Carbon::now()->subHours(5),
            //         'teacher' => (object)[
            //             'user' => (object)[ 'name' => 'Prof. Sarah Wijaya' ]
            //         ]
            //     ],
            //     (object)[
            //         'id' => 102,
            //         'title' => 'Dasar-Dasar Kalkulus',
            //         'category' => 'Matematika',
            //         'created_at' => Carbon::now()->subDays(1),
            //         'teacher' => (object)[
            //             'user' => (object)[ 'name' => 'Dr. Budi Santoso' ]
            //         ]
            //     ],
            //     (object)[
            //         'id' => 103,
            //         'title' => 'Speaking for IELTS',
            //         'category' => 'Bahasa',
            //         'created_at' => Carbon::now()->subDays(3),
            //         'teacher' => (object)[
            //             'user' => (object)[ 'name' => 'Andi Pratama, M.Pd' ]
            //         ]
            //     ]
            // ]);

            // 4. DUMMY LIST QUIZ
            // Struktur: $q->creator->user->name
            $pendingQuizzes = Quiz::where('status', CourseStatus::PENDING)->get();
            // $pendingQuizzes = collect([
            //     (object)[
            //         'id' => 501,
            //         'title' => 'Ujian Tengah Semester Aljabar',
            //         'questions_count' => 25,
            //         'created_at' => Carbon::now()->subMinutes(45),
            //         'creator' => (object)[
            //             'user' => (object)[ 'name' => 'Dr. Budi Santoso' ]
            //         ]
            //     ],
            //     (object)[
            //         'id' => 502,
            //         'title' => 'Tes Vocabulary Level 1',
            //         'questions_count' => 50,
            //         'created_at' => Carbon::now()->subHours(6),
            //         'creator' => (object)[
            //             'user' => (object)[ 'name' => 'Andi Pratama, M.Pd' ]
            //         ]
            //     ],
            //     (object)[
            //         'id' => 503,
            //         'title' => 'Kuis Logika Pemrograman',
            //         'questions_count' => 10,
            //         'created_at' => Carbon::now()->subDays(2),
            //         'creator' => (object)[
            //             'user' => (object)[ 'name' => 'Prof. Sarah Wijaya' ]
            //         ]
            //     ]
            // ]);

            // Kirim semua data dummy ke View
        
            return view('dashboard.admin', compact('stats', 'pendingTeachers', 'pendingCourses', 'pendingQuizzes'));
        }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role == UserRole::STUDENT) {
            return $this->student();
        }
        if ($user->role == UserRole::TEACHER) {
            return $this->teacher();
        } 
        if ($user->role == UserRole::ADMIN) {
            return $this->admin();
        }
    }
}
