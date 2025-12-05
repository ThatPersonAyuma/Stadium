<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Enums\CourseStatus;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Models\StudentContentProgress;
use App\Models\Student;
use App\Models\Content;

class CourseController extends Controller
{
    protected function defaultCourses()
    {
        return collect([
            ['id' => 1, 'title' => 'Algoritma', 'status' => 'activity',  'progress' => 90,  'color' => '#E53935'],
            ['id' => 2, 'title' => 'PBO',       'status' => 'activity',  'progress' => 50,  'color' => '#1D4ED8'],
            ['id' => 3, 'title' => 'PWEB',      'status' => 'activity',  'progress' => 90,  'color' => '#A21CAF'],
            ['id' => 4, 'title' => 'Matdas',    'status' => 'new',       'progress' => 0,   'color' => '#65A30D'],
            ['id' => 5, 'title' => 'Matdis',    'status' => 'new',       'progress' => 0,   'color' => '#06B6D4'],
            ['id' => 6, 'title' => 'Matdus',    'status' => 'completed', 'progress' => 100, 'color' => '#F97316'],
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user===NULL){
            return redirect()->route('login');;
        }
        if ($user->role == UserRole::TEACHER){
            return redirect()->route('teacher.courses.index');
        }
        // $student = $user && $user->role === 'student'
            // ? $user
            // : User::where('role', 'student')->first();
        $student = $user->student;
        $courses = Course::all()->map(function ($course) use ($student) {
                    $result = $this->getStudentCourseProgress($course, $student);
                    $status = $result['status'] ?? 'new';
                    $progress = $result['progress_percentage'] ?? 0;
                    $cta = match ($status) {
                        'completed' => 'Review Course',
                        'activity'  => 'Continue Learning',
                        default     => 'Start Learning',
                    };
                    $course->progress = $progress;
                    $course->cta = $cta;
                    $course->color = $course->color ?? '#1D4ED8';
                    $course->title = $course->title ?? 'Course';
                    $course->user_status = $status;
                    return $course;
                });

        $summary = [
            'all'       => $courses->count(),
            'activity'  => $courses->where('user_status', 'activity')->count(),
            'completed' => $courses->where('user_status', 'completed')->count(),
        ];


        return view('courses.student.index', compact('courses', 'summary'));
    }

    public function teacherIndex()
    {
        // $teacher = Auth::user()?->role === 'teacher'
        //     ? Auth::user()
        //     : User::where('role', 'teacher')->first();
        $user = Auth::user();
        if ($user==NULL){
            return;
        }
        $teacher = $user->teacher;
        $courses = Course::query()
            ->when($teacher, fn ($q) => $q->where('teacher_id', $teacher->id))
            ->withCount('lessons')
            ->latest()
            ->get();

        $summary = [
            'total'   => $courses->count(),
            'draft'   => $courses->where('status', CourseStatus::DRAFT)->count(),
            'pending' => $courses->where('status', CourseStatus::PENDING)->count(),
            'approved'=> $courses->where('status', CourseStatus::APPROVED)->count(),
        ];

        return view('courses.teacher.index', compact('courses', 'summary', 'teacher'));
    }

    public function teacherCreate()
    {
        return view('courses.teacher.create');
    }

    public function teacherShow(Course $course)
    {
        $course->load([
            'lessons' => fn ($q) => $q->orderBy('order_index')->with([
                'contents' => fn ($q) => $q->orderBy('order_index')->with([
                    'cards' => fn ($q) => $q->orderBy('order_index')->with([
                        'blocks' => fn ($q) => $q->orderBy('order_index'),
                    ]),
                ]),
            ]),
        ]);

        $stats = [
            'lessons'  => $course->lessons->count(),
            'contents' => $course->lessons->flatMap->contents->count(),
            'cards'    => $course->lessons->flatMap->contents->flatMap->cards->count(),
            'blocks'   => $course->lessons->flatMap->contents->flatMap->cards->flatMap->blocks->count(),
        ];

        return view('courses.teacher.show', compact('course', 'stats'));
    }

    public function teacherEdit(Course $course)
    {
        return view('courses.teacher.edit', compact('course'));
    }

    public function teacherLessonShow(Course $course, Lesson $lesson)
    {
        abort_if($lesson->course_id !== $course->id, 404);

        $lesson->load([
            'contents' => fn ($q) => $q->orderBy('order_index')->with([
                'cards' => fn ($q) => $q->orderBy('order_index')->with([
                    'blocks' => fn ($q) => $q->orderBy('order_index'),
                ]),
            ]),
        ]);

        $stats = [
            'contents' => $lesson->contents->count(),
            'cards'    => $lesson->contents->flatMap->cards->count(),
            'blocks'   => $lesson->contents->flatMap->cards->flatMap->blocks->count(),
        ];

        return view('courses.teacher.lessons.show', compact('course', 'lesson', 'stats'));
    }

    public function teacherLessonUpdate(Request $request, Course $course, Lesson $lesson)
    {
        abort_if($lesson->course_id !== $course->id, 404);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'nullable|integer|min:1',
        ]);

        if (isset($validated['order_index']) && $validated['order_index'] !== $lesson->order_index) {
            $exists = $course->lessons()
                ->where('order_index', $validated['order_index'])
                ->where('id', '!=', $lesson->id)
                ->exists();
            if ($exists) {
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Urutan lesson sudah digunakan.'], 422);
                }
                return back()
                    ->withErrors(['order_index' => 'Urutan lesson sudah digunakan.'])
                    ->withInput();
            }
            $lesson->order_index = $validated['order_index'];
        }

        $lesson->title = $validated['title'];
        $lesson->description = $validated['description'] ?? $lesson->description;
        $lesson->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Lesson berhasil diperbarui',
                'lesson'  => $lesson,
            ]);
        }

        return redirect()
            ->route('teacher.courses.lessons.show', [$course, $lesson])
            ->with('status', 'Lesson berhasil diperbarui.');
    }

    public function teacherDestroy(Request $request, Course $course)
    {
        // Pastikan hanya guru pemilik yang dapat menghapus (jika ada relasi teacher_id)
        $user = Auth::user();
        if ($user && $user->role === 'teacher' && $course->teacher_id && $course->teacher_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus course ini.');
        }

        $course->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Course berhasil dihapus']);
        }

        return redirect()
            ->route('teacher.courses.index')
            ->with('status', 'Course berhasil dihapus.');
    }

    public function detail(Course $course)
    {
        $user = Auth::user();

        if (!$user) return redirect()->route('login');
        if ($user->role !== UserRole::STUDENT) abort(403);

        // Load everything in one query
        $lessons = $course->lessons()
            ->with([
                'contents' => fn($q) => $q->orderBy('order_index'),
                'contents.student_content_progress'
            ])
            ->get();

        foreach ($lessons as $lesson) {
            $completed = $lesson->contents->filter(fn($c) =>
                $c->student_content_progress
                ->where('student_id', $user->student->id)
                ->where('is_completed', true)
                ->count() > 0
            );

            $highest = $completed->max('order_index') ?? 0;
            $unlockIndex = $highest + 1;

            foreach ($lesson->contents as $content) {
                if ($content->order_index <= $highest) {
                    $content->status = 'done';
                } elseif ($content->order_index == $unlockIndex) {
                    $content->status = 'current';
                } else {
                    $content->status = 'locked';
                }
            }
        }

        $progress = $this->getStudentCourseProgress($course, $user->student)['progress_percentage'];

        return view('courses.student.detail', [
            'course' => $course,
            'modules' => $lessons,
            'progress' => $progress
        ]);
    }

        // $courses = $this->defaultCourses()->map(function ($course) {
        //     $cta = match ($course['status']) {
        //         'completed' => 'Review Course',
        //         'activity'  => 'Continue Learning',
        //         default     => 'Start Learning',
        //     };

        //     return (object) array_merge($course, ['cta' => $cta]);
        // });

        // $course = Course::findOrFail();

        // $modules = collect([
        //     [
        //         'title'   => 'Pertemuan 1',
        //         'desc'    => 'Bilangan dan Operasi Hitung',
        //         'lessons' => [
        //             ['id' => 11, 'status' => 'done'],
        //             ['id' => 12, 'status' => 'locked'],
        //             ['id' => 13, 'status' => 'locked'],
        //             ['id' => 14, 'status' => 'locked'],
        //             ['id' => 15, 'status' => 'locked'],
        //             ['id' => 16, 'status' => 'locked'],
        //         ],
        //     ],
        //     [
        //         'title'   => 'Pertemuan 2',
        //         'desc'    => 'Perhitungan Linear',
        //         'lessons' => [
        //             ['id' => 21, 'status' => 'current'],
        //             ['id' => 22, 'status' => 'locked'],
        //             ['id' => 23, 'status' => 'locked'],
        //             ['id' => 24, 'status' => 'locked'],
        //             ['id' => 25, 'status' => 'locked'],
        //             ['id' => 26, 'status' => 'locked'],
        //         ],
        //     ],
        // ]);

    public function getCoursesAvailable()
    {
        $courses = Course::where('status', CourseStatus::Approved)->get();
    }

    public function getAllLessonOFACourse(Course $course)
    {
        return response()->json($course->lessons()->with('contents')->get());
    }


    public function create()
    {
        return view('courses.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:draft,pending,approved,revision,rejected,hidden,archived',
            // 'teacher_id'  => 'nullable|integer|exists:users,id',
        ]);

        // $teacherId = $validated['teacher_id']
        //     ?? (Auth::check() ? Auth::id() : null)
        //     ?? User::where('role', UserRole::TEACHER)->value('id');
        $user = Auth::user();
        if ($user===NULL){
            return redirect()->route('login');;
        }

        $course = Course::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? '',
            'status'      => $validated['status'] ?? 'draft',
            'teacher_id'  => $user->teacher->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($course, 201);
        }

        return redirect()
            ->route('teacher.courses.index')
            ->with('status', 'Course berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Jika ingin sekaligus tampilkan relasi lesson:
        // $course->load('lessons');

        return response()->json($course);
    }

    public function teacherLessonStore(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'nullable|integer|min:1',
        ]);

        if (isset($validated['order_index'])) {
            $exists = $course->lessons()
                ->where('order_index', $validated['order_index'])
                ->exists();
            if ($exists) {
                return response()->json([
                    'message' => 'Urutan lesson sudah digunakan.',
                ], 422);
            }
            $nextOrder = $validated['order_index'];
        } else {
            $nextOrder = ($course->lessons()->max('order_index') ?? 0) + 1;
        }

        $lesson = $course->lessons()->create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? '',
            'order_index' => $nextOrder,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Lesson berhasil ditambahkan',
                'lesson'  => $lesson,
            ], 201);
        }

        return redirect()
            ->back()
            ->with('status', 'Lesson berhasil ditambahkan.');
    }

    public function teacherLessonDestroy(Course $course, Lesson $lesson)
    {
        abort_if($lesson->course_id !== $course->id, 404);
        $lesson->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Lesson berhasil dihapus',
            ]);
        }

        return redirect()
            ->back()
            ->with('status', 'Lesson berhasil dihapus.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:draft,pending,approved,revision,rejected,hidden,archived',
            'teacher_id'  => 'nullable|integer|exists:users,id',
        ]);

        $course->title = $validated['title'];
        $course->description = $validated['description'] ?? $course->description;
        $course->status = $validated['status'] ?? $course->status;

        if (! empty($validated['teacher_id'])) {
            $course->teacher_id = $validated['teacher_id'];
        }

        $course->save();

        if ($request->wantsJson()) {
            return response()->json($course);
        }

        return redirect()
            ->route('teacher.courses.index')
            ->with('status', 'Course berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(null, 204);
    }

    public function getStudentCourseProgress(Course $course, Student $student)
    {
        $totalContents = $course->contents()->count();

        $completedCount = StudentContentProgress::where('student_id', $student->id)
            ->whereIn('content_id', $course->contents()->pluck('contents.id'));
            // ->where('is_completed', true)
            // ->count();
        $contentCounted = $completedCount->count();
        $contentCountedCompleted = $completedCount->where('is_completed', true)->count();
        if ($totalContents>0){
            $percentage = round(($contentCountedCompleted / $totalContents) * 100, 2);
        }else{
            $percentage = 0;
        }
        // $percentage = $totalContents > 0
        //     ? round(($completedCount / $totalContents) * 100, 2)
        //     : 0;
        if ($contentCountedCompleted > 0){
            if ($percentage==100){
                $status = 'completed';
            }else{
                $status = 'activity';
            }
        }else{
            $status = 'new';
        }
        // $status = $contentCounted > 0
        //     ? 'new'
        //     : ( ? '':'new');
        return [
            'course_id' => $course->id,
            'total_contents' => $totalContents,
            'completed' => $contentCountedCompleted,
            'status' => $status,
            'progress_percentage' => $percentage,
        ];

        return response()->json([
            'course_id' => $course->id,
            'total_contents' => $totalContents,
            'completed' => $contentCountedCompleted,
            'condition' => $condition,
            'progress_percentage' => $percentage,
        ]);
    }

}
