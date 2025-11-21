<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = Content::all();

        return response()->json($contents);
    }
    
    public function getById(int $contentId)
    {   
        return Content::find($contentId);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            ['name' => 'title', 'type' => 'text', 'label' => 'Judul'],
            ['name' => 'order_index', 'type' => 'number', 'label' => 'Urutan'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order_index' => 'required|integer',
            'course_id' => 'required|integer',// $courseSlug
            'lesson_id' => 'required|integer', // $lessonSlug 
        ]);
        $old_path = FileHelper::getFolderName($validated['course_id'], $validated['lesson_id'], $content->id);
        if (file_exists($old_path)) {
        } else {
            return response()->json(['message' => 'Ini salah ew old path' . $old_path], 500);
        }
        $content->title = $validated['title'];
        $content->order_index = $validated['order_index'];
        $content->lesson_id = $validated['lesson_id'];
        $content->save();
        $new_path = FileHelper::getFolderName($validated['course_id'], $validated['lesson_id'], $content->id);
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
    public function destroy(Content $content)
    {
        $content->delete();

        return response()->json(null, 204);
    }
}
