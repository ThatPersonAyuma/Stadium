<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Enums\ContentType;

class BlockController extends Controller
{
    /** Scenario of Block
     *  'type' => ContentType::TEXT,
     *        'data' => [
     *              'title' => 'Mengapa Kita Belajar Pemrograman?',
     *              'body' => 'Tujuan utama dari belajar pemrograman adalah untuk memecahkan masalah dan menciptakan solusi digital yang efisien.',
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type','text'); // misal: quiz, video, article
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
                ['name' => 'data[content]', 'type' => 'text', 'label' => 'Teks'],
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
                Block::where('content_id', $block->content_id)
                            ->whereBetween('order_index', [$new_index, $old_index-1])
                            ->increment('order_index');
        }else{
                Block::where('content_id', $block->content_id)
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
        // dd($request->all(), $request->file());

        // return $request;
        // if ($request->has('data') && is_string($request->data)) {
        //     $decoded = json_decode($request->data, true);
        //     if (json_last_error() === JSON_ERROR_NONE) {
        //         $request->merge(['data' => $decoded]);
        //     } else {
        //         return response()->json(['error' => 'Invalid JSON format in data field'], 400);
        //     }
        // }
        $type = $request->input('type', 'text');
        
        $rules = [
            'type' => 'required|in:text,image,code,quiz,gif,video',
            'order_index' => 'required|integer|min:0',
            'card_id' => 'required|exists:cards,id',
            // 'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,video|max:2048',
            'course_id' => 'required|integer',// $courseSlug
            'lesson_id' => 'required|integer', // $lessonSlug 
            'content_id' => 'required|integer', // $contentSlug
            // 'card_id' => 'required|integer', // $cardSlug
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
            'image', 'gif', 'video' => [
                'data.file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4|max:10480',
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
        // return $request;
        $validated = $request->validate([
            ...$rules,
            ...$typeRules
        ]);
        return $validated;
        // return $request;
        // $data = $this->expandDotNotation($validated);
        // $validated['data'] = $data['data'];
        
        $type = $validated['type'];
        if ($type == 'image' || $type == 'gif' || $type == 'video'){
            $validated['data']['filename'] = $validated['data']['file']->getClientOriginalName();
        }
        $file = $validated['data']['file'];
        unset($validated['data']['file']);
        $block_data = [
            'type' => $validated['type'],
            'data' => $validated['data'],
            'order_index' => $validated['order_index'],
            'card_id' => $validated['card_id'],
        ];
        $block = Block::create($block_data);
        Block::where('card_id', $block->content_id)
            ->where('order_index', '>=', $block->order_index)
            ->increment('order_index');
        match ($type)
        {
            'image', 'gif', 'video' => $data = FileHelper::storeBlockFile(
                                                    $file,
                                                    $validated['course_id'],
                                                    $validated['lesson_id'],
                                                    $validated['content_id'],
                                                    $validated['card_id'],
                                                    $block->id, // block_id
                                                ),
            default => $data = NULL,
        };
        if ($block === NULL){
            return response()->json(['error' => 'On creating Block Model'], 400);
        }
        return response()->json($data);
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        $block->delete();

        return response()->json(null, 204);
    }
}
