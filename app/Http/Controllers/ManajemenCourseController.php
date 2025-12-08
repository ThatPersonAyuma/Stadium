<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Content;
use Illuminate\Http\Request;

class ManajemenCourseController extends Controller
{
    /**
     * Menampilkan daftar semua course.
     * Logika sorting: Status 'Pending' selalu paling atas.
     */
    public function index()
    {
        // 1. Ambil data dengan Eager Loading (biar ringan)
        // Kita gunakan orderByRaw agar status 'pending' punya prioritas (muncul duluan)
        // CASE WHEN status = 'pending' THEN 0 ELSE 1 END -> Artinya pending dianggap angka 0 (kecil), sisanya 1 (besar)
        $courses = Course::with(['teacher.user', 'lessons'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END") 
            ->latest() // Setelah dipisah pending/bukan, urutkan berdasarkan tanggal terbaru
            ->get();

        // 2. Hitung Summary untuk Statistik di halaman Index
        $summary = [
            'total'    => $courses->count(),
            'pending'  => $courses->where('status', 'pending')->count(),
            'approved' => $courses->where('status', 'approved')->count(),
            'rejected' => $courses->where('status', 'rejected')->count(),
        ];

        // Pastikan nama view sesuai dengan folder resources/views/admin/manajemen-course/index.blade.php
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
        // Update status jadi approved
        $course->update([
            'status' => 'approved',
            'rejection_note' => null // Hapus catatan penolakan jika ada (biar bersih)
        ]);

        return redirect()
            ->back() // Kembali ke halaman sebelumnya (bisa index atau show)
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
}