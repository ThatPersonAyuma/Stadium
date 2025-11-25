@extends('layouts.dashboard')
@section('title', 'Teacher Courses')

@section('content')
@php
    $courses = $courses ?? collect();
@endphp
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <x-dashboard-header title="Course Teacher" subtitle="Kelola semua course Anda" />
            <a href="{{ route('teacher.courses.create') }}"
               class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                <i class="fa-solid fa-circle-plus text-base"></i>
                Tambah Course
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Total Course</p>
                <p class="m-0 text-3xl font-black">{{ $summary['total'] ?? $courses->count() }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Draft</p>
                <p class="m-0 text-3xl font-black">{{ $summary['draft'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Approved</p>
                <p class="m-0 text-3xl font-black">{{ $summary['approved'] ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($courses as $course)
                @php
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
                <div class="flex flex-col gap-3 rounded-2xl border border-white/15 bg-white/10 p-5 shadow-2xl">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Course</p>
                            <h3 class="m-0 text-xl font-black leading-tight">{{ $course->title }}</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide"
                              style="background-color: {{ $statusData['bg'] }}; color: #fff;">
                            <span class="h-2 w-2 rounded-full bg-white/90"></span>
                            {{ $statusData['label'] }}
                        </span>
                    </div>
                    <p class="m-0 text-sm opacity-80 line-clamp-3">{{ $course->description }}</p>
                    <div class="flex items-center gap-4 text-sm opacity-85">
                        <span class="inline-flex items-center gap-2">
                            <i class="fa-solid fa-book text-white/80"></i>
                            {{ $course->lessons_count ?? 0 }} Lessons
                        </span>
                        @if($teacher ?? false)
                            <span class="inline-flex items-center gap-2">
                                <i class="fa-solid fa-user text-white/80"></i>
                                {{ $teacher->name ?? $teacher->username }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-auto flex flex-wrap gap-2">
                        <a href="{{ route('teacher.courses.show', $course) }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                            Detail Course
                        </a>
                        <a href="{{ route('teacher.courses.edit', $course) }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 bg-white/10 px-3 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                            Edit Course
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-white/15 bg-white/5 p-6 text-sm opacity-80">
                    Belum ada course. Mulai dengan menambahkan course pertama Anda.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
