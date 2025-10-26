<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua course
        $courses = Course::all();

        return response()->json($courses);
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
