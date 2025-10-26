<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Card;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cards = [
            [
                'order_index' => 1,
                'content_id' => 1,
            ],
            [
                'order_index' => 2,
                'content_id' => 1,
            ],
            [
                'order_index' => 3,
                'content_id' => 1,
            ],
            [
                'order_index' => 4,
                'content_id' => 1,
            ],
            [
                'order_index' => 1,
                'content_id' => 2,
            ],
            [
                'order_index' => 2,
                'content_id' => 2,
            ],
            [
                'order_index' => 3,
                'content_id' => 2,
            ],
            [
                'order_index' => 4,
                'content_id' => 2,
            ],
            [
                'order_index' => 5,
                'content_id' => 2,
            ],
            [
                'order_index' => 6,
                'content_id' => 2,
            ],
        ];
        foreach ($cards as $card) {
            Card::create($card);
        }
    }
}
