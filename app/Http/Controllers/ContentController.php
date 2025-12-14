<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Enums\ContentType;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255', Rule::unique('contents', 'title')],
            'order_index' => ['required', 'integer', 'min:1'],
            'lesson_id'   => ['required', 'exists:lessons,id'],
            'course_id'   => ['nullable', 'integer'],
        ]);

        $lesson = Lesson::findOrFail($validated['lesson_id']);
        $courseId = $validated['course_id'] ?? $lesson->course_id;
        $nextOrder = (Content::where('lesson_id', $validated['lesson_id'])->max('order_index') ?? 0) + 1;
        $order = max(1, min($validated['order_index'], $nextOrder));
        $content = null;
        DB::transaction(function () use (&$content, $lesson, $validated, $order) {
            Content::where('lesson_id', $lesson->id)
                ->where('order_index', '>=', $order)
                ->increment('order_index');

            $content = Content::create([
                'title'       => $validated['title'],
                'order_index' => $order,
                'lesson_id'   => $lesson->id,
            ]);
        });

        return response()->json([
            'message' => 'Content berhasil ditambahkan',
            'content' => $content?->fresh(),
            'urls'    => [
                'update' => route('contents.update', $content),
                'delete' => route('contents.destroy', $content),
            ],
            'meta'    => [
                'course_id' => $courseId,
                'lesson_id' => $lesson->id,
            ],
        ], 201);
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
            'title'       => ['required', 'string', 'max:255', Rule::unique('contents', 'title')->ignore($content->id)],
            'order_index' => ['required', 'integer', 'min:1'],
            'course_id'   => ['required', 'integer'],
            'lesson_id'   => ['required', 'integer'],
        ]);

        if ((int) $validated['lesson_id'] !== $content->lesson_id) {
            return response()->json(['message' => 'Perpindahan content ke lesson lain belum didukung di halaman ini.'], 422);
        }

        $lesson = Lesson::findOrFail($validated['lesson_id']);
        $courseId = $validated['course_id'] ?? $lesson->course_id;
        $maxOrder = Content::where('lesson_id', $lesson->id)->max('order_index');
        if ($validated['order_index']>$maxOrder){
            $validated['order_index']=$maxOrder;
        }
        $oldPath = FileHelper::getFolderName($courseId, $lesson->id, $content->id);

        DB::transaction(function () use ($content, $lesson, $validated) {
            $newOrder = $validated['order_index'];
            if ($content->order_index !== $newOrder) {
                if ($content->order_index > $newOrder) {
                    Content::where('lesson_id', $lesson->id)
                        ->whereBetween('order_index', [$newOrder, $content->order_index - 1])
                        ->increment('order_index');
                } else {
                    Content::where('lesson_id', $lesson->id)
                        ->whereBetween('order_index', [$content->order_index + 1, $newOrder])
                        ->decrement('order_index');
                }
            }

            $content->title = $validated['title'];
            $content->order_index = $newOrder;
            $content->save();
        });

        $newPath = FileHelper::getFolderName($courseId, $lesson->id, $content->id);
        $disk = Storage::disk('public');
        if ($oldPath !== $newPath && $disk->exists($oldPath)) {
            $disk->move($oldPath, $newPath);
        }

        return response()->json([
            'message' => 'Content berhasil diperbarui',
            'content' => $content->fresh(),
            'meta'    => [
                'course_id' => $courseId,
                'lesson_id' => $lesson->id,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content)
    {
        $content->loadMissing('lesson.course');

        $lessonId = $content->lesson_id;
        $courseId = $content->lesson?->course?->id;
        $folderPath = $courseId
            ? FileHelper::getFolderName($courseId, $lessonId, $content->id)
            : null;

        DB::transaction(function () use ($content, $lessonId) {
            $order = $content->order_index;
            $content->delete();
            Content::where('lesson_id', $lessonId)
                ->where('order_index', '>', $order)
                ->decrement('order_index');
        });

        if ($folderPath && Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }

        return response()->json(['message' => 'Content dihapus']);
    }
}
