<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionChoice;
use App\Models\QuizParticipant;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // ========================
        // 1. Create Quiz
        // ========================
        $quiz = Quiz::create([
            'title'         => 'Ujian Matematika Dasar',
            'description'   => 'Kuis untuk mengukur kemampuan numerik dasar.',
            'creator_id'    => 1, // pastikan teacher id = 1 ada
            'running_index' => null,
            'is_finished'   => false,
        ]);

        // ========================
        // 2. Create Questions
        // ========================
        $questions = [
            [
                'order_index' => 1,
                'question' => "Berapakah hasil 2 + 2?",
                'choices' => [
                    ['A', '2', false],
                    ['B', '3', false],
                    ['C', '4', true],
                    ['D', '5', false],
                ]
            ],
            [
                'order_index' => 2,
                'question' => "Ibu memiliki 10 apel, diberikan 4. Sisa?",
                'choices' => [
                    ['A', '5', false],
                    ['B', '6', true],
                    ['C', '7', false],
                    ['D', '8', false],
                ]
            ],
        ];

        foreach ($questions as $q) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'order_index' => $q['order_index'],
                'question' => $q['question'],
            ]);

            foreach ($q['choices'] as $c) {
                QuizQuestionChoice::create([
                    'question_id' => $question->id,
                    'label' => $c[0],
                    'text' => $c[1],
                    'is_correct' => $c[2],
                ]);
            }
        }

        // ========================
        // 3. Participants Example
        // ========================
        QuizParticipant::create([
            'quiz_id' => $quiz->id,
            'participants_id' => 1,  // pastikan student id = 1 ada
            'score' => 100,
        ]);

        QuizParticipant::create([
            'quiz_id' => $quiz->id,
            'participants_id' => 2,  // pastikan student id = 2 ada
            'score' => 80,
        ]);
    }
}
