@extends('layouts.dashboard')
@section('title', 'Daftar Quiz')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    {{-- Background --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-6">

        {{-- Header --}}
        <x-dashboard-header 
            title="Daftar Quiz" 
            subtitle="Kelola semua quiz yang telah Anda buat" 
        />

        <a href="{{ route('quiz.create') }}"
           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
            <i class="fa-solid fa-circle-plus text-base"></i>
            Tambah Quiz
        </a>

        <div class="grid grid-cols-1 gap-6">
            @forelse ($quizzes as $quiz)
                
                <a href="{{ route('quiz.manage', $quiz->id) }}"
                class="block rounded-2xl border border-white/15 bg-white/10 p-5 shadow-2xl transition hover:bg-white/20">
                
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Quiz</p>
                            <h3 class="m-0 text-xl font-black leading-tight">{{ $quiz->title }}</h3>
                        </div>

                        <span class="inline-flex items-center gap-2 text-xs font-semibold opacity-80">
                            <i class="fa-solid fa-user text-white/80"></i>
                            {{ $quiz->creator->user->name ?? 'Unknown' }}
                        </span>
                    </div>

                    <p class="m-0 mt-2 text-sm opacity-80 line-clamp-2">
                        {{ $quiz->description }}
                    </p>
                </a>

                <div class="flex w-full gap-3">

                    @if ($quiz->is_finished)
                        <a href="{{ route('quiz.monitoring', $quiz->id) }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-green-500/20 border border-green-300/40 px-4 py-2 text-sm font-semibold text-green-200 shadow-md transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-chart-line"></i>
                            Lihat Hasil
                        </a>
                    @else
                        <a href="{{ route('quiz.monitoring', $quiz->id) }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500/20 border border-blue-300/40 px-4 py-2 text-sm font-semibold text-blue-200 shadow-md transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-play"></i>
                            Mulai Quiz
                        </a>
                    @endif

                    <form action="{{ route('quiz.delete', $quiz->id) }}"
                        method="POST"
                        class="flex-1"
                        data-question-delete-form>
                        @csrf
                        @method('DELETE')

                        <button type="button" data-delete-question
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-rose-300/40 bg-rose-500/20 px-4 py-2 text-sm font-semibold text-rose-100 shadow-md transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-trash"></i>
                            Hapus
                        </button>
                    </form>

                </div>

            @empty
                <div class="col-span-full rounded-2xl border border-white/15 bg-white/5 p-6 text-sm opacity-80">
                    Belum ada quiz. Tambahkan quiz pertama Anda.
                </div>
            @endforelse
        </div>


    </div>
</div>
@endsection
