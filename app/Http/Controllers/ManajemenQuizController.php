<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Enums\CourseStatus;
use Illuminate\Http\Request;

class ManajemenQuizController extends Controller
{
    /**
     * Menampilkan Daftar Pengajuan Kuis
     */
    public function index()
    {
        // 1. Ambil semua quiz, urutkan dari yang terbaru
        // Gunakan 'with' untuk mengambil relasi creator (teacher) dan user-nya agar query ringan
        $quizzes = Quiz::with(['creator.user', 'questions'])
            ->where('status', '!=', CourseStatus::DRAFT)
            ->latest()
            ->get();

        // 2. Hitung Statistik untuk 3 Card di atas
        // Kita hitung dari collection yang sudah diambil agar hemat query database
        $stats = [
            'pending'  => $quizzes->where('status', 'pending')->count(),
            'approved' => $quizzes->where('status', 'approved')->count(),
            'rejected' => $quizzes->where('status', 'rejected')->count(),
        ];

        return view('admin.manajemen-quiz.index', compact('quizzes', 'stats'));
    }

    /**
     * Menampilkan Detail Soal Kuis (Lembar Review)
     */
    public function show($id)
    {
        // Ambil quiz beserta soal dan pilihan jawabannya
        // 'questions.choices' penting agar pilihan ganda muncul di view
        $quiz = Quiz::with(['creator.user', 'questions.choices'])
            ->findOrFail($id);

        return view('admin.manajemen-quiz.show', compact('quiz'));
    }

    /**
     * Action: Terima Kuis
     */
    // public function approve($id)
    // {
    //     $quiz = Quiz::findOrFail($id);
        
    //     $quiz->update([
    //         'status' => 'approved'
    //     ]);

    //     return redirect()->back()->with('success', 'Kuis berhasil disetujui dan sekarang Aktif.');
    // }

    // /**
    //  * Action: Tolak Kuis
    //  */
    // public function reject($id)
    // {
    //     $quiz = Quiz::findOrFail($id);
        
    //     $quiz->update([
    //         'status' => 'rejected'
    //     ]);

    //     return redirect()->back()->with('error', 'Kuis telah ditolak.');
    // }
    public function action(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|integer',
            'status'     => 'required|in:revision,rejected,approved'
        ]);

        $quiz = Quiz::find($request['quiz_id']);
        if ($request->status === CourseStatus::APPROVED->value) {
            $quiz->status = CourseStatus::APPROVED;
        } else if($request->status === CourseStatus::REJECTED->value)
        {
            $quiz->status = CourseStatus::REJECTED;
        }else if($request->status === CourseStatus::REVISION->value){
            $quiz->status = CourseStatus::REVISION;
        }
        $quiz->save();

        return back()->with('success', 'Status quiz berhasil diperbarui!');
    }
}