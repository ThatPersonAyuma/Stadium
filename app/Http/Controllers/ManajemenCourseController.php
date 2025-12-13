<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Content;
use App\Enums\CourseStatus;
use Illuminate\Http\Request;

class ManajemenCourseController extends Controller
{
    /**
     * Menampilkan daftar semua course.
     * Logika sorting: Status 'Pending' selalu paling atas.
     */
    public function index()
    {
        $courses = Course::with(['teacher.user', 'lessons'])
            ->where('status', CourseStatus::PENDING->value)
            ->latest()
            ->get();

        $summary = [
            'total'    => Course::count(),
            'pending'  => Course::where('status', CourseStatus::PENDING->value)->count(),
            'approved' => Course::where('status', CourseStatus::APPROVED->value)->count(),
            'rejected' => Course::where('status', CourseStatus::REJECTED->value)->count(),
        ];

        return view('admin.manajemen-course.index', compact('courses', 'summary'));
    }

    /**
     * Menampilkan detail course secara Read-Only.
     * Admin bisa melihat isi materi untuk bahan pertimbangan approval.
     */
    public function show(Course $course)
    {
        // 1. Load data sedalam mungkin (Deep Eager Loading) untuk statistik
        // lessons -> contents -> cards -> blocks
        $course->load([
            'teacher.user',
            'lessons.contents.cards.blocks'
        ]);

        // 2. Hitung statistik konten untuk ditampilkan di kotak atas halaman detail
        $stats = [
            'lessons'  => $course->lessons->count(),
            'contents' => $course->lessons->flatMap->contents->count(),
            'blocks'   => $course->lessons->flatMap->contents->flatMap->cards->flatMap->blocks->count(),
        ];

        return view('admin.manajemen-course.show', compact('course', 'stats'));
    }

    /**
     * Action: Menyetujui Course (Live)
     */
    public function approve(Course $course)
    {
        $course->update([
            'status' => 'approved',
            'rejection_note' => null
        ]);

        return redirect()
            ->back() 
            ->with('success', "Course '{$course->title}' berhasil disetujui (Approved).");
    }

    /**
     * Action: Menolak Course (Kembalikan ke Teacher)
     */
    public function reject(Request $request, Course $course)
    {
        // Validasi: Admin WAJIB mengisi alasan penolakan
        $request->validate([
            'rejection_note' => 'required|string|min:5',
        ], [
            'rejection_note.required' => 'Alasan penolakan wajib diisi agar guru paham kekurangannya.',
        ]);

        // Update status jadi rejected dan simpan alasannya
        $course->update([
            'status' => 'rejected',
            'rejection_note' => $request->rejection_note
        ]);

        return redirect()
            ->back()
            ->with('success', "Course '{$course->title}' ditolak.");
    }

    public function previewContent(Content $content)
    {
        // 1. Load Parent (untuk navigasi breadcrumb: Course > Lesson)
        // 2. Load Children (Cards & Blocks) supaya isinya muncul!
        $content->load([
            'lesson.course',      // Ke atas (Parent)
            'cards.blocks'        // Ke bawah (Isi Materi)
        ]);

        return view('admin.manajemen-course.preview', compact('content'));
    }
    public function action(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'status'     => 'required|in:revision,rejected,approved'
        ]);

        $course = Course::find($request['course_id']);
        if ($request->status === CourseStatus::APPROVED->value) {
            $course->status = CourseStatus::APPROVED;
        } else if($request->status === CourseStatus::REJECTED->value)
        {
            $course->status = CourseStatus::REJECTED;
        }else if($request->status === CourseStatus::REVISION->value){
            $course->status = CourseStatus::REVISION;
        }
        $course->save();

        return back()->with('success', 'Status course berhasil diperbarui!');
    }
}
