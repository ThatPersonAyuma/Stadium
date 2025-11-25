@extends('layouts.dashboard')
@section('title', 'Teacher Courses')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-10 md:pt-8 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <x-dashboard-header title="Teacher Panel" subtitle="Kelola course dan pantau progres siswa" />
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-white/10 px-4 py-3 shadow-lg border border-white/15">
                    <p class="m-0 text-sm opacity-80">Completed today</p>
                    <p class="m-0 text-2xl font-black">{{ $teacher->completedToday ?? 0 }}</p>
                </div>
                <a href="#"
                   class="inline-flex items-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-900 text-white text-sm font-bold">+</span>
                    Tambah Course
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-sm opacity-80">Total Course</p>
                <p class="m-0 text-3xl font-black">{{ $summary['courses'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-sm opacity-80">Total Siswa Terdaftar</p>
                <p class="m-0 text-3xl font-black">{{ $summary['students'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-sm opacity-80">Siswa Menyelesaikan</p>
                <p class="m-0 text-3xl font-black">{{ $summary['completed'] ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                @php
                    $rate = ($course->total_students ?? 0) > 0
                        ? round(($course->completed_count / $course->total_students) * 100)
                        : 0;
                @endphp
                <div class="col-span-2 rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="m-0 text-sm uppercase tracking-widest opacity-70">Course</p>
                            <h2 class="m-0 text-2xl font-black">{{ $course->title }}</h2>
                            <p class="m-0 text-sm opacity-80">
                                {{ $course->total_students }} siswa · {{ $course->completed_count }} selesai
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide"
                              style="background-color: {{ $course->color }}; color: #fff;">
                            <span class="h-2 w-2 rounded-full bg-white/90"></span>
                            {{ $course->status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                    </div>

                    <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-white/20 shadow-inner">
                        <div class="h-full bg-white/80" style="width: {{ $rate }}%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-sm opacity-85">
                        <span>{{ $rate }}% selesai</span>
                        <span>{{ $course->completed_count }} / {{ $course->total_students }} siswa</span>
                    </div>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <a href="#"
                           class="inline-flex items-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                            Lihat Detail Course
                        </a>
                        <a href="#"
                           class="inline-flex items-center gap-2 rounded-lg border border-white/30 bg-white/10 px-3 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                            Kelola Materi
                        </a>
                    </div>

                    <div class="mt-6 rounded-xl border border-white/10 bg-white/5 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="m-0 text-sm font-semibold">Siswa yang baru selesai</p>
                            <span class="text-xs uppercase tracking-widest opacity-70">Terbaru</span>
                        </div>
                        <div class="divide-y divide-white/10">
                            @forelse ($course->recent_completes as $student)
                                <div class="py-2 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-white/15 flex items-center justify-center text-sm font-bold text-white">
                                            {{ strtoupper(substr($student['name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="m-0 font-semibold">{{ $student['name'] }}</p>
                                            <p class="m-0 text-xs opacity-70">{{ $student['time'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold bg-white/10 px-3 py-1 rounded-lg border border-white/20">
                                        Skor {{ $student['score'] }}
                                    </span>
                                </div>
                            @empty
                                <p class="m-0 text-sm opacity-70">Belum ada yang menyelesaikan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/15 bg-white/5 p-5 shadow-xl">
                    <p class="m-0 text-sm opacity-80">Aksi Cepat</p>
                    <div class="mt-3 space-y-3">
                        <button type="button" class="w-full rounded-lg bg-white text-slate-900 px-4 py-3 text-sm font-semibold shadow-md hover:-translate-y-0.5 transition">
                            Tambah Modul
                        </button>
                        <button type="button" class="w-full rounded-lg border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white hover:-translate-y-0.5 transition">
                            Kirim Pengumuman
                        </button>
                        <button type="button" class="w-full rounded-lg border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white hover:-translate-y-0.5 transition">
                            Lihat Forum Course
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
