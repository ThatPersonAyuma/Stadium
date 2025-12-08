@extends('layouts.dashboardadmin')
@section('title', 'Manajemen Course')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    <div class="relative z-10 mx-auto w-full space-y-8 mb-12">
        <x-dashboard-header title="Manajemen Course" subtitle="nyenyenye" />
    </div>
 
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
             style="background: linear-gradient(135deg, #002F87 0%, #000B21 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-70 tracking-wider uppercase mb-1">Total Course</p>
                <h2 class="text-4xl font-extrabold font-heading">{{ $summary['total'] }}</h2>
            </div>
            <i class="fas fa-book absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
             style="background: linear-gradient(135deg, #EDB240 0%, #d97706 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-80 tracking-wider uppercase mb-1 text-black/60">Need Approval</p>
                <h2 class="text-4xl font-extrabold font-heading text-black">{{ $summary['pending'] }}</h2>
            </div>
            <i class="fas fa-clock absolute -right-4 -bottom-4 text-8xl text-black opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
             style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-70 tracking-wider uppercase mb-1">approved</p>
                <h2 class="text-4xl font-extrabold font-heading">{{ $summary['approved'] }}</h2>
            </div>
            <i class="fas fa-check-circle absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>
    </div>

    {{-- 3. DAFTAR COURSE --}}
    <div class="px-4">
        <h3 class="text-2xl font-bold text-[#f2eded] mb-6 font-heading flex items-center gap-2">
            <i class="fas fa-list-ul"></i> Daftar Pengajuan
        </h3>

        <div class="flex flex-col gap-5 pb-20">
            @forelse($courses as $course)
                {{-- CARD UTAMA --}}
                <div class="course-card relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:scale-[1.01]" 
                    style="background: linear-gradient(90deg, #001E5C 0%, #002F87 100%); min-height: auto; align-items: center;">
                    
                    {{-- Status Strip (Garis warna di kiri) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-2 
                        {{ $course->status == 'pending' ? 'bg-[#EDB240]' : ($course->status == 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                    </div>

                    {{-- 1. Thumbnail / Icon --}}
                    <div class="course-thumb flex items-center justify-center bg-white/10 ml-3">
                        <i class="fas fa-laptop-code text-2xl text-white/80"></i>
                    </div>

                    {{-- 2. Informasi Course --}}
                    <div class="course-info pl-2">
                        <div class="flex items-center gap-3 mb-1">
                            {{-- Badge Status --}}
                            @php
                                $statusColor = match($course->status) {
                                    'pending' => 'bg-[#EDB240] text-black',
                                    'approved' => 'bg-green-500 text-white',
                                    'rejected' => 'bg-red-600 text-white',
                                    default => 'bg-gray-500 text-white'
                                };
                            @endphp
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider {{ $statusColor }}">
                                {{ $course->status }}
                            </span>
                            <span class="text-xs text-white/60 font-medium">
                                {{ $course->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <h4 class="course-title text-white mb-1 leading-tight">{{ $course->title }}</h4>
                        <p class="course-topic text-white/60 text-sm truncate max-w-lg">
                            Oleh: <span class="text-[#EDB240] font-bold">{{ $course->teacher->user->name ?? 'Unknown' }}</span> • 
                            {{ $course->lessons->count() }} Lessons
                        </p>
                    </div>

                    {{-- 3. TOMBOL AKSI (Muncul Terus) --}}
                    <div class="flex items-center gap-3 ml-auto pr-2">
                        
                        {{-- Tombol Detail --}}
                        <a href="{{ route('admin.manajemen-course.show', $course) }}" 
                        class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-bold transition-all border border-white/10">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </a>

                        {{-- Tombol Tolak (Visual) --}}
                        <button type="button" 
                                class="px-4 py-2 rounded-xl bg-red-600/90 hover:bg-red-600 text-white text-sm font-bold shadow-lg hover:shadow-red-500/30 transition-all">
                            <i class="fas fa-times"></i>
                        </button>

                        {{-- Tombol Terima (Visual) --}}
                        <button type="button" 
                                class="px-4 py-2 rounded-xl bg-[#10b981] hover:bg-[#0ed193] text-[#ffffff] text-sm font-bold shadow-lg hover:shadow-green-500/40 transition-all">
                            <i class="fas fa-check mr-1"></i> Terima
                        </button>

                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl border-2 border-dashed border-gray-300">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 font-bold">Tidak ada pengajuan course saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection