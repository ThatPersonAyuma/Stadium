@extends('layouts.dashboardadmin')
@section('title', 'Manajemen Teacher')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
{{-- HEADER SECTION --}}
    <div class="relative z-10 mx-auto w-full space-y-8 mb-12">
        <x-dashboard-header title="Manajemen Teacher" subtitle="Kelola data pengajar dan akses sistem" />
    </div>

{{-- 1. HEADER SECTION (Rounded Bubble Title) --}}
    <div class="relative z-10 w-full mb-10">

            
{{-- Grid 3 Kolom: Total (Gelap), Pending (Orange), Active (Hijau) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        
        {{-- CARD 1: TOTAL TEACHERS --}}
        {{-- Design: Blue Gradient (Mirip Total Course) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
            style="background: linear-gradient(135deg, #002F87 0%, #000B21 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-70 tracking-wider uppercase mb-1">Total Teachers</p>
                <h2 class="text-4xl font-extrabold font-heading">{{ $teachers->count() }}</h2>
            </div>
            {{-- Icon: fa-users --}}
            <i class="fas fa-users absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- CARD 2: NEED APPROVAL --}}
        {{-- Design: Orange/Gold Gradient (Mirip Butuh Approval) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
            style="background: linear-gradient(135deg, #EDB240 0%, #d97706 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-80 tracking-wider uppercase mb-1 text-black/60">Need Approval</p>
                <h2 class="text-4xl font-extrabold font-heading text-black">{{ $summary['pending'] ?? 0 }}</h2>
            </div>
            {{-- Icon: fa-clock --}}
            <i class="fas fa-clock absolute -right-4 -bottom-4 text-8xl text-black opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- CARD 3: APPROVED --}}
        {{-- Design: Green Gradient (Mirip Sudah Live) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
            style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-70 tracking-wider uppercase mb-1">Approved</p>
                <h2 class="text-4xl font-extrabold font-heading">{{ $summary['approved'] ?? 0 }}</h2>
            </div>
            {{-- Icon: fa-check-circle --}}
            <i class="fas fa-check-circle absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

    </div>

    {{-- 3. TEACHER LIST CONTAINER (Dark Blue Section) --}}
    {{-- Container Besar Pembungkus List --}}
    <div class="bg-[#001E5C] rounded-[2.5rem] p-6 md:p-10 shadow-2xl">
        
        <div class="flex justify-between items-center mb-8 px-2">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/20 flex items-center justify-center border border-blue-400/30">
                    <i class="fas fa-user-plus text-blue-300 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold font-heading text-white">Daftar Pengajuan</h3>
                    <p class="text-blue-200 text-sm">{{ count($teachers) }} pengajuan menunggu review</p>
                </div>
            </div>
        </div>

        {{-- GRID TEACHER CARDS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">

            @forelse($teachers as $teacher)
                {{-- CARD INDIVIDUAL (Darker Navy Background) --}}
                <div class="bg-[#000B21] rounded-[2rem] p-6 border border-white/5 hover:border-blue-500/50 transition-all duration-300 group shadow-lg">
                    
                    <div class="flex flex-col sm:flex-row gap-6">
                        
                        {{-- Avatar Box (Pink/Purple Pop like reference) --}}
                        <div class="shrink-0 flex flex-col gap-3">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center shadow-lg transform group-hover:rotate-3 transition-transform">
                                <span class="font-heading font-bold text-3xl text-white">
                                    {{ substr($teacher->name, 0, 1) }}
                                </span>
                            </div>
                            {{-- Badge Pending --}}
                            <span class="px-3 py-1 rounded-full bg-[#1E293B] border border-yellow-500/50 text-yellow-400 text-[10px] font-bold text-center uppercase tracking-wider">
                                ● Pending
                            </span>
                        </div>

                        {{-- Info & Actions --}}
                        <div class="flex-1 min-w-0 flex flex-col">
                            
                            {{-- Nama & Jabatan --}}
                            <div class="mb-4">
                                <h4 class="text-xl font-bold text-white mb-1 truncate">{{ $teacher->name }}</h4>
                                <p class="text-slate-400 text-xs font-medium">{{ $teacher->email }}</p>
                            </div>

                            {{-- Mini Stats / Info --}}
                            <div class="grid grid-cols-1 gap-2 mb-6">
                                <div class="flex items-center gap-2 text-slate-500 text-xs">
                                    <i class="fas fa-user-tag w-4"></i>
                                    <span>Username: {{ $teacher->username }}</span>
                                </div>
                            </div>

                            {{-- BUTTONS (Green Wide & Red Square) --}}
                            <div class="mt-auto flex gap-3">
                                {{-- Tombol Terima (Hijau Lebar - Solid) --}}
                                <button class="flex-1 bg-[#10B981] hover:bg-[#059669] text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-green-900/20 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i>
                                    <span>Setujui</span>
                                </button>

                                {{-- Tombol Tolak (Merah Kotak Kecil - Solid) --}}
                                <button class="w-12 h-auto rounded-xl bg-[#DC2626] hover:bg-[#B91C1C] text-white flex items-center justify-center shadow-lg shadow-red-900/20 transition-all" title="Tolak">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                {{-- STATE KOSONG --}}
                <div class="col-span-1 lg:col-span-2 py-20 text-center">
                    <div class="w-20 h-20 bg-[#001535] rounded-full flex items-center justify-center mx-auto mb-4 border border-white/10">
                        <i class="fas fa-folder-open text-3xl text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Tidak ada data</h3>
                    <p class="text-slate-400 text-sm">Semua pengajuan teacher sudah diproses.</p>
                </div>
            @endforelse

        </div>
    </div>
</div>
@endsection