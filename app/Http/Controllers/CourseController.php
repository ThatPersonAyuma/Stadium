<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Enums\CourseStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

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
        $student = $user && $user->role === 'student'
            ? $user
            : User::where('role', 'student')->first();

        $hasPivot = Schema::hasTable('course_user');
        $palette = ['#E53935', '#1D4ED8', '#A21CAF', '#65A30D', '#06B6D4', '#F97316', '#0EA5E9', '#9333EA'];

        $courses = Course::query()
            ->when($student && $hasPivot, function ($query) use ($student) {
                $query->with(['users' => fn ($q) => $q->where('users.id', $student->id)]);
            })
            ->get()
            ->values()
            ->map(function ($course, $idx) use ($palette, $student, $hasPivot) {
                $progress = 0;
                if ($student && $hasPivot && $course->users?->first()?->pivot) {
                    $progress = (int) ($course->users->first()->pivot->progress ?? 0);
                }
                $status = $progress >= 100 ? 'completed' : ($progress > 0 ? 'activity' : 'new');
                $cta = match ($status) {
                    'completed' => 'Review Course',
                    'activity'  => 'Continue Learning',
                    default     => 'Start Learning',
                };
                $course->status = $status;
                $course->progress = $progress;
                $course->cta = $cta;
                $course->color = $course->color ?? $palette[$idx % count($palette)];
                $course->title = $course->title ?? $course->name ?? 'Course';
                return $course;
            });

        $summary = [
            'all'       => $courses->count(),
            'activity'  => $courses->where('status', 'activity')->count(),
            'completed' => $courses->where('status', 'completed')->count(),
        ];


        return view('courses.student.index', compact('courses', 'summary'));
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

    public function detail(int $courseId)
    {
        $course = Course::with([
            'lessons' => fn ($q) => $q->orderBy('order_index')->with([
                'contents' => fn ($q) => $q->orderBy('order_index')->with([
                    'cards' => fn ($q) => $q->orderBy('order_index'),
                ]),
            ]),
        ])->findOrFail($courseId);

        $modules = $course->lessons->map(function ($lesson) {
            $cards = $lesson->contents->flatMap(function ($content) {
                return $content->cards->map(function ($card) use ($content) {
                    return [
                        'id'            => $card->id,
                        'order_index'   => $card->order_index,
                        'content_title' => $content->title,
                    ];
                });
            })->values();

            return [
                'lesson_id'   => $lesson->id,
                'title'       => $lesson->title,
                'desc'        => $lesson->description ?? 'Siap belajar materi ini.',
                'order_index' => $lesson->order_index,
                'cards'       => $cards,
            ];
        });

        $totalLessons = max(1, $modules->count());
        $progress = 0;

        return view('courses.student.detail', compact('course', 'modules', 'progress'));
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
}
