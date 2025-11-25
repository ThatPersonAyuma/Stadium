<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua course
        $lessons = Lesson::all();

        return response()->json($lessons);
    }

    protected function sampleLessonContent(int $lessonId): array
    {
        $samples = [
            11 => [
                'title'    => 'Bilangan dan Operasi Hitung',
                'subtitle' => 'Pembagian Dasar',
                'question' => 'Hasil dari 48 ÷ 6 adalah ...',
                'progress' => 25,
                'options'  => ['6', '8', '7', '9'],
            ],
            21 => [
                'title'    => 'Perhitungan Linear',
                'subtitle' => 'Latihan Perkalian',
                'question' => 'Hitung hasil 7 × 8 adalah ...',
                'progress' => 40,
                'options'  => ['48', '52', '54', '56'],
            ],
        ];

        return $samples[$lessonId] ?? [
            'title'    => 'Bilangan dan Operasi Hitung',
            'subtitle' => 'Latihan Pembagian',
            'question' => 'Hasil dari 48 ÷ 6 adalah ...',
            'progress' => 20,
            'options'  => ['6', '7', '8', '9'],
        ];
    }

    public function play(int $courseId, int $lessonId)
    {
        $lesson = $this->sampleLessonContent($lessonId);

        return view('courses.lesson', [
            'courseId' => $courseId,
            'lessonId' => $lessonId,
            'lesson'   => $lesson,
            'progress' => $lesson['progress'] ?? 0,
        ]);
    }

    public function getById(int $lessonId)
    {   
        return Lesson::find($lessonId);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order_index' => 'required|integer|min:1',
            'course_id' => 'required|integer|exist:course_id',
        ]);

        // Simpan course baru
        $lesson = Lesson::create($validated);

        return response()->json($lesson, 201);
    }

    /**
     * Display the specified resource.
     */
    public function getRelationWithCourse(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
        ]);
        $lesson = Lesson::where('course_id', $validated['course_id'])
                    ->orderBy('order_index')
                    ->get();
        
        return response()->json($lesson);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        // Jika ingin sekaligus tampilkan relasi lesson:
        // $course->load('lessons');
        
        return response()->json($lesson);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order_index' => 'required|integer|min:1',
            'course_id' => 'required|integer|exist:course_id',
        ]);

        $lesson->update($validated);

        return response()->json($lesson);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json(null, 204);
    }
}
