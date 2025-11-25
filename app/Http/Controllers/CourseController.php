<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Enums\CourseStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        $courses = $this->defaultCourses()->map(function ($course) {
            $cta = match ($course['status']) {
                'completed' => 'Review Course',
                'activity'  => 'Continue Learning',
                default     => 'Start Learning',
            };

            return (object) array_merge($course, ['cta' => $cta]);
        });

        $summary = [
            'all'       => $courses->count(),
            'activity'  => $courses->where('status', 'activity')->count(),
            'completed' => $courses->where('status', 'completed')->count(),
        ];

        return view('courses.course', compact('courses', 'summary'));
    }

    public function teacherIndex()
    {
        $teacher = Auth::user()?->role === 'teacher'
            ? Auth::user()
            : User::where('role', 'teacher')->first();

        $courses = Course::query()
            ->when($teacher, fn ($q) => $q->where('teacher_id', $teacher->id))
            ->withCount('lessons')
            ->latest()
            ->get();

        $summary = [
            'total'   => $courses->count(),
            'draft'   => $courses->where('status', 'draft')->count(),
            'pending' => $courses->where('status', 'pending')->count(),
            'approved'=> $courses->where('status', 'approved')->count(),
        ];

        return view('courses.teacher-index', compact('courses', 'summary', 'teacher'));
    }

    public function teacherCreate()
    {
        return view('courses.teacher-create');
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

        return view('courses.teacher-show', compact('course', 'stats'));
    }

    public function teacherEdit(Course $course)
    {
        return view('courses.teacher-edit', compact('course'));
    }

    public function detail(int $courseId)
    {
        $courses = $this->defaultCourses()->map(function ($course) {
            $cta = match ($course['status']) {
                'completed' => 'Review Course',
                'activity'  => 'Continue Learning',
                default     => 'Start Learning',
            };

            return (object) array_merge($course, ['cta' => $cta]);
        });

        $course = $courses->firstWhere('id', $courseId) ?? $courses->first();

        $modules = collect([
            [
                'title'   => 'Pertemuan 1',
                'desc'    => 'Bilangan dan Operasi Hitung',
                'lessons' => [
                    ['id' => 11, 'status' => 'done'],
                    ['id' => 12, 'status' => 'locked'],
                    ['id' => 13, 'status' => 'locked'],
                    ['id' => 14, 'status' => 'locked'],
                    ['id' => 15, 'status' => 'locked'],
                    ['id' => 16, 'status' => 'locked'],
                ],
            ],
            [
                'title'   => 'Pertemuan 2',
                'desc'    => 'Perhitungan Linear',
                'lessons' => [
                    ['id' => 21, 'status' => 'current'],
                    ['id' => 22, 'status' => 'locked'],
                    ['id' => 23, 'status' => 'locked'],
                    ['id' => 24, 'status' => 'locked'],
                    ['id' => 25, 'status' => 'locked'],
                    ['id' => 26, 'status' => 'locked'],
                ],
            ],
        ]);

        $totalLessons = $modules->sum(function ($m) {
            return count($m['lessons']);
        });
        $doneLessons = $modules->sum(function ($m) {
            return collect($m['lessons'])->where('status', 'done')->count();
        });
        $progress = $totalLessons > 0 ? round(($doneLessons / $totalLessons) * 100) : 0;

        return view('courses.course-detail', [
            'course'   => $course,
            'modules'  => $modules,
            'progress' => $progress,
        ]);
    }

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
        return view('courses.teacher-create');
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
            'teacher_id'  => 'nullable|integer|exists:users,id',
        ]);

        $teacherId = $validated['teacher_id']
            ?? (Auth::check() ? Auth::id() : null)
            ?? User::where('role', 'teacher')->value('id');

        $course = Course::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? '',
            'status'      => $validated['status'] ?? 'draft',
            'teacher_id'  => $teacherId,
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

        $nextOrder = $validated['order_index']
            ?? (($course->lessons()->max('order_index') ?? 0) + 1);

        $lesson = $course->lessons()->create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? '',
            'order_index' => $nextOrder,
        ]);

        return response()->json([
            'message' => 'Lesson berhasil ditambahkan',
            'lesson'  => $lesson,
        ], 201);
    }

    public function teacherLessonDestroy(Course $course, Lesson $lesson)
    {
        abort_if($lesson->course_id !== $course->id, 404);
        $lesson->delete();

        return response()->json([
            'message' => 'Lesson berhasil dihapus',
        ]);
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
}
