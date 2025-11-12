<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\QuestionSent;

class QuizController extends Controller
{
    public function startQuestion(Request $request)
    {
        $questions = [
            [
                'question' => 'Siapa penemu bola lampu?',
                'options' => ['Thomas Edison', 'Nikola Tesla', 'Alexander Graham Bell', 'Albert Einstein'],
            ],
            [
                'question' => 'Apa ibu kota Jepang?',
                'options' => ['Tokyo', 'Osaka', 'Kyoto', 'Nagoya'],
            ],
            [
                'question' => 'Berapa hasil dari 7 x 8?',
                'options' => ['54', '56', '64', '58'],
            ],
        ];

        // Pilih pertanyaan acak
        $randomQuestion = $questions[array_rand($questions)];
        broadcast(new QuestionSent($request->quizId, $randomQuestion['question'], $randomQuestion['options']))->toOthers();

        // Return ke pengirim juga
        return response()->json([
            'status' => 'ok'.$request->quizId,
            'question' => $randomQuestion,
        ]);
    }
}
