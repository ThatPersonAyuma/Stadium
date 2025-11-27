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
    public function HandleAnswer(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'quiz_id'    => 'required|integer',
            'answer_id'     => 'required|integer',
        ]);

        $quizId = $request->quiz_id;
        $studentId = $request->student_id;
        $answer = $request->answer;

        // Ambil quiz
        $quiz = Quiz::findOrFail($quizId);

        $index = $quiz->running_index;

        // Hitung duration sejak question update
        $questionStart = $quiz->updated_at;          // soal berubah → updated_at berubah
        $now = now();

        $duration = $now->floatDiffInSeconds($questionStart); 
        $duration = round($duration, 2); // dua angka belakang koma

        // Ambil soal aktif
        $question = QuizQuestion::where('quiz_id', $quizId)
            ->where('order_index', $index)
            ->firstOrFail();

        $isCorrect = $answer == $question->correct_answer; // CHeck from here

        // Hitung score berdasarkan waktu
        $baseScore = 1000;
        $score = $isCorrect ? max(0, $baseScore - ($duration * 100)) : 0;
        $score = round($score, 2);

        // Simpan jawaban
        QuizAnswer::updateOrCreate(
            [
                'quiz_id'        => $quizId,
                'student_id'     => $studentId,
                'question_index' => $index,
            ],
            [
                'answer'        => $answer,
                'is_correct'    => $isCorrect,
                'duration'      => $duration,
                'score_awarded' => $score,
            ]
        );

        // Update total nilai
        $totalScore = QuizParticipant::where('quiz_id', $quizId)
            ->where('student_id', $studentId)
            ->get()->score + $score;

        QuizParticipant::updateOrCreate(
            [
                'quiz_id'    => $quizId,
                'student_id' => $studentId,
                'score' => $totalScore,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'duration' => $duration,
                'score_awarded' => $score,
                'total_score' => $totalScore,
            ]
        ]);
    }
    public function EndQuiz(Request $request)
    {

    }
}
