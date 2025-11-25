@extends('layouts.dashboard')
@section('title', 'Teacher Courses')

@section('content')
@php
    $courses = $courses ?? collect();
    $summary = $summary ?? ['courses' => 0, 'students' => 0, 'completed' => 0];
@endphp
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-8">
        <x-dashboard-header title="Teacher Panel" subtitle="Kelola course secara ringkas" />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="rounded-2xl bg-white/10 px-4 py-3 shadow-lg border border-white/15">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Selesai Hari Ini</p>
                <p class="m-0 text-2xl font-black">{{ $teacher->completedToday ?? 0 }}</p>
            </div>
            <a href="#"
               class="inline-flex w-full md:w-auto items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                <i class="fa-solid fa-circle-plus text-base"></i>
                Tambah Course
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Course Aktif</p>
                <p class="m-0 text-3xl font-black">{{ $summary['courses'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Total Siswa</p>
                <p class="m-0 text-3xl font-black">{{ $summary['students'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Siswa Selesai</p>
                <p class="m-0 text-3xl font-black">{{ $summary['completed'] ?? 0 }}</p>
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($courses as $course)
                @php
                    $recentCompletes = $course->recent_completes ?? collect();
                    $rate = ($course->total_students ?? 0) > 0
                        ? round(($course->completed_count / $course->total_students) * 100)
                        : 0;
                    $status = strtolower($course->status ?? 'draft');
                    $statusMeta = [
                        'new'       => ['label' => 'New', 'bg' => '#38bdf8'],
                        'pending'   => ['label' => 'Pending', 'bg' => '#f59e0b'],
                        'approved'  => ['label' => 'Approved', 'bg' => '#22c55e'],
                        'published' => ['label' => 'Published', 'bg' => '#22c55e'],
                        'revision'  => ['label' => 'Revision', 'bg' => '#a855f7'],
                        'rejected'  => ['label' => 'Rejected', 'bg' => '#ef4444'],
                        'hidden'    => ['label' => 'Hidden', 'bg' => '#64748b'],
                        'archived'  => ['label' => 'Archived', 'bg' => '#94a3b8'],
                        'draft'     => ['label' => 'Draft', 'bg' => '#475569'],
                    ];
                    $statusData = $statusMeta[$status] ?? $statusMeta['draft'];
                @endphp
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-white text-slate-900 px-4 py-2.5 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-folder-plus text-base"></i>
                            Edit Course
                        </button>
                        <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-comments text-base"></i>
                            Forum Course
                        </button>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div class="space-y-1">
                                <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Course</p>
                                <h2 class="m-0 text-2xl font-black">{{ $course->title }}</h2>
                                <p class="m-0 text-sm opacity-80">
                                    {{ $course->total_students }} siswa &middot; {{ $course->completed_count }} selesai
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide"
                                  style="background-color: {{ $statusData['bg'] }}; color: #fff;">
                                <span class="h-2 w-2 rounded-full bg-white/90"></span>
                                {{ $statusData['label'] }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-1">
                            <div class="h-2 w-full overflow-hidden rounded-full bg-white/20 shadow-inner">
                                <div class="h-full bg-white/80" style="width: {{ $rate }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-sm opacity-85">
                                <span>{{ $rate }}% selesai</span>
                                <span>{{ $course->completed_count }} / {{ $course->total_students }} siswa</span>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <a href="#"
                               class="inline-flex items-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                                Lihat Detail
                            </a>
                            <a href="#"
                               class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-3 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                                Kelola Materi
                            </a>
                        </div>

                        <div class="mt-6 rounded-xl border border-white/10 bg-white/5 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="m-0 text-sm font-semibold">Aktivitas Terbaru</p>
                                <span class="text-xs uppercase tracking-widest opacity-70">Update</span>
                            </div>
                            <div class="divide-y divide-white/10">
                                @forelse ($recentCompletes as $student)
                                    <div class="flex items-center justify-between py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-sm font-bold text-white">
                                                {{ strtoupper(substr($student['name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="m-0 font-semibold">{{ $student['name'] }}</p>
                                                <p class="m-0 text-xs opacity-70">{{ $student['time'] }}</p>
                                            </div>
                                        </div>
                                        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold">
                                            Progress {{ $student['score'] }}%
                                        </span>
                                    </div>
                                @empty
                                    <p class="m-0 text-sm opacity-70">Belum ada progres terbaru.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-white/15 bg-white/5 p-6 text-sm opacity-80">
                    Belum ada course yang Anda kelola.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
