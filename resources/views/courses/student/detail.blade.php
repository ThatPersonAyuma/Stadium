@extends('layouts.dashboard')
@section('title', 'Course Detail')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-10 md:pt-8 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(120%_80%_at_18%_10%,rgba(0,64,168,0.25),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_82%_0%,rgba(0,46,135,0.35),transparent_60%)]"></div>

    <div class="relative space-y-6">
        <x-dashboard-header :title="$course->title ?? 'Course'" />

        <div class="bg-white/10 border border-white/15 rounded-2xl p-4 md:p-5 shadow-xl backdrop-blur-sm">
            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                <span>Progress</span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="h-4 w-full rounded-full bg-white/20 overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-amber-300 to-orange-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="space-y-8">
            @foreach ($modules as $module)
                <div class="space-y-4">
                    <div class="rounded-2xl bg-gradient-to-r from-lime-500 to-emerald-500 text-white p-4 md:p-6 shadow-xl">
                        <p class="text-lg font-extrabold m-0">{{ $module['title'] }}</p>
                        <h3 class="text-2xl md:text-3xl font-black leading-tight m-0">{{ $module['desc'] }}</h3>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($module['lessons'] as $lesson)
                            @php
                                $status = $lesson['status'];
                                $isLocked = $status === 'locked';
                                $colors = [
                                    'done'    => 'from-emerald-500 to-lime-500 text-white',
                                    'current' => 'from-blue-600 to-indigo-600 text-white',
                                    'locked'  => 'from-slate-400 to-slate-500 text-slate-200',
                                ];
                            @endphp
                            @if ($isLocked)
                                <div
                                    class="group relative aspect-square w-full rounded-2xl bg-gradient-to-br {{ $colors[$status] }} shadow-lg opacity-60 pointer-events-none">
                                    <div class="absolute inset-0 rounded-2xl border border-white/25"></div>
                                    <div class="flex h-full w-full items-center justify-center">
                                        <svg viewBox="0 0 64 64" class="w-14 h-14 drop-shadow-sm">
                                            <path d="M20 10 L32 4 L44 10 L52 6 L60 18 L60 38 C60 52 46 60 32 60 18 60 4 52 4 38 L4 18 L12 6 Z" fill="currentColor"/>
                                            <circle cx="26" cy="32" r="4" fill="#0f172a"/>
                                            <circle cx="38" cy="32" r="4" fill="#0f172a"/>
                                            <rect x="28" y="40" width="8" height="3" rx="1.5" fill="#0f172a"/>
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <a
                                    href="{{ route('lesson.show', ['courseId' => $course->id, 'lessonId' => $lesson['id']]) }}"
                                    class="group relative aspect-square w-full rounded-2xl bg-gradient-to-br {{ $colors[$status] }} shadow-lg transition hover:-translate-y-1 hover:shadow-xl">
                                    <div class="absolute inset-0 rounded-2xl border border-white/25"></div>
                                    <div class="flex h-full w-full items-center justify-center">
                                        <svg viewBox="0 0 64 64" class="w-14 h-14 drop-shadow-sm">
                                            <path d="M20 10 L32 4 L44 10 L52 6 L60 18 L60 38 C60 52 46 60 32 60 18 60 4 52 4 38 L4 18 L12 6 Z" fill="currentColor"/>
                                            <circle cx="26" cy="32" r="4" fill="#0f172a"/>
                                            <circle cx="38" cy="32" r="4" fill="#0f172a"/>
                                            <rect x="28" y="40" width="8" height="3" rx="1.5" fill="#0f172a"/>
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
