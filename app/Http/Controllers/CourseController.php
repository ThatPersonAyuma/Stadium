<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Enums\CourseStatus;

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
        return response()->json([
            ['name' => 'title', 'type' => 'text', 'label' => 'Judul'],
            ['name' => 'description', 'type' => 'text', 'label' => 'Deskripsi'],
        ]);
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
            'title' => 'required|string|max:255',
            'description' => 'string',
        ]);
        $old_path = FileHelper::getFolderName($course->id);
        $content->title = $validated['title'];
        $content->description = $validated['description'];
        $new_path = FileHelper::getFolderName($course->id);
        $result = FileHelper::changeFolderName($new_path, $old_path);
        if (!$result){
            return response()->json("Path doesn't exist", 500);
        }
        $content->save();
        return response()->json(null, 204);
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
