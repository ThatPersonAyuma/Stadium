<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;


class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::all();

        return response()->json($cards);
    }

    public function getById(int $cardId)
    {   
        return Card::find($cardId);
    }

        public function getBlocksOfCard(Card $card)
    {
        return $card->blocks()->orderBy('order_index')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            ['name' => 'order_index', 'type' => 'number', 'label' => 'Urutan'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_index' => 'nullable|integer|min:1',
            'content_id'  => 'required|exists:contents,id',
        ]);

        $content = \App\Models\Content::findOrFail($validated['content_id']);
        $nextOrder = $validated['order_index'] ?? (($content->cards()->max('order_index') ?? 0) + 1);

        // prevent duplicate order_index
        if ($content->cards()->where('order_index', $nextOrder)->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Urutan card sudah digunakan.'], 422);
            }
            return back()->withErrors(['order_index' => 'Urutan card sudah digunakan.'])->withInput();
        }

        $card = $content->cards()->create([
            'order_index' => $nextOrder,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message'     => 'Card berhasil dibuat',
                'card'        => $card,
                'detail_url'  => route('cards.show', $card),
                'delete_url'  => route('cards.destroy', $card),
                'update_url'  => route('cards.update', $card),
            ], 201);
        }

        return back()->with('status', 'Card berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        $card->load([
            'content.lesson.course',
            'blocks' => fn ($q) => $q->orderBy('order_index'),
        ]);

        return view('courses.teacher.cards.show', compact('card'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    private function reposition_order_index(Card $card, $new_index)
    {
        $old_index = $card->order_index;
        if ($old_index == $new_index){
            return;
        }else if ($old_index > $new_index){
                Card::where('content_id', $card->content_id)
                            ->whereBetween('order_index', [$new_index, $old_index-1])
                            ->increment('order_index');
        }else{
                Card::where('content_id', $card->content_id)
                            ->whereBetween('order_index', [$old_index+1, $new_index])
                            ->decrement('order_index');
        }

        $card->order_index=$new_index;
        $card->save();
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'order_index' => 'required|integer|min:1',
        ]);
        $maxOrder = ($card->content?->cards()->max('order_index') ?? 0);
        $newOrder = min($validated['order_index'], max(1, $maxOrder));
        $this->reposition_order_index($card, $newOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Card diperbarui',
                'card' => $card->fresh(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        // $card->load('content.lesson.course');
        // if (!FileHelper::deleteFolder($card->content->lesson->course->id, $card->content->lesson->id, $card->content->id, $card->id))
        // {
        //     Log::error("Gagal menghapus folder untuk card ID: {$card->id}");
        // }
        $card->delete();

        return response()->json(['message' => 'Card dihapus']);
    }
}
