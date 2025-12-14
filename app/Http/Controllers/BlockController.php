<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Content;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Helpers\Utils;
use App\Enums\ContentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StudentContentProgress;

class BlockController extends Controller
{
    /** Scenario of Block
     *  'type' => ContentType::TEXT,
     *        'data' => [
     *              'content' => 'Tujuan utama dari belajar pemrograman adalah untuk memecahkan masalah dan menciptakan solusi digital yang efisien.',
     *           ],
      *          'order_index' => 1,
       *         'card_id' => 2,
     * 'type' => ContentType::GIF,
     *        'data' => [
     *            'filename' => 'coding_process.gif',
     *            'alt' => 'Contoh proses coding sederhana.',
     *        ],
     *        'order_index' => 2,
     *        'card_id' => 2,
     * 'type' => ContentType::IMAGE,
    *          'data' => [
    *               'filename' => 'programming_flowchart.png',
    *               'alt' => 'Ilustrasi alur berpikir dalam pemrograman.',
    *           ],
    *           'order_index' => 2,
    *           'card_id' => 1,
    *   'type' => ContentType::QUIZ,
    *          'data' => [
    *               'question' => 'Pemrograman digunakan untuk apa?',
    *               'choices' => [
    *                   'A' => 'Menggambar di komputer',
    *                   'B' => 'Menulis instruksi untuk komputer',
    *                   'C' => 'Membuat musik',
    *                   'D' => 'Menjalankan hardware secara manual',
    *               ],
    *               'answer' => 'B',
    *               'explanation' => 'Pemrograman adalah menulis instruksi agar komputer melakukan sesuatu secara otomatis.',
    *           ],
    *           'order_index' => 4,
    *           'card_id' => 1,

    *'type' => ContentType::CODE,
    *   'data' => [
    *    'code' => 
    *    'language' =>
    *  ]
    *  'order_index'
    *  'card_id' => 1
     */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blocks = Block::all();

        return response()->json($blocks);
    }

    public function getType()
    {
        return response()->json(array_column(ContentType::cases(), 'value'));
    }

    /**
     * Show the form data needed for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type','text'); 
        $card_id  = $request->get('card_id','number');
        // return $card_id;
        $maxOrder = Block::where('card_id', $card_id)
            ->max('order_index') + 1;
        $common = [
            ['name' => 'order_index', 'type' => 'number" min="1" max="'. $maxOrder .'" step="1', 'label' => 'Urutan'],
        ];

        $jsonbSchema = match ($type) {
            'quiz' => [
                ['name' => 'data[question]', 'type' => 'text', 'label' => 'Pertanyaan'],
                ['name' => 'data[choices][A]', 'type' => 'text', 'label' => 'Pilihan A'],
                ['name' => 'data[choices][B]', 'type' => 'text', 'label' => 'Pilihan B'],
                ['name' => 'data[choices][C]', 'type' => 'text', 'label' => 'Pilihan C'],
                ['name' => 'data[choices][D]', 'type' => 'text', 'label' => 'Pilihan D'],
                ['name' => 'data[answer]', 'type' => 'text', 'label' => 'Jawaban (A/B/C/D)'],
                ['name' => 'data[explanation]', 'type' => 'textarea', 'label' => 'Penjelasan'],
            ],
            'image', 'gif', 'video' =>[
                ['name' => 'data[file]', 'type' => 'file', 'label' => 'File'],
                ['name' => 'data[alt]', 'type' => 'text', 'label' => 'Alternative Name'],
            ],
            'text' => [
                ['name' => 'data[content]', 'type' => 'textarea', 'label' => 'Teks'],
            ],
            'code' => [
                ['name' => 'data[language]', 'type' => 'text', 'label' => 'Language'],
                ['name' => 'data[code]', 'type' => 'text', 'label' => 'Code'],
            ],
            default => ['This is default']
        };

        return response()->json([
            ...$common,
            ...$jsonbSchema,
        ]);
    }

    private function expandDotNotation(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $segments = explode('.', $key);
            $ref =& $result;

            foreach ($segments as $segment) {
                if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                    $ref[$segment] = [];
                }
                $ref =& $ref[$segment];
            }

            // assign value (overwrite)
            $ref = $value;
        }

        return $result;
    }


    private function reposition_order_index(Block $block, $new_index)
    {
        $old_index = $block->order_index;
        if ($old_index == $new_index){
            return;
        }else if ($old_index > $new_index){
                Block::where('card_id', $block->card_id)
                            ->whereBetween('order_index', [$new_index, $old_index-1])
                            ->increment('order_index');
        }else{
                Block::where('card_id', $block->card_id)
                            ->whereBetween('order_index', [$old_index+1, $new_index])
                            ->decrement('order_index');
        }

        $block->order_index=$new_index;
        $block->save();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $type = $request->input('type', 'text');
        Log::info($request->all());
        Log::info('first');
        $type = $request['type'];
        $rules = [
            'type' => 'required|in:text,image,code,quiz,gif,video',
            'order_index' => 'required|integer|min:1',
            'card_id' => 'required|exists:cards,id',
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'content_id' => 'required|integer',
        ];
        $typeRules = match ($type) {
            'quiz' => [
                'data.question' => 'required|string',
                'data.choices.A' => 'required|string',
                'data.choices.B' => 'required|string',
                'data.choices.C' => 'required|string',
                'data.choices.D' => 'required|string',
                'data.answer' => 'required|string|in:A,B,C,D',
                'data.explanation' => 'required|string',
            ],
            'image' => [
                'data.file' => 'required|file|mimes:jpg,jpeg,png|max:5240',
                'data.alt' => 'nullable|string'
            ],
            'gif' => [
                'data.file' => 'required|file|mimes:gif|max:5240',
                'data.alt' => 'nullable|string'
            ],
            'video' => [
                'data.file' => 'required|file|mimes:mp4|max:10480',
                'data.alt' => 'nullable|string'
            ],
            'text' => [
                'data.content' => 'required|string',
            ],
            'code' => [
                'data.language' => 'required|string',
                'data.code' => 'required|string',
            ],
            default => []
        };
        Log::info('second');
        $validated = $request->validate([
            ...$rules,
            ...$typeRules
        ]);
        Log::info('third');
        // return ;
        $type = $validated['type'];
        $file = null;
        if (in_array($type, ['image', 'gif', 'video'], true) && isset($validated['data']['file'])) {
            $file = $validated['data']['file'];
            $validated['data']['filename'] = $file->getClientOriginalName();
            unset($validated['data']['file']);
        }

        $cardId = $validated['card_id'];
        $nextOrder = (Block::where('card_id', $cardId)->max('order_index') ?? 0) + 1;
        $order = max(1, min($validated['order_index'], $nextOrder));

        $block = null;
        DB::transaction(function () use (&$block, $validated, $order, $cardId) {
            Block::where('card_id', $cardId)
                ->where('order_index', '>=', $order)
                ->increment('order_index');

            $block = Block::create([
                'type' => $validated['type'],
                'data' => $validated['data'],
                'order_index' => $order,
                'card_id' => $cardId,
            ]);
        });

        if (!$block) {
            return response()->json(['message' => 'Gagal membuat block'], 500);
        }

        if ($file) {
            $stored = FileHelper::storeBlockFile(
                $file,
                $validated['course_id'],
                $validated['lesson_id'],
                $validated['content_id'],
                $validated['card_id'],
                $block->id
            );

            if ($stored === false) {
                return response()->json(['message' => 'Gagal menyimpan file block'], 500);
            }
        }

        return response()->json([
            'message' => 'Block berhasil dibuat',
            'block' => $block->fresh(),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        $type = $block->type;

        // return $card_id;
        $maxOrder = Block::where('card_id', $block->card_id)
            ->max('order_index') + 1;
        $common = [
            ['name' => 'order_index', 'type' => 'number" '.'value="'.$block->order_index.'" min="1" max="'. $maxOrder .'" step="1', 'label' => 'Urutan'],
        ];

        $jsonbSchema = match ($type) {
            ContentType::QUIZ => [
                ['name' => 'data[question]', 'type' => 'text', 'label' => 'Pertanyaan'],
                ['name' => 'data[choices][A]', 'type' => 'text', 'label' => 'Pilihan A'],
                ['name' => 'data[choices][B]', 'type' => 'text', 'label' => 'Pilihan B'],
                ['name' => 'data[choices][C]', 'type' => 'text', 'label' => 'Pilihan C'],
                ['name' => 'data[choices][D]', 'type' => 'text', 'label' => 'Pilihan D'],
                ['name' => 'data[answer]', 'type' => 'text', 'label' => 'Jawaban (A/B/C/D)'],
                ['name' => 'data[explanation]', 'type' => 'textarea', 'label' => 'Penjelasan'],
            ],
            ContentType::IMAGE, ContentType::GIF, ContentType::VIDEO =>[
                ['name' => 'data[file]', 'type' => 'file', 'label' => 'File'],
                ['name' => 'data[alt]', 'type' => 'text', 'label' => 'Alternative Name'],
            ],
            ContentType::TEXT => [
                ['name' => 'data[content]', 'type' => 'textarea', 'label' => 'Teks'],
            ],
            ContentType::CODE => [
                ['name' => 'data[language]', 'type' => 'text', 'label' => 'Language'],
                ['name' => 'data[code]', 'type' => 'text', 'label' => 'Code'],
            ],
            default => ['This is default']
        };

        return response()->json([
            'schema' => [...$common, ...$jsonbSchema],
            'value'  => $block, // <--- nilai awal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        $type = $block->type;
        Log::info('first');
        $rules = [
            'order_index' => 'required|integer|min:1',
            'card_id' => 'required|exists:cards,id',
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'content_id' => 'required|integer',
        ];
        $typeRules = match ($type) {
            ContentType::QUIZ => [
                'data.question' => 'required|string',
                'data.choices.A' => 'required|string',
                'data.choices.B' => 'required|string',
                'data.choices.C' => 'required|string',
                'data.choices.D' => 'required|string',
                'data.answer' => 'required|string|in:A,B,C,D',
                'data.explanation' => 'required|string',
            ],
            ContentType::IMAGE=> [
                'data.file' => 'required|file|mimes:jpg,jpeg,png|max:5240',
                'data.alt' => 'nullable|string'
            ],
            ContentType::GIF => [
                'data.file' => 'required|file|mimes:gif|max:5240',
                'data.alt' => 'nullable|string'
            ], 
            ContentType::VIDEO => [
                'data.file' => 'required|file|mimes:mp4|max:10480',
                'data.alt' => 'nullable|string'
            ], 
            ContentType::TEXT => [
                'data.content' => 'required|string',
            ],
            ContentType::CODE => [
                'data.language' => 'required|string',
                'data.code' => 'required|string',
            ],
            default => []
        };
        $validated = $request->validate([
            ...$rules,
            ...$typeRules
        ]);

        $maxOrder = Block::where('card_id', $block->card_id)
            ->max('order_index') + 1;

        $validated['order_index'] = max(1, min($validated['order_index'], $maxOrder));

        if ($block->type == ContentType::IMAGE || $block->type == ContentType::GIF || $block->type == ContentType::VIDEO){
            $result = FileHelper::deleteBlockFile($validated['course_id'], $validated['lesson_id'], $validated['content_id'], $validated['card_id'], $block->id);
            if (!$result){
                return response()->json(['message' => 'Gagal menghapus file lama'], 500);
            }
            $validated['data']['filename'] = $validated['data']['file']->getClientOriginalName();
            FileHelper::storeBlockFile(
                $validated['data']['file'],
                $validated['course_id'],
                $validated['lesson_id'],
                $validated['content_id'],
                $validated['card_id'],
                $block->id
            );
            unset($validated['data']['file']);
        }

        $block->data = $validated['data'];
        Log::info($validated['data']);

        self::reposition_order_index($block, $validated['order_index']);
        $block->save();
        return response()->json([
            'message' => 'Block diperbarui',
            'block' => $block->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        $cardId = $block->card_id;
        $order = $block->order_index;

        DB::transaction(function () use ($block, $cardId, $order) {
            $block->delete();
            Block::where('card_id', $cardId)
                ->where('order_index', '>', $order)
                ->decrement('order_index');
        });

        return response()->json(['message' => 'Block dihapus']);
    }

    public function check_answer(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'block_id'=> 'required|integer|min:1',
            'answer' => 'required|string',
            'content_id' => 'required|integer|min:1',
        ]);

        // Ambil block + relasi
        $block = Block::with('card.content')->findOrFail($validated['block_id']);

        // Cek jawaban
        $correctAnswer = $block->data['answer'];
        $isCorrect = ($validated['answer'] === $correctAnswer);

        // Kurangi heart jika salah
        $remain_heart = $user->student->heart;

        if (!$isCorrect) {
            $user->student->heart -= 1;
            $user->student->save(); // WAJIB
            $remain_heart = $user->student->heart;
        }

        // Jika heart habis (opsional)
        if ($remain_heart <= 0) {
            return response()->json([
                'status'   => 'error',
                'title'    => 'Heart habis!',
                'message'  => 'Tidak bisa melanjutkan lesson.',
                'redirect' => route('course.detail', $block->card->content->lesson->course->id)
            ]);
            return redirect()->route('course.detail', $block->card->content->lesson->course->id)->with('status', [
                'type' => 'error',
                'title' => 'Heart habis!',
                'message' => 'Tidak bisa melanjutkan lesson.'
            ]);
        }

        // Ambil list card dalam content tersebut

        return response()->json([
            'status' => 'ok',
            'correct' => $isCorrect,
            'correct_answer' => $correctAnswer,
            'remain_heart' => $remain_heart,
        ]);
    }
    public function finish_content(Request $request)
    {
        $user = Auth::user();
        // validasi input
        $validated = $request->validate([
            'content_id' => 'required|integer|min:1',
        ]);
        
        // Ambil content + lesson
        $content = Content::with('lesson')->findOrFail($validated['content_id']);
        $progress1 = StudentContentProgress::where('student_id', $user->student->id)->where('content_id', $content->id)->first();
        if ($progress1 != NULL)
        {
            if ($progress1->is_completed)
            {
                return response()->json([
                    'status' => 'ok',
                    'is_done' => true,
                    'redirect' => route('course.detail', [
                        'course'  => $content->lesson->course_id,
                    ])
                ]);
            }
        }

        // Tandai progres selesai
        StudentContentProgress::updateOrCreate(
            [
                'student_id' => $user->student->id,
                'content_id' => $content->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );
        $old_exp = $user->student->experience;
        $rank = Utils::add_exp_student($content->experience, $user->student->id);
        return response()->json([
            'status' => 'ok',
            'redirect' => route('course.detail', [
                'course'  => $content->lesson->course_id,
            ]), 
            'rank' => $rank,
            'exp_gain' => $content->experience,
            'exp_before' => $old_exp,
        ]);
    }
}
