<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\QuestionSent;
use App\Events\ParticipantRegistered;
use App\Events\SendAnswerAndScore;
use App\Events\ScoreBoard;
use App\Events\AnswerSubmitted;
use App\Events\QuizEnd;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Models\Quiz;
use App\Models\QuizParticipant;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionChoice;
use App\Helpers\Utils;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $quizzes = Quiz::all()->where('creator_id', $teacher->id);
        return view('quiz.index', compact('quizzes'));
    }

 public function create()
    {
        return view('quiz.create_quiz');
    }

    /**
     * Update quiz
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|string',
        ]);

        $quiz->update($validated);

        return redirect()->route('quiz.manage', $quiz->id)
                         ->with('success', 'Quiz berhasil diperbarui.');
    }

    /**
     * Hapus quiz
     */
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('quiz.index')
                         ->with('success', 'Quiz berhasil dihapus.');
    }

    public function sendQuestion(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|integer',
            'quiz_order' => 'required|integer'
        ]);
        $quiz = Quiz::findOrFail($validated['quiz_id']);
        $quiz->running_index = $validated['quiz_order'];
        $quiz->save();
        // 1. Ambil question berdasarkan quiz_id + order_index
        $question = QuizQuestion::where('quiz_id', $validated['quiz_id'])
            ->where('order_index', $validated['quiz_order'])
            ->firstOrFail();

        // 2. Ambil pilihan jawaban
        $choices = QuizQuestionChoice::where('question_id', $question->id)
            ->orderBy('label') // A, B, C, D
            ->get(['id', 'label', 'text']); // jangan kirim is_correct!

        // 3. Format options ke array sederhana
        $options = $choices->map(function ($choice) {
            return [
                'id' => $choice->id,
                'label' => $choice->label,
                'text' => $choice->text
            ];
        })->toArray();

        // 4. Broadcast event
        broadcast(new QuestionSent(
            $validated['quiz_id'],
            $question->question,
            $options
        ))->toOthers();
        $quiz->updated_at = now();
        $quiz->save();
        // 5. Return ke pengirim
        return response()->json([
            'status' => 'ok',
            'question' => [
                'question' => $question->question,
                'options' => $options
            ],
        ]);
    }
    public function startQuestion(Request $request)
    {
        $validated = $request->validate([
            'interval' => 'required|integer',
            'quiz_id' => 'required|integer'
        ]);
        $quiz = Quiz::findOrFail($validated['quiz_id']);
        $quiz->interval = $validated['interval'];
        $quiz->save();
        return [
            'scoreboard' => QuizParticipant::where('quiz_id', $quiz->id)
                ->with('student.user')
                ->orderByDesc('score')
                ->get()
                ->map(function ($p) {
                    return [
                        'username' => $p->student->user->username, 
                        'score' => $p->score,
                    ];
                }),
        ];
    }   
    public function BroadcastScoreboard(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|integer'
        ]);
        broadcast(new ScoreBoard($validated['quiz_id']))->toOthers();
        Log::info('Success on BroadCAst');
        return response()->json([
            'status' => 'success',
            'message' => 'Broadcasted'
        ]);
    }
    public function EndQuestion(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|integer'
        ]);

        $quiz = Quiz::findOrFail($validated['quiz_id']);

        // Ambil pertanyaan berdasarkan running_index
        $question = $quiz->questions()
            ->where('order_index', $quiz->running_index)
            ->firstOrFail();

        // Ambil pilihan yang benar
        $correctChoice = $question->choices()
            ->where('is_correct', true)
            ->first();

        // Jika tidak ada jawaban benar (harusnya tidak mungkin)
        if (!$correctChoice) {
            return response()->json([
                'message' => 'No correct answer found for this question.'
            ], 500);
        }

        // Skor akhir ketika soal selesai — biasanya 0 karena ini hanya reveal
        $score = 0;

        broadcast(
            new SendAnswerAndScore(
                $quiz->id,
                [
                    'question' => $question->question,
                    'choice_id' => $correctChoice->id,
                    'label'     => $correctChoice->label,
                    'text'      => $correctChoice->text,
                ]
            )
        )->toOthers();
        Log::info('Success on EndQuestion');
        return response()->json([
            'status' => 'ok',
            'correct_answer' => $correctChoice->only(['id', 'label', 'text'])
        ]);
    }

    public function HandleAnswer(Request $request)
    {
        Log::info($request->all());
        $request->validate([
            'student_id' => 'required|integer',
            'quiz_id'    => 'required|integer',
            'answer_id'     => 'required|integer',
        ]);
        
        $quizId = $request['quiz_id'];
        $studentId = $request['student_id'];
        $answer = $request['answer_id'];
        // Log::info($quizId . $studentId . $answer);
        $quiz = Quiz::findOrFail($quizId);


        $index = $quiz->running_index;
        // $question = QuizQuestion::where(['quiz_id'=>$quiz->id, 'order_index'=>$index])->first();

        $questionStart = $quiz->updated_at;   // timestamp saat soal dikirim
        $now = now();

        $interval = (int) $quiz->interval;    // detik
        $duration = abs($questionStart->diffInSeconds($now, false));
        Log::info('Durasi: ' . $duration);
        $duration = round($duration, 2);

        // Log::info('Checkpoin 1 Passed');

        // ambil soal berdasarkan order
        $question = QuizQuestion::where('quiz_id', $quizId)
            ->where('order_index', $index)
            ->firstOrFail();

        // Log::info('Checkpoin 2 Passed');

        $isCorrect = QuizQuestionChoice::findOrFail($answer)->is_correct;

        $maxScore = 1000; // skor penuh

        if ($isCorrect) {
            // Rasio kecepatan (0–1)
            $speedRatio = max(0, ($interval - $duration) / $interval);

            // Skor akhir
            $score = round($maxScore * $speedRatio, 2);
        } else {
            $score = 0;
        }

        $participants = QuizParticipant::where('quiz_id', $quizId)
            ->where('participant_id', $studentId)
            ->firstOrFail();
        $participants->score = $participants->score + $score;
        $participants->save();
        broadcast(new AnswerSubmitted($quiz->id, ['username'=>$participants->student->user->username,'score'=>$participants->score]));
        return response()->json([
            'success' => true,
            'data' => [
                'duration' => $duration,
                'score_awarded' => $score,
                'total_score' => $participants->score,
            ]
        ]);
    }
    public function EndQuiz(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|integer'
        ]);
        $quiz = Quiz::findOrFail($validated['quiz_id']);
        if (!($quiz->is_finished)){
            $quiz->is_finished = true;
            $quiz->save();
        }
        broadcast(new QuizEnd($validated['quiz_id']));
        $participants = QuizParticipant::where('quiz_id', $quiz->id)
            ->with('student')
            ->orderByDesc('score')
            ->get();

        $maxXP = $quiz->max_experience;
        $decay = 0.85; // <-- curve level (boleh diubah 0.80 - 0.95)

        foreach ($participants as $i => $result) {
            $rank = $i + 1;

            // Curve distribution
            $xp = intval($maxXP * pow($decay, $rank - 1));

            // Update XP student
            $student = $result->student;
            Utils::add_exp_student($xp,$student->id);
        }
        return response()->json(['status'=>'Ok'], 200);
    }
    public function OpenQuiz(Quiz $quiz)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        Log::info('Teacher id: '. $teacher->id . "Creator_id: " . $quiz->creator_id);
        if ($quiz->creator_id !== $teacher->id) {
            return abort(403, 'You do not have permission to access this resource.');
        }

        if ($quiz->code !== NULL){
            return response()->json([
                'code'=>$quiz->code,
                'status' => 'success',
                'message' => 'Quiz already open.',
                'participants' => $quiz->participants()
                    ->with('student.user')
                    ->get()
                    ->map(function ($p) {
                        return [
                            'username' => $p->student->user->username, 
                        ];
                    })
                    ->toArray(),
            ], 200);
        }
        $maxAttempts = 30;
        $attempt = 0;

        do {
            $code = strtoupper(Str::random(6));
            $exists = Quiz::where('code', $code)->exists();
            $attempt++;

            if ($attempt > $maxAttempts) {
                return abort(500, 'Internal server error, please try again later.');
            }
        } while ($exists);

        $quiz->code = $code;
        $quiz->save();

        

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz opened successfully.',
            'code' => $quiz->code,
            'participants' => $quiz->participants()
                ->with('student.user')
                ->get()
                ->map(function ($p) {
                    return [
                        'username' => $p->student->user->username, 
                    ];
                })
                ->toArray(),
        ], 200);
    }

    public function studentJoin(Request $request)
    {
        $validated = $request->validate([
            'quiz_code' => 'required|string',
            // 'quiz_id'   => 'required|integer',
        ]);

        $quiz = Quiz::where('code', $validated['quiz_code'])->first();

        if (!$quiz) {
            return response()->json(['message' => 'Kode salah'], 200);
            // return abort(404, 'Quiz dengan kode tersebut tidak ditemukan.');
        }
        // if ($quiz->code !== $validated['quiz_code']) {
        //     return response()->json(['message' => 'Kode salah'], 200);
        // }

        $user = Auth::user();
        $student = $user->student;

        $alreadyJoined = QuizParticipant::where('quiz_id', $quiz->id)
            ->where('participant_id', $student->id)
            ->exists();

        if (!$alreadyJoined) {
            QuizParticipant::create([
                'quiz_id' => $quiz->id,
                'participant_id' => $student->id,
            ]);
            broadcast(new ParticipantRegistered($quiz->id, $quiz->participants()
                ->with('student.user')
                ->get()
                ->map(function ($p) {
                    return [
                        'username' => $p->student->user->username, 
                    ];
                })
                ->toArray()));
        }

        // return 'baru join';
        return view('quiz.quiz_running', ['quiz_id' => $quiz->id, 'is_finished' => $quiz->is_finished])
            ->with('success', 'Berhasil bergabung ke quiz');
    }

    public function ShowIndex()
    {
        $user = Auth::user();
        if ($user->role == UserRole::STUDENT)
        {
            return  view('quiz.register');
        }else if($user->role == UserRole::TEACHER){
            $teacher = $user->teacher;
            $quizzes = Quiz::all()->where('creator_id', $teacher->id);
            return view('quiz.index', compact('quizzes'));
        }else if($user->role == UserRole::ADMIN){

        }
    }
    public function TeacherMonitoring(Quiz $quiz)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        if ($teacher->id !== $quiz->creator_id){
            return abort(403, 'You do not have permission to access this resource.');
        }
        return view('quiz.quiz_monitoring', compact('quiz'));
    }
    public function manage(Quiz $quiz)
    {
        return view('quiz.manage', compact('quiz'));
    }
    public function CreateQuestion(Quiz $quiz)
    {
        $nextOrder = QuizQuestion::where('quiz_id', $quiz->id)->count();
        if ($nextOrder<=0)
            {$nextOrder = 1;
        }else{
            $nextOrder += 1;
        }
        return view('quiz.create_question', compact('quiz', 'nextOrder'));
    }
    public function EditQuestion(Quiz $quiz, QuizQuestion $question)
    {
        return view('quiz.edit_question', compact('quiz', 'question'));
    }
    public function DeleteQuestion(Quiz $quiz, QuizQuestion $question)
    {
        // Ambil order index sebelum dihapus
        $order = $question->order_index;

        // Hapus pertanyaan
        $question->delete();

        // Re-order pertanyaan lain pada quiz yang sama
        $quiz->questions()
            ->where('order_index', '>', $order)
            ->decrement('order_index');

        return response()->json([
            'message' => 'Pertanyaan berhasil dihapus'
        ]);
    }

    private function reposition_order_index(QuizQuestion $question, $new_index)
    {
        $old_index = $question->order_index;
        if ($old_index == $new_index){
            return;
        }else if ($old_index > $new_index){
                QuizQuestion::where('quiz_id', $question->quiz_id)
                            ->whereBetween('order_index', [$new_index, $old_index-1])
                            ->increment('order_index');
        }else{
                QuizQuestion::where('quiz_id', $question->quiz_id)
                            ->whereBetween('order_index', [$old_index+1, $new_index])
                            ->decrement('order_index');
        }
        $question->order_index=$new_index;
        $question->save();
    }

    public function UpdateQuestion(Request $request, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question'       => 'required|string',
            'order_index'    => 'required|integer',
            'choices'        => 'required|array|size:4',
            'choices.*.text' => 'required|string',
            'correct'        => 'required|string|in:A,B,C,D',
        ]);
        $maxIndex = QuizQuestion::where('quiz_id', $question->quiz_id)
                        ->max('order_index');

        $newIndex = $validated['order_index'];

        if ($newIndex > $maxIndex) {
            $newIndex = $maxIndex + 1;
        }

        if ($newIndex <= 0) {
            $newIndex = 1;
        }

        $this->reposition_order_index($question, $newIndex);

        $question->question = $validated['question'];
        $question->save();
        $choices = $question->choices->keyBy('label'); 

        foreach ($validated['choices'] as $label => $data) {

            if (!isset($choices[$label])) {
                continue; 
            }

            $choice = $choices[$label];
            $choice->text       = $data['text'];
            $choice->is_correct = ($label === $validated['correct']);
            $choice->save();
        }

        return back()->with('success', 'Pertanyaan berhasil diperbarui');
    }



    public function StoreQuestion(Quiz $quiz, Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'question'       => 'required|string',
            'order_index'    => 'required|integer|min:1',
            'choices'        => 'required|array',
            'choices.*.text' => 'required|string',
            'correct'        => 'required|string|in:A,B,C,D',
        ]);

        // Simpan Pertanyaan
        $question = QuizQuestion::create([
            'quiz_id'     => $quiz->id,
            'order_index' => $validated['order_index'],
            'question'    => $validated['question'],
        ]);

        // Simpan Semua Pilihan Jawaban
        foreach ($validated['choices'] as $label => $choiceData) {
            QuizQuestionChoice::create([
                'question_id' => $question->id,
                'label'       => $label,
                'text'        => $choiceData['text'],
                'is_correct'  => $validated['correct'] === $label,
            ]);
        }

        // Redirect balik ke halaman manajemen quiz
        return redirect()
            ->route('quiz.manage', $quiz->id)
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }
    public function GetScoreboard(Request $request)
    {
        $validated = $request->validate([
            'quiz_id'    => 'required|integer|min:1',
        ]);
        return [
            'scoreboard' => QuizParticipant::where('quiz_id', $validated['quiz_id'])
                ->with('student.user')
                ->orderByDesc('score')
                ->get()
                ->map(function ($p) {
                    return [
                        'username' => $p->student->user->username, 
                        'score' => $p->score,
                    ];
                }),
        ];
    }
}
