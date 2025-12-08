@extends('layouts.dashboardadmin')
@section('title', 'Detail Course')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    <div class="w-full px-1 mb-6">
        <a href="{{ route('admin.manajemen-course.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 hover:bg-[#EDB240] hover:text-[#000B21] text-white/70 transition-all border border-white/5 font-bold text-xs uppercase tracking-widest group backdrop-blur-md">
            <i class="fas fa-chevron-left text-[10px] group-hover:-translate-x-1 transition-transform"></i>
            Back to List
        </a>
    </div>


    <div class="relative w-full rounded-3xl overflow-hidden p-[1px] mb-8 group">
        
        {{-- Animated Border Effect --}}
        <div class="absolute inset-0 bg-gradient-to-r from-[#002F87] via-[#EDB240] to-[#002F87] opacity-30 group-hover:opacity-50 transition-opacity duration-500"></div>

        <div class="relative bg-[#000B21]/80 backdrop-blur-xl rounded-[23px] p-6 md:p-10 flex flex-col md:flex-row gap-8 items-stretch">
            
            {{-- KIRI: Main Info --}}
            <div class="flex-1 flex flex-col justify-center">
                {{-- Top Meta --}}
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    @php
                        $statusVal = $course->status->value ?? $course->status;
                        $statusClass = match($statusVal) {
                            'pending' => 'text-[#EDB240] border-[#EDB240] shadow-[0_0_10px_rgba(237,178,64,0.2)]',
                            'approved' => 'text-green-400 border-green-400 shadow-[0_0_10px_rgba(74,222,128,0.2)]',
                            'rejected' => 'text-red-400 border-red-400 shadow-[0_0_10px_rgba(248,113,113,0.2)]',
                            default => 'text-gray-400 border-gray-400'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-md border bg-white/5 text-[10px] font-extrabold uppercase tracking-[0.2em] {{ $statusClass }}">
                        {{ $statusVal }}
                    </span>
                    {{-- <div class="h-4 w-[1px] bg-white/10"></div>
                    <span class="text-white/40 text-[10px] uppercase font-bold tracking-widest flex items-center gap-2">
                        <i class="far fa-clock"></i> {{ $course->created_at->format('d M Y') }}
                    </span> --}}
                </div>

                {{-- Title --}}
                <h1 class="text-3xl md:text-5xl font-extrabold text-white font-heading leading-tight mb-4 drop-shadow-2xl">
                    {{ $course->title }}
                </h1>

                {{-- Description (Compact) --}}
                <div class="border-l-2 border-[#EDB240]/50 pl-4 py-1 mb-6">
                    <p class="text-white/70 text-sm md:text-base font-light leading-relaxed line-clamp-3 md:line-clamp-none">
                        {{ $course->description }}
                    </p>
                </div>

                {{-- Instructor Badge --}}
                <div class="flex items-center gap-3 self-start bg-white/5 pr-5 pl-2 py-1.5 rounded-full border border-white/5">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#EDB240] to-yellow-600 flex items-center justify-center text-[#000B21] font-bold text-sm shadow-lg">
                        {{ substr($course->teacher->user->name ?? 'T', 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-white/40 uppercase font-bold tracking-wider">Instructor</span>
                        <span class="text-white font-bold text-xs">{{ $course->teacher->user->name ?? 'Unknown' }}</span>
                    </div>
                </div>

                {{-- Alert Rejected --}}
                @if($statusVal == 'rejected' && $course->rejection_note)
                    <div class="mt-4 bg-red-500/10 border-l-4 border-red-500 p-3 rounded-r-lg">
                        <p class="text-red-400 text-xs italic">"{{ $course->rejection_note }}"</p>
                    </div>
                @endif
            </div>

            {{-- KANAN: HUD Stats (Tampilan Data Futuristik) --}}
            <div class="md:w-[280px] flex flex-col gap-3">
                
                {{-- Stat Item 1 --}}
                <div class="flex-1 bg-gradient-to-r from-[#EDB240]/10 to-transparent border border-[#EDB240]/20 rounded-2xl p-4 flex items-center justify-between group">
                    <div>
                        <span class="text-[9px] text-white/40 uppercase font-bold tracking-widest block mb-1">Lessons</span>
                        <span class="text-2xl font-heading text-white font-bold">{{ $stats['lessons'] }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#EDB240] flex items-center justify-center text-[#000B21] shadow-[0_0_15px_rgba(237,178,64,0.4)]">
                        <i class="fas fa-book"></i>
                    </div>
                </div>

                {{-- Stat Item 2 --}}
                <div class="flex-1 bg-gradient-to-r from-[#EDB240]/10 to-transparent border border-[#EDB240]/20 rounded-2xl p-4 flex items-center justify-between group">
                    <div>
                        <span class="text-[9px] text-white/40 uppercase font-bold tracking-widest block mb-1">Contents</span>
                        <span class="text-2xl font-heading text-white font-bold">{{ $stats['contents'] }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#EDB240] flex items-center justify-center text-[#000B21] shadow-[0_0_15px_rgba(237,178,64,0.4)]">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>

                {{-- Stat Item 3 (Highlight) --}}
                <div class="flex-1 bg-gradient-to-r from-[#EDB240]/10 to-transparent border border-[#EDB240]/20 rounded-2xl p-4 flex items-center justify-between group">
                    <div>
                        <span class="text-[9px] text-[#EDB240]/70 uppercase font-bold tracking-widest block mb-1">Total Blocks</span>
                        <span class="text-2xl font-heading text-[#ffffff] font-bold">{{ $stats['blocks'] }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#EDB240] flex items-center justify-center text-[#000B21] shadow-[0_0_15px_rgba(237,178,64,0.4)]">
                        <i class="fas fa-cubes"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 4. THE DATA STREAM (Lesson List) --}}
    <div class="w-full">
        <div class="flex items-center gap-4 mb-6 px-2">
            <h3 class="text-[40px] font-heading text-white flex items-center gap-2">
                <i class="fas fa-database text-[#EDB240]"></i> Course Modules
            </h3>
            <div class="h-[1px] flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
        </div>

        <div class="flex flex-col gap-4">
            @forelse($course->lessons as $lesson)
                
                {{-- Lesson Node --}}
                <div class="group relative pl-6 transition-all duration-300">
                    
                    {{-- Connecting Line Visual --}}
                    <div class="absolute left-0 top-0 bottom-0 w-[2px] bg-white/5 group-hover:bg-[#EDB240]/50 transition-colors"></div>
                    <div class="absolute left-[-4px] top-6 w-2.5 h-2.5 rounded-full bg-[#000B21] border-2 border-[#EDB240] shadow-[0_0_10px_rgba(237,178,64,0.5)]"></div>

                    {{-- Card Container --}}
                    <div class="bg-[#001E5C] border border-white/5 rounded-2xl overflow-hidden hover:border-[#EDB240]/30 transition-all shadow-lg hover:shadow-2xl">
                        
                        {{-- Header Lesson --}}
                        <div class="p-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between bg-white/[0.02]">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#000B21] text-[#EDB240] font-heading font-bold text-lg border border-white/10 shadow-inner">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <h4 class="text-white font-bold text-lg leading-tight group-hover:text-[#EDB240] transition-colors">{{ $lesson->title }}</h4>
                                    <p class="text-white/40 text-xs mt-1 line-clamp-1 font-mono">{{ $lesson->description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-white/30 bg-white/5 px-2 py-1 rounded">
                                    {{ $lesson->contents->count() }} Files
                                </span>
                            </div>
                        </div>

                        {{-- Content Grid (Compact Chips) --}}
                        @if($lesson->contents->count() > 0)
                            <div class="p-4 bg-[#000B21]/30 border-t border-white/5">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($lesson->contents as $content)
                                        
                                        {{-- ITEM KONTEN (LINK) --}}
                                        <a href="{{ route('admin.manajemen-course.preview', $content) }}" 
                                        class="group relative inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 border border-white/5 hover:bg-[#EDB240] hover:border-[#EDB240] hover:text-[#000B21] text-white/70 transition-all duration-200 cursor-pointer decoration-0">
                                            
                                            {{-- Icon Kecil --}}
                                            <i class="fas fa-play-circle text-[10px] opacity-50 group-hover:opacity-100 transition-opacity"></i>
                                            
                                            {{-- Judul --}}
                                            <span class="text-xs font-bold truncate max-w-[150px]">{{ $content->title }}</span>
                                            
                                            {{-- Badge Tipe (Jika ada) --}}
                                            @if(isset($content->type))
                                                <span class="ml-1 pl-2 border-l border-white/10 group-hover:border-black/10 text-[9px] uppercase font-extrabold opacity-60">
                                                    {{ $content->type }}
                                                </span>
                                            @endif

                                        </a>

                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="h-1 bg-white/5 w-full"></div>
                        @endif
                    </div>
                </div>

            @empty
                <div class="p-8 text-center border-2 border-dashed border-white/5 rounded-2xl">
                    <p class="text-white/30 font-mono text-sm">NO_DATA_AVAILABLE_IN_STREAM</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection