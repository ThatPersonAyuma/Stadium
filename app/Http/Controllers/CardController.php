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
            
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
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
            'order_index' => 'required|integer',
        ]);
        reposition_order_index($card, $validated['order_index']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return response()->json(null, 204);
    }
}
