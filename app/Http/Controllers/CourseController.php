<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

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

    public function getById(int $courseId)
    {   
        return Course::find($courseId);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Simpan course baru
        $course = Course::create($validated);

        return response()->json($course, 201);
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'string',
        ]);

        $course->update($validated);

        return response()->json($course);
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
