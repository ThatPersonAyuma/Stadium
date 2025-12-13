@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    <div class="relative z-10 mx-auto w-full space-y-8 mb-12">
        <x-dashboard-header title="Manajemen Quiz" subtitle="nyenyenye" />
    </div>
{{-- 2. STATS CARDS (DESIGN MATCHING COURSE CARD 100%) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        
        {{-- CARD 1: TOTAL QUIZ --}}
        {{-- Design: Blue Gradient (Mirip Total Course) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
             style="background: linear-gradient(135deg, #002F87 0%, #000B21 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-70 tracking-wider uppercase mb-1">Total Quiz</p>
                <h2 class="text-4xl font-extrabold font-heading">{{ $quizzes->count() }}</h2>
            </div>
            {{-- Icon: fa-file-alt (Quiz) --}}
            <i class="fas fa-file-alt absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- CARD 2: BUTUH REVIEW --}}
        {{-- Design: Orange/Gold Gradient (Mirip Butuh Approval) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
             style="background: linear-gradient(135deg, #EDB240 0%, #d97706 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-80 tracking-wider uppercase mb-1 text-black/60">Need Approval</p>
                <h2 class="text-4xl font-extrabold font-heading text-black">{{ $stats['pending'] ?? 0 }}</h2>
            </div>
            {{-- Icon: fa-clock (Pending) --}}
            <i class="fas fa-clock absolute -right-4 -bottom-4 text-8xl text-black opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

        {{-- CARD 3: KUIS AKTIF --}}
        {{-- Design: Green Gradient (Mirip Sudah Live) --}}
        <div class="relative overflow-hidden rounded-3xl p-6 text-white shadow-lg group hover:-translate-y-1 transition-all duration-300"
             style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
            <div class="relative z-10">
                <p class="text-sm font-bold opacity-70 tracking-wider uppercase mb-1">Approved</p>
                <h2 class="text-4xl font-extrabold font-heading">{{ $quizzes->where('status', App\Enums\CourseStatus::APPROVED)->count() ?? 0 }}</h2>
            </div>
            {{-- Icon: fa-check-double (Active) --}}
            <i class="fas fa-check-double absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
        </div>

    </div>
    {{-- 3. LIST CONTAINER --}}
    <div class="bg-[#001229]/80 backdrop-blur-xl rounded-[3rem] p-8 md:p-12 border border-white/5 shadow-2xl relative overflow-hidden">
        
        {{-- Decorative Top Line --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <div class="flex items-center gap-6 mb-10 px-2">
            <div class="w-16 h-16 rounded-[1.2rem] bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center shadow-lg shadow-blue-500/30 transform -rotate-3">
                <i class="fas fa-layer-group text-3xl text-white"></i>
            </div>
            <div>
                <h3 class="text-3xl font-black font-heading text-white tracking-tight">Bank Soal Masuk</h3>
            </div>
        </div>

        {{-- GRID LIST QUIZ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full">
            @forelse($quizzes->where('status', App\Enums\CourseStatus::PENDING)  as $quiz)
                <div class="group relative bg-[#001E5C] rounded-[2.5rem] p-1 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    
                    {{-- Border Gradient Effect --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent rounded-[2.5rem] pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-blue-500/0 to-blue-500/0 group-hover:from-blue-500/50 group-hover:via-purple-500/50 group-hover:to-pink-500/50 rounded-[2.5rem] transition-all duration-500 opacity-0 group-hover:opacity-100 blur-xl -z-10"></div>

                    <div class="bg-[#000B21] rounded-[2.4rem] p-8 h-full flex flex-col sm:flex-row gap-8 relative z-10 border border-white/5 group-hover:border-white/10">
                        
                        {{-- Icon Kiri (Floating) --}}
                        <div class="shrink-0">
                            <div class="w-20 h-20 rounded-3xl bg-[#001535] border border-white/5 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-puzzle-piece text-4xl text-transparent bg-clip-text bg-gradient-to-br from-[#EDB240] to-orange-500"></i>
                            </div>
                        </div>

                        {{-- Info Tengah --}}
                        <div class="flex-1 min-w-0 flex flex-col">
                            <div class="mb-6">
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    @if($quiz->status == App\Enums\CourseStatus::PENDING)
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-[#EA580C]/20 text-[#EA580C] border border-[#EA580C]/30 tracking-wider">Pending</span>
                                    @elseif($quiz->status == App\Enums\CourseStatus::APPROVED)
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-[#0F9D58]/20 text-[#0F9D58] border border-[#0F9D58]/30 tracking-wider">Active</span>
                                    @elseif($quiz->status == App\Enums\CourseStatus::REVISION)
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-[#EDB240]/20 text-[#0F9D58] border border-[#0F9D58]/30 tracking-wider">REVISION</span>
                                    @else
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-[#DC2626]/20 text-[#DC2626] border border-[#DC2626]/30 tracking-wider">Rejected</span>
                                    @endif
                                    <span class="text-xs text-slate-400 font-bold flex items-center gap-1.5 bg-white/5 px-3 py-1 rounded-lg border border-white/5">
                                        <i class="fas fa-user-circle text-blue-400"></i> {{ $quiz->creator->user->name ?? 'Unknown' }}
                                    </span>
                                </div>
                                <h4 class="text-2xl font-black text-white truncate leading-tight group-hover:text-blue-300 transition-colors">{{ $quiz->title }}</h4>
                                <p class="text-slate-400 text-sm mt-2 line-clamp-2 font-light leading-relaxed">{{ $quiz->description }}</p>
                            </div>

                            {{-- Footer Card (Action Buttons) --}}
                            <div class="mt-auto flex items-center gap-3 pt-6 border-t border-white/5">
                                <form action="{{ route('admin.manajemen.quiz.action') }}" method="POST" class="flex items-center gap-2">
                                    @csrf

                                    <input
                                        type="hidden"
                                        name="quiz_id"
                                        class="text-black"
                                        value="{{ $quiz->id }}"
                                        required
                                    >
                                    <a href="{{ route('admin.manajemen-quiz.show', $quiz->id) }}"  class="flex-1 h-12 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-bold uppercase tracking-wider transition-all border border-white/5 flex items-center justify-center gap-2 group/btn">
                                        <i class="fas fa-eye text-blue-400 group-hover/btn:text-white transition-colors"></i>
                                        <span>Review</span>
                                    </a>
                                    <button type="submit" 
                                            name="status"
                                            value="{{ App\Enums\CourseStatus::REVISION }}"
                                            class="px-4 py-3 rounded-xl bg-[#EDB240] hover:bg-[#0ed193] text-[#ffffff] text-sm font-bold shadow-lg hover:shadow-green-500/40 transition-all">
                                        <i class="fa-solid fa-circle-notch"></i> Revisi
                                    </button>
                                    <button type="submit" 
                                            name="status"
                                            value="{{ App\Enums\CourseStatus::REJECTED }}"
                                            class="w-12 h-12 rounded-xl bg-[#DC2626]/10 border border-[#DC2626]/30 text-[#DC2626] hover:bg-[#DC2626] hover:text-white flex items-center justify-center transition-all shadow-lg hover:shadow-red-500/30" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
            
                                    {{-- Tombol Terima (Visual) --}}
                                    <button type="submit" 
                                            name="status"
                                            value="{{ App\Enums\CourseStatus::APPROVED }}"
                                            class="px-4 py-3 rounded-xl bg-[#10b981] hover:bg-[#0ed193] text-[#ffffff] text-sm font-bold shadow-lg hover:shadow-green-500/40 transition-all">
                                        <i class="fas fa-check mr-1"></i> Terima
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 lg:col-span-2 py-32 flex flex-col items-center justify-center text-center opacity-50">
                    <div class="w-24 h-24 bg-[#001535] rounded-3xl flex items-center justify-center mb-6 border border-white/10 shadow-2xl">
                        <i class="fas fa-inbox text-5xl text-blue-500/50"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 font-heading">Data Kosong</h3>
                    <p class="text-slate-400 max-w-xs mx-auto leading-relaxed">Belum ada pengajuan kuis dari teacher saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@if (session()->has('success'))
    @push('scripts')
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        </script>
    @endpush
@endif
@endsection