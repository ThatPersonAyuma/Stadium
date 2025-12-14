<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Block;
use App\Models\Course;
use App\Models\StudentContentProgress;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Enums\ContentType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

    public function play(Request $request, int $courseId, int $lessonId, int $contentId)
    {
        $user = Auth::user();
        if ($user->student->heart<=0)
        {
            return redirect()->route('course.detail', $courseId)->with('status', [
                'type' => 'error',
                'title' => 'Heart habis!',
                'message' => 'Tidak bisa melanjutkan lesson.'
            ]);
        }
        $lesson = Lesson::with([
            'contents' => fn ($q) => $q->orderBy('order_index')->with([
                'cards' => fn ($q) => $q->orderBy('order_index')->with([
                    'blocks' => fn ($b) => $b->orderBy('order_index'),
                ]),
            ]),
            'course',
        ])->findOrFail($lessonId);

        if ($lesson->course_id != $courseId) {
            abort(404);
        }

        $progress = (new CourseController)->getStudentCourseProgress($lesson->course, $user->student)['progress_percentage'];

        // Ambil content aktif
        $activeContent = $lesson->contents->firstWhere('id', $contentId)
            ?? abort(404, 'Content tidak ditemukan');

        // Semua card dalam content tersebut
        $allCards = $activeContent->cards ?? collect();

        // Card aktif
        $activeCardId = $request->integer('card');
        $activeCard = $allCards->firstWhere('id', $activeCardId) ?? $allCards->first();

        // Payload untuk: setiap card + block dalam card tersebut
        $cardsPayload = $activeContent->cards
            ->sortBy('order_index')
            ->map(function($card) {
                return [
                    'card_id' => $card->id,
                    'title'   => $card->content->title, // atau nama card jika beda
                    'order'   => $card->order_index,
                    'blocks'  => $card->blocks
                        ->sortBy('order_index')
                        ->map(function($block) {
                            return [
                                'id'    => $block->id,
                                'order' => $block->order_index,
                                'type'  => $block->type,
                                'data'  => $block->data,
                                'card_id' => $block->card_id,
                                'asset_url' => $block->asset_url ?? null, // optional
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();
        $result = StudentContentProgress::where('student_id', $user->student->id)->where('content_id', $contentId)->first();
        if ($result == NULL)
        {
            StudentContentProgress::create(
                [
                    'student_id' => $user->student->id,
                    'content_id' => $contentId,
                    'is_completed' => false,
                    'completed_at' => now(),
                ]
            );
        }
        return view('courses.student.lesson', [
            'courseId'      => $courseId,
            'lesson'        => $lesson,
            'progress'      => $progress,
            'contentId'     => $contentId,
            'activeCard'    => $activeCard,
            'cardsPayload'  => $cardsPayload, 
        ]);
    }

    protected function resolveBlockAsset(Block $block, Lesson $lesson, $card): ?string
    {
        $type = $block->type;
        if (! in_array($type, [ContentType::IMAGE, ContentType::GIF, ContentType::VIDEO])) {
            return null;
        }

        $contentId = $card->content_id ?? $card->content?->id;
        if (! $contentId) {
            return null;
        }

        return FileHelper::getBlockUrl(
            $lesson->course_id,
            $lesson->id,
            $contentId,
            $card->id,
            $block->id
        );
    }

    public function getById(int $lessonId)
    {   
        return Lesson::find($lessonId);
    }

    public function create(Request $request)
    {
        return response()->json([
            ['name' => 'title', 'type' => 'text', 'label' => 'Judul'],
            ['name' => 'description', 'type' => 'text', 'label' => 'Deskripsi'],
            ['name' => 'order_index', 'type' => 'number', 'label' => 'Urutan'],
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
            'description' => 'required|string',
            'order_index' => 'required|integer|min:1',
            'course_id' => 'required|integer|exist:course_id',
        ]);
        $nextOrder = (Lesson::where('course_id', $validated['course_id'])->max('order_index') ?? 0) + 1;
        $order = max(1, min($validated['order_index'], $nextOrder));
        $validated['order_index'] = $order;
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
    private function reposition_order_index(Lesson $Lesson, $new_index)
    {
        $old_index = $Lesson->order_index;
        if ($old_index == $new_index){
            return;
        }else if ($old_index > $new_index){
                Lesson::where('course_id', $Lesson->course_id)
                            ->whereBetween('order_index', [$new_index, $old_index-1])
                            ->increment('order_index');
        }else{
                Lesson::where('course_id', $Lesson->course_id)
                            ->whereBetween('order_index', [$old_index+1, $new_index])
                            ->decrement('order_index');
        }

        $Lesson->order_index=$new_index;
        $Lesson->save();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        Log::info("Fire");
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order_index' => 'required|integer|min:1',
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);
        $old_path = FileHelper::getFolderName($validated['course_id'], $lesson->course_id);
        if (file_exists($old_path)) {
        } else {
            return response()->json(['message' => 'Ini salah ew old path' . $old_path], 500);
        } 
        $maxOrder = Lesson::where('course_id', $lesson->course_id)->count();
        if ($validated['order_index']>$maxOrder){
            $validated['order_index']=$maxOrder;
        }
        Log::info($maxOrder);
        $content->title = $validated['title'];
        $content->description = $validated['description'];
        $content->course_id = $validated['course_id'];
        $this->reposition_order_index($lesson, $validated['order_index']);
        $new_path = FileHelper::getFolderName($validated['course_id'], $lesson->id);
        $result = rename($old_path, $new_path);
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
