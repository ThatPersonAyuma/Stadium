@extends('layouts.dashboard')
@section('title', 'Preview Lesson')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    <div class="w-full px-1 mb-8 pt-4">
        <div class=" mb-6">
            <a href="{{ route('admin.manajemen-course.show', $content->lesson->course->id) }}" 
            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 hover:bg-[#EDB240] hover:text-[#000B21] text-white/70 transition-all border border-white/5 font-bold text-xs uppercase tracking-widest group backdrop-blur-md">
                <i class="fas fa-chevron-left text-[10px] group-hover:-translate-x-1 transition-transform"></i>
                Back to couser
            </a>
        </div>

        {{-- Judul Konten (Full Width Card) --}}
        <div class="bg-[#001E5C] border border-white/10 rounded-3xl p-8 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-[#EDB240]"></div> {{-- Aksen Kiri --}}
            
            <div class="relative z-10">
                <span class="block text-xs font-bold text-[#EDB240] uppercase tracking-widest mb-2">
                    {{ $content->lesson->title }}
                </span>
                <h1 class="text-3xl md:text-5xl font-bold font-heading text-white leading-tight">
                    {{ $content->title }}
                </h1>
            </div>
            
            {{-- Background Decoration --}}
            <i class="fas fa-file-alt absolute -right-6 -bottom-6 text-9xl text-white/[0.03] pointer-events-none rotate-12"></i>
        </div>
    </div>

    {{-- 2. MAIN CONTENT AREA (Full Width) --}}
    <div class="w-full space-y-10 px-1">
        
        @forelse($content->cards as $index => $card)
            
            {{-- Wrapper per Bagian --}}
            <div class="relative">
                
                {{-- Label Bagian --}}
{{-- === PEMBATAS BERCAHAYA (THE ONLY GLOWING PART) === --}}
            <div class="flex items-center gap-6 py-4 relative">
                {{-- Garis halus memudar --}}
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-[#EDB240]/40 to-transparent opacity-70"></div>
                
                {{-- Badge "Emas" yang menyala elegan --}}
                <span class="relative z-10 px-5 py-1.5 rounded-full bg-gradient-to-r from-[#EDB240] to-[#D49E35] text-[#000B21] text-xs font-extrabold uppercase tracking-[0.25em] shadow-[0_0_20px_rgba(237,178,64,0.6)]">
                    BAGIAN {{ $index + 1 }}
                </span>
                
                <div class="h-px flex-1 bg-gradient-to-l from-transparent via-[#EDB240]/40 to-transparent opacity-70"></div>
            </div>

                {{-- CARD CONTAINER (Full Width) --}}
                <div class="bg-[#001E5C] border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="p-8 md:p-12 space-y-10">
                        @foreach($card->blocks as $block)
                            
                            @php
                                $type = $block->type->value ?? $block->type;
                                $data = $block->data;
                                $isQuiz = is_array($data) && isset($data['question']) && isset($data['choices']);
                                $src = is_array($data) ? ($data['filename'] ?? ($data['url'] ?? '')) : $data;
                                $text = is_array($data) ? ($data['text'] ?? ($data['content'] ?? '')) : $data;
                            @endphp

                            {{-- 1. TAMPILAN QUIZ / SOAL --}}
                            @if($isQuiz)
                                <div class="bg-[#000B21]/60 border border-white/10 rounded-2xl p-8 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 bg-[#002F87] px-4 py-2 rounded-bl-2xl text-xs font-bold text-white/80 uppercase tracking-wider">
                                        Latihan Soal
                                    </div>

                                    <div class="flex gap-5 mb-8 pr-10">
                                        <div class="shrink-0 w-10 h-10 rounded-xl bg-[#EDB240] text-[#000B21] flex items-center justify-center font-heading font-bold text-2xl shadow-lg">?</div>
                                        <h4 class="text-xl md:text-2xl font-bold text-white leading-normal pt-1">{{ $data['question'] }}</h4>
                                    </div>

                                    {{-- Grid Pilihan Jawaban (Full Width Grid) --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-0 md:ml-14">
                                        @foreach($data['choices'] as $key => $choice)
                                            <div class="flex items-center gap-4 p-4 rounded-xl border border-white/5 bg-white/5">
                                                <span class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-base bg-[#000B21] border border-white/10 text-white/70">
                                                    {{ $key }}
                                                </span>
                                                <span class="text-white/90 text-base font-medium ">{{ $choice }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-8 ml-0 md:ml-14 p-5 bg-gradient-to-r from-green-500/20 to-transparent border-l-4 border-green-500 rounded-r-xl">
                                        <span class="text-sm font-bold text-green-400 uppercase tracking-wider flex items-center gap-2">
                                            <i class="fas fa-check-circle text-lg"></i> Kunci Jawaban: {{ $data['answer'] }}
                                        </span>
                                        @if(isset($data['explanation']))
                                            <p class="text-base text-white/70 italic mt-2 ml-7 leading-relaxed">"{{ $data['explanation'] }}"</p>
                                        @endif
                                    </div>
                                </div>

                            {{-- 2. TAMPILAN GAMBAR --}}
                            @elseif($type === 'image' || $type === 'gif')
                                <div class="my-10">
                                    <figure class="bg-black/30 rounded-2xl overflow-hidden border border-white/10 shadow-xl">
                                        <img src="{{ asset('storage/' . $src) }}" alt="Materi" class="w-full h-auto object-cover">
                                        @if(isset($data['alt']))
                                            <figcaption class="p-3 text-center text-sm text-white/50 italic bg-black/50 backdrop-blur-sm">{{ $data['alt'] }}</figcaption>
                                        @endif
                                    </figure>
                                </div>


                            {{-- 4. TAMPILAN CODE --}}
                            @elseif($type === 'code')
                                <div class="my-8 rounded-2xl overflow-hidden bg-[#0d1117] border border-white/10 font-mono text-sm shadow-xl">
                                    <div class="bg-[#161b22] px-5 py-3 border-b border-white/5 flex justify-between items-center">
                                        <div class="flex gap-2">
                                            <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                                            <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                                            <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                                        </div>
                                        <span class="text-xs text-white/40 uppercase font-bold tracking-wider">{{ $data['language'] ?? 'CODE' }}</span>
                                    </div>
                                    <div class="p-6 overflow-x-auto text-[#e6edf3] leading-relaxed">
                                        <pre><code>{{ is_array($data) ? ($data['code'] ?? '') : $data }}</code></pre>
                                    </div>
                                </div>

                            {{-- 5. HEADING --}}
                            @elseif($type === 'heading')
                                <h3 class="text-3xl font-bold text-[#EDB240] mt-12 mb-6 pb-3 border-b-2 border-white/10 inline-block font-heading">
                                    {{ is_array($data) ? ($data['text'] ?? $text) : $data }}
                                </h3>

                            {{-- 6. TEXT --}}
                            @else
                                <div class="prose prose-invert prose-xl max-w-none text-white/80 font-light leading-loose">
                                    @php $finalText = is_array($data) ? ($data['text'] ?? ($data['content'] ?? json_encode($data))) : $data; @endphp
                                    {!! nl2br(e($finalText)) !!}
                                </div>
                            @endif

                        @endforeach
                    </div>
                </div>
            </div>

        @empty
            <div class="text-center py-32 border-2 border-dashed border-white/10 rounded-3xl bg-white/5">
                <i class="far fa-folder-open text-5xl text-white/20 mb-4"></i>
                <p class="text-white/40 text-lg">Belum ada konten materi.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection