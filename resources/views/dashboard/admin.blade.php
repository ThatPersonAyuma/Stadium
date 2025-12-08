@extends('layouts.dashboardadmin')
@section('title', 'Admin Dashboard')

@section('content')

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    <div class="relative z-10 mx-auto max-w-6xl space-y-8">
        <x-dashboard-header title="Admin Panel" subtitle="nyenyenye" />
    </div>
    
    <div class="relative z-10 px-6 pt-10 pb-20 md:px-10 lg:px-16 mx-auto max-w-8xl">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            
            {{-- Card 1: Pending Teachers (Orange Gradient) --}}
            <div class="relative overflow-hidden rounded-[2rem] p-6 bg-gradient-to-br from-[#d97706] to-[#b45309] shadow-lg shadow-orange-900/20 group hover:scale-[1.02] transition-transform duration-300">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-white/90 font-bold text-sm">Pending Teachers</p>
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-user-clock text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white mb-4">{{ $pendingTeachers->count() }}</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-lg bg-black/20 text-[10px] font-bold text-white/90 backdrop-blur-md">
                        <span>+{{ $stats['new_teachers_today'] ?? 0 }} hari ini</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            {{-- Card 2: Total Courses (Blue Gradient) --}}
            <div class="relative overflow-hidden rounded-[2rem] p-6 bg-gradient-to-br from-[#2563eb] to-[#1d4ed8] shadow-lg shadow-blue-900/20 group hover:scale-[1.02] transition-transform duration-300">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-white/90 font-bold text-sm">Total Courses</p>
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-book-open text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white mb-4">{{ $stats['total_courses'] }}</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-lg bg-black/20 text-[10px] font-bold text-white/90 backdrop-blur-md">
                        <span>+{{ $stats['new_courses_week'] ?? 0 }} minggu ini</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            {{-- Card 3: Active Users (Teal/Green Gradient) --}}
            <div class="relative overflow-hidden rounded-[2rem] p-6 bg-gradient-to-br from-[#0d9488] to-[#0f766e] shadow-lg shadow-teal-900/20 group hover:scale-[1.02] transition-transform duration-300">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-white/90 font-bold text-sm">Active Users</p>
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white mb-4">{{ number_format($stats['active_users']) }}</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-lg bg-black/20 text-[10px] font-bold text-white/90 backdrop-blur-md">
                        <span>Student Role</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            {{-- Card 4: Total Quizzes (Purple Gradient) --}}
            <div class="relative overflow-hidden rounded-[2rem] p-6 bg-gradient-to-br from-[#7c3aed] to-[#6d28d9] shadow-lg shadow-purple-900/20 group hover:scale-[1.02] transition-transform duration-300">
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-white/90 font-bold text-sm">Total Quizzes</p>
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-puzzle-piece text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white mb-4">{{ $stats['total_quizzes'] }}</h3>
                    <div class="inline-flex items-center px-3 py-1 rounded-lg bg-black/20 text-[10px] font-bold text-white/90 backdrop-blur-md">
                        <span>All Time</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        </div>

        {{-- 2. SECTION: MANAJEMEN REGISTRASI TEACHER --}}
        <div class="bg-[#111827]/40 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8 mb-12 relative overflow-hidden">
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/20 flex items-center justify-center border border-blue-500/20 text-blue-400">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Manajemen Registrasi Teacher</h2>
                        <p class="text-sm text-slate-400 font-medium mt-1">{{ $pendingTeachers->count() }} pengajuan menunggu review</p>
                    </div>
                </div>
                {{-- TOMBOL LIHAT SEMUA --}}
                <a href="{{ route('admin.manajemen-teachers') }}" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-bold text-white transition-all hover:scale-105">
                    Lihat Semua
                </a>
            </div>

            {{-- Teacher Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pendingTeachers as $teacher)
                    <div class="bg-[#0f172a] rounded-[2rem] p-6 border border-white/5 hover:border-blue-500/30 transition-all duration-300 group hover:-translate-y-1 relative overflow-hidden">
                        {{-- Top Part --}}
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-white leading-tight group-hover:text-blue-400 transition-colors">{{ $teacher->user->name ?? 'Unknown' }}</h3>
                                <p class="text-xs font-medium text-slate-400 mt-1">{{ $teacher->expertise ?? 'Expertise N/A' }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span> Pending
                            </span>
                        </div>

                        {{-- Info Details --}}
                        <div class="space-y-3 mb-8">
                            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
                                <i class="fas fa-briefcase text-slate-600 w-4"></i>
                                <span>Pengalaman: {{ $teacher->experience_years ?? 0 }} tahun</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
                                <i class="fas fa-calendar-alt text-slate-600 w-4"></i>
                                <span>Tanggal: {{ $teacher->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-3">
                            <button class="flex-1 h-11 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-400 hover:to-green-500 text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-green-900/20 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                            <button class="w-11 h-11 rounded-xl bg-[#1e293b] hover:bg-[#334155] border border-white/5 text-slate-400 hover:text-white transition-all flex items-center justify-center">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border border-dashed border-white/10 rounded-3xl bg-white/5">
                        <p class="text-slate-400 text-sm font-medium">Tidak ada registrasi guru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- 3. SECTION: MANAJEMEN COURSE & QUIZ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- Course Approval --}}
            <div class="bg-[#111827]/40 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8 flex flex-col">
                {{-- Header Course --}}
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600/20 flex items-center justify-center border border-indigo-500/20 text-indigo-400">
                            <i class="fas fa-book text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Course Approval</h3>
                            <p class="text-xs text-slate-400">{{ $pendingCourses->count() }} pending</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.manajemen-course.index') }}" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-[10px] font-bold text-white uppercase tracking-wider transition-all hover:scale-105">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($pendingCourses as $course)
                        <div class="bg-[#0f172a] rounded-2xl p-4 border border-white/5 flex items-center justify-between group hover:border-indigo-500/30 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-bold text-xs">
                                    {{ substr($course->title, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">{{ Str::limit($course->title, 20) }}</h4>
                                    <p class="text-[10px] text-slate-400">{{ $course->teacher->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="w-8 h-8 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500 hover:text-white transition-colors flex items-center justify-center">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-700/50 text-slate-400 hover:bg-slate-700 hover:text-white transition-colors flex items-center justify-center">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-500">Tidak ada course pending.</div>
                    @endforelse
                </div>
            </div>

            {{-- Quiz Review --}}
            <div class="bg-[#111827]/40 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-8 flex flex-col">
                {{-- Header Quiz --}}
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-600/20 flex items-center justify-center border border-purple-500/20 text-purple-400">
                            <i class="fas fa-question text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Quiz Review</h3>
                            <p class="text-xs text-slate-400">{{ $pendingQuizzes->count() }} pending</p>
                        </div>
                    </div>
                    {{-- TOMBOL LIHAT SEMUA --}}
                    <a href="{{ route('admin.manajemen-quiz.index') }}" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-[10px] font-bold text-white uppercase tracking-wider transition-all hover:scale-105">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($pendingQuizzes as $quiz)
                        <div class="bg-[#0f172a] rounded-2xl p-4 border border-white/5 flex items-center justify-between group hover:border-purple-500/30 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400 font-bold text-xs">
                                    {{ substr($quiz->title, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white group-hover:text-purple-400 transition-colors">{{ Str::limit($quiz->title, 20) }}</h4>
                                    <p class="text-[10px] text-slate-400">{{ $quiz->questions_count ?? 0 }} Soal</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="w-8 h-8 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500 hover:text-white transition-colors flex items-center justify-center">
                                    <i class="fas fa-check text-xs"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-slate-700/50 text-slate-400 hover:bg-slate-700 hover:text-white transition-colors flex items-center justify-center">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-500">Tidak ada quiz pending.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection