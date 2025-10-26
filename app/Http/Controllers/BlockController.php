<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all(), $request->file());

        // return $request;
        if ($request->has('data') && is_string($request->data)) {
            $decoded = json_decode($request->data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['data' => $decoded]);
            } else {
                return response()->json(['error' => 'Invalid JSON format in data field'], 400);
            }
        }
        // dd($request->all(), $request->file());
        $validated = $request->validate([
            'type' => 'required|in:text,image,code,quiz,gif,video',
            'data' => 'required|array', // boleh dikirim sebagai array langsung
            'order_index' => 'required|integer|min:0',
            'card_id' => 'required|exists:cards,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,video|max:2048',
            'course_id' => 'required|integer',// $courseSlug
            'lesson_id' => 'required|integer', // $lessonSlug 
            'content_id' => 'required|integer', // $contentSlug
            'card_id' => 'required|integer', // $cardSlug
        ]);
        $type = $validated['type'];
        $validated['data']['filename'] = $validated['file']->getClientOriginalName();
        $block_data = [
            'type' => $validated['type'],
            'data' => $validated['data'],
            'order_index' => $validated['order_index'],
            'card_id' => $validated['card_id'],
        ];
        $block = Block::create($validated);

        if ($block === NULL){
            return response()->json(['error' => 'On creating Block Model'], 400);
        }
        match ($type)
        {
            'image', 'gif', 'video' => $data = FileHelper::storeBlockFile(
                                                    $validated['file'],
                                                    $validated['course_id'],
                                                    $validated['lesson_id'],
                                                    $validated['content_id'],
                                                    $validated['card_id'],
                                                    $block->id, // block_id
                                                ),
            default => $data = NULL,
        };
        return response()->json($block);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        //
    }
}
