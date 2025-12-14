<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\StudentContentProgress;
use App\Models\QuizParticipant;
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
        if (! $user || $user->role !== UserRole::STUDENT) {
            return respone()->json("Silakan login dengan aku student",403);
            // $user = User::where('role', 'student')->first();
        }
        $student = $user->student;
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
                ->withPivot(['progress', 'updated_at'])
                ->orderByDesc('course_user.updated_at')
                ->take(3)
                ->get()
            : collect();
        // Fallback: jika tabel pivot belum ada atau belum ada enrollment, ambil course dari progres konten siswa
        if ($courses->isEmpty() && $student) {
            $courses = StudentContentProgress::with('content.lesson.course')
                ->where('student_id', $student->id)
                ->orderByDesc('completed_at')
                ->get()
                ->map(fn ($p) => $p->content?->lesson?->course)
                ->filter()
                ->unique('id')
                ->take(3)
                ->values();
        }
        // Tambahkan warna dan progress ke setiap course
        $palette = ['#0B4DAE', '#0D5AC1', '#0F6CE5', '#0A4B8F', '#0D74D8', '#0F7BEA', '#1281F5', '#0E5EA8', '#0A5EC4'];
        $courses = $courses->values()->map(function ($course, $idx) use ($palette, $student) {
            if (empty($course->color)) {
                $course->color = $palette[$course->id % count($palette)] ?? $palette[$idx % count($palette)];
            }
            // Hitung progress jika belum tersedia di pivot
            if (isset($course->pivot) && $course->pivot?->progress !== null) {
                $course->progress = $course->pivot->progress;
            } elseif ($student) {
                $totalContents = $course->contents()->count();
                $completed = StudentContentProgress::where('student_id', $student->id)
                    ->where('is_completed', true)
                    ->whereHas('content.lesson', fn ($q) => $q->where('course_id', $course->id))
                    ->count();
                $course->progress = $totalContents > 0 ? round(($completed / $totalContents) * 100, 2) : 0;
            } else {
                $course->progress = 0;
            }
            return $course;
        });

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
        $leaderboard = $champions;

        $recentActivity = collect();

        if ($student) {
            $recentContent = StudentContentProgress::with('content.lesson.course')
                ->where('student_id', $student->id)
                ->where('is_completed', true)
                ->whereNotNull('completed_at')
                ->orderByDesc('completed_at')
                ->take(5)
                ->get()
                ->map(function ($progress) {
                    $courseTitle = $progress->content?->lesson?->course?->title;
                    $lessonTitle = $progress->content?->lesson?->title;
                    $contentTitle = $progress->content?->title;

                    $label = collect([$courseTitle, $lessonTitle, $contentTitle])
                        ->filter()
                        ->implode(' • ');

                    return [
                        'title'      => 'Completed: ' . ($label ?: 'Content'),
                        'time'       => $progress->completed_at?->diffForHumans(),
                        'timestamp'  => $progress->completed_at,
                    ];
                });

            $recentQuizzes = QuizParticipant::with('quiz')
                ->where('participant_id', $student->id)
                ->orderByDesc('updated_at')
                ->take(5)
                ->get()
                ->map(function ($participant) {
                    $quizTitle = $participant->quiz?->title ?? 'Quiz';
                    $timeStamp = $participant->updated_at ?? $participant->created_at;

                    return [
                        'title'     => 'Quiz: ' . $quizTitle,
                        'time'      => $timeStamp?->diffForHumans(),
                        'timestamp' => $timeStamp,
                    ];
                });

            $recentCourseProgress = $hasPivot
                ? $user->courses()
                    ->select('courses.*')
                    ->withPivot(['progress', 'updated_at'])
                    ->orderByDesc('course_user.updated_at')
                    ->take(5)
                    ->get()
                    ->map(function ($course) {
                        $timeStamp = $course->pivot?->updated_at;
                        return [
                            'title'     => 'Course progress: ' . ($course->name ?? 'Course') . ' (' . ($course->pivot?->progress ?? 0) . '%)',
                            'time'      => $timeStamp?->diffForHumans(),
                            'timestamp' => $timeStamp,
                        ];
                    })
                : collect();

            $recentActivity = collect()
                ->merge($recentContent)
                ->merge($recentQuizzes)
                ->merge($recentCourseProgress)
                ->filter(fn ($item) => ! empty($item['timestamp']))
                ->sortByDesc('timestamp')
                ->take(3)
                ->map(function ($item) {
                    return [
                        'title' => $item['title'],
                        'time'  => $item['time'] ?? '-',
                    ];
                })
                ->values();
        }

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
            // Statistik
            $stats = [
                'total_quizzes'      => Quiz::count(),
                'total_courses'      => Course::count(),
                'active_users'       => User::where('role', UserRole::STUDENT)->count(),
                'total_teachers'     => User::where('role', UserRole::TEACHER)->count(),
                'new_teachers_today' => 0, // tabel teachers tidak menyimpan timestamp reliably
                'new_courses_week'   => Course::where('created_at', '>=', now()->subWeek())->count(),
            ];

            $pendingTeachers = User::where('role', UserRole::TEACHER)
                ->with('teacher')
                ->whereHas('teacher', function ($q) {
                    $q->where('status', AccountStatus::WAITING);
                })
                ->get();

            $pendingCourses = Course::where('status', CourseStatus::PENDING)->get();
            $pendingQuizzes = Quiz::where('status', CourseStatus::PENDING)->get();

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
