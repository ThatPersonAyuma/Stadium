<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;

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
        if (file_exists($old_path)) {
        } else {
            return response()->json(['message' => 'Ini salah ew old path' . $old_path], 500);
        }
        $content->title = $validated['title'];
        $content->description = $validated['description'];
        $content->save();
        $new_path = FileHelper::getFolderName($course->id);
        $result = rename($old_path, $new_path);
        if ($result){
            return response()->json(['message' => 'Resource created successfully' . $old_path . $new_path], 200); // 201 Created
        }else{
            return response()->json(['message' => 'Failed to change folder'], 500); // 201 Created
        }
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
