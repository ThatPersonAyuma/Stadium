@extends('layouts.dashboard')
@section('title', 'Review Quiz')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
{{-- WRAPPER KONTEN: Full Width (w-full) dengan padding minimal --}}
    <div class="relative z-10 mx-auto w-full space-y-8 mb-12">

        {{-- 1. NAVIGASI SIMPLE (Pojok Kiri) --}}
        <div class="mb-8">
            <a href="{{ route('admin.manajemen-quiz.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-800/50 hover:bg-indigo-600 border border-slate-700 hover:border-indigo-500 transition-all text-sm font-medium text-slate-300 hover:text-white group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span>Kembali ke Menu</span>
            </a>
        </div>

        {{-- 2. HEADER QUIZ (Full Width Banner) --}}
        <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-12 border border-white/10">
            {{-- Gradient Background Full --}}
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-900 via-violet-900 to-[#020617] opacity-90"></div>
            
            <div class="relative p-10 md:p-14 text-center">
                {{-- Judul Besar --}}
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight drop-shadow-lg">
                    {{ $quiz->title }}
                </h1>
                
                {{-- Deskripsi --}}
                <p class="text-indigo-200 text-lg md:text-xl max-w-5xl mx-auto leading-relaxed font-light mb-10">
                    {{ $quiz->description }}
                </p>

                {{-- Stats Bar (Horizontal Full) --}}
                <div class="inline-flex flex-wrap justify-center gap-4 md:gap-12 p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                    <div class="text-center px-4">
                        <div class="text-[10px] md:text-xs text-indigo-300 uppercase tracking-widest font-bold mb-1">Total Soal</div>
                        <div class="text-2xl md:text-3xl font-bold text-white">{{ $quiz->questions->count() }}</div>
                    </div>
                    <div class="w-px bg-white/10 h-12 hidden md:block"></div>
                    <div class="text-center px-4">
                        <div class="text-[10px] md:text-xs text-indigo-300 uppercase tracking-widest font-bold mb-1">Dibuat Oleh</div>
                        <div class="text-2xl md:text-3xl font-bold text-white">{{ $quiz->creator->user->name ?? 'Admin' }}</div>
                    </div>
                    <div class="w-px bg-white/10 h-12 hidden md:block"></div>
                    <div class="text-center px-4">
                        <div class="text-[10px] md:text-xs text-indigo-300 uppercase tracking-widest font-bold mb-1">Tanggal</div>
                        <div class="text-2xl md:text-3xl font-bold text-white">{{ $quiz->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. DAFTAR PERTANYAAN (Full Width Stretched) --}}
        <div class="space-y-8">
            @forelse($quiz->questions as $index => $question)
                
                {{-- ITEM CARD: Lebar Penuh --}}
                <div class="bg-[#0f172a] rounded-2xl border border-slate-800 hover:border-indigo-500/30 transition-all duration-300 overflow-hidden shadow-lg group">
                    
                    {{-- Header Soal (Pertanyaan) --}}
                    <div class="bg-[#1e293b]/50 p-6 md:p-8 flex gap-6 items-start border-b border-slate-800/50">
                        {{-- Nomor (Gradient Box) --}}
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-indigo-500/20">
                                {{ $index + 1 }}
                            </div>
                        </div>

                        {{-- Teks Pertanyaan --}}
                        <div class="grow">
                            <h3 class="text-xl md:text-3xl font-semibold text-white leading-normal mt-1">
                                {{ $question->question }}
                            </h3>
                        </div>
                    </div>

                    {{-- Body Soal (Pilihan Jawaban) --}}
                    <div class="p-6 md:p-8 bg-[#0f172a]">
                        {{-- Grid Jawaban: Menggunakan Grid 1 Kolom Full Width agar memanjang --}}
                        <div class="flex flex-col gap-3">
                            @foreach($question->choices as $choice)
                                @php
                                    $isCorrect = $choice->is_correct;
                                    
                                    if($isCorrect) {
                                        // STYLE BENAR: Glowing Emerald Green
                                        $style = "bg-emerald-500/10 border-emerald-500/50 shadow-[0_0_20px_rgba(16,185,129,0.1)]";
                                        $textStyle = "text-emerald-100 font-medium text-lg";
                                        $badgeStyle = "bg-emerald-500 text-white border-emerald-400";
                                    } else {
                                        // STYLE SALAH/BIASA: Dark Slate
                                        $style = "bg-[#1e293b]/50 border-slate-700 hover:border-slate-500 hover:bg-[#1e293b]";
                                        $textStyle = "text-slate-300 text-lg";
                                        $badgeStyle = "bg-slate-700 text-slate-400 border-slate-600";
                                    }
                                @endphp

                                <div class="flex items-center p-4 md:p-5 rounded-xl border-2 transition-all duration-200 w-full {{ $style }}">
                                    {{-- Huruf A/B/C/D --}}
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold border mr-5 shrink-0 {{ $badgeStyle }}">
                                        {{ $choice->label }}
                                    </div>

                                    {{-- Teks Jawaban --}}
                                    <div class="grow {{ $textStyle }}">
                                        {{ $choice->text }}
                                    </div>

                                    {{-- Icon Check (Hanya muncul jika benar) --}}
                                    @if($isCorrect)
                                        <div class="shrink-0 ml-4 bg-emerald-500 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @empty
                <div class="py-32 text-center rounded-3xl border-2 border-dashed border-slate-800 bg-slate-900/50">
                    <i class="fas fa-clipboard-list text-6xl text-slate-700 mb-6"></i>
                    <h3 class="text-2xl text-slate-400 font-bold">Belum ada soal tersedia</h3>
                    <p class="text-slate-500 mt-2">Silakan tambahkan soal melalui menu editor.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer Simple --}}
        <div class="mt-20 border-t border-slate-800 pt-8 pb-12 text-center">
            <p class="text-slate-600 font-mono text-sm uppercase tracking-[0.2em]">End of Quiz Detail</p>
        </div>

    </div>
</div>
@endsection