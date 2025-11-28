@extends('layouts.dashboard')
@section('title', 'Course Detail')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-10 md:pt-8 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(120%_80%_at_18%_10%,rgba(0,64,168,0.25),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_82%_0%,rgba(0,46,135,0.35),transparent_60%)]"></div>

    <div class="relative space-y-6">
        <x-dashboard-header :title="$course->title ?? 'Course'" />

        <div class="bg-white/10 border border-white/15 rounded-2xl p-4 md:p-5 shadow-xl backdrop-blur-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 text-sm font-semibold mb-2">
                <span class="uppercase tracking-wide">Progress</span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="h-3 w-full rounded-full bg-white/20 overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-amber-300 to-orange-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="space-y-8">
            @foreach ($modules as $module)
                <div class="space-y-4">
                    <div class="rounded-3xl bg-gradient-to-r from-lime-500 to-emerald-500 text-white p-5 md:p-7 shadow-xl">
                        <p class="text-sm md:text-base font-extrabold m-0 uppercase tracking-wide">Lesson {{ $module->order_index ?? '' }}</p>
                        <h3 class="text-2xl md:text-3xl font-black leading-tight m-0">{{ $module->title }}</h3>
                        <p class="text-sm md:text-base opacity-90 m-0 mt-1">{{ $module->description }}</p>
                    </div>

                    <div class="space-y-2">
                        <p class="text-sm font-semibold opacity-80 m-0">Cards pada lesson ini:</p>
                        @php
                            $contents = $module->contents;
                            $headerThemes = [
                                'bg-gradient-to-r from-sky-500 to-indigo-600',
                                'bg-gradient-to-r from-amber-400 to-orange-600',
                                'bg-gradient-to-r from-emerald-500 to-teal-600',
                                'bg-gradient-to-r from-rose-500 to-pink-600',
                            ];
                            $cardThemes = [
                                'bg-gradient-to-br from-sky-500/80 to-indigo-700/80 border border-white/15 hover:border-white/30',
                                'bg-gradient-to-br from-amber-400/85 to-orange-600/85 border border-white/15 hover:border-white/30',
                                'bg-gradient-to-br from-emerald-500/80 to-teal-700/80 border border-white/15 hover:border-white/30',
                                'bg-gradient-to-br from-rose-500/80 to-pink-600/80 border border-white/15 hover:border-white/30',
                            ];
                        @endphp
                            
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                        @foreach ($contents as $content)
                                            @php

                                            // $cards   = $group['cards'];   
                                            $themeIndex = $loop->index % count($headerThemes);
                                            $headerClass = $headerThemes[$themeIndex];
                                            $cardClass = $cardThemes[$themeIndex];
            
                                            $status= $content->status;
                                            // Warna status
                                            $statusClasses = [
                                                'current' => 'ring-4 ring-green-400 shadow-lg shadow-green-500/30',
                                                'done'    => 'ring-4 ring-blue-400 shadow-lg shadow-blue-500/30',
                                                'locked'    => 'opacity-50 cursor-not-allowed'
                                            ];
                                            $statusClass = $statusClasses[$status];

                                            // Href
                                            $href = $status === 'locked'
                                                ? null
                                                : route('lesson.show', [
                                                    'course'  => $course->id,
                                                    'lesson'  => $module->id,
                                                    'content' => $content->id
                                                ]) . '?card=' . $content->id;
                                        @endphp


                                        @if ($href)
                                            {{-- clickable card --}}
                                            <a href="{{ $href }}"
                                            class="group relative w-full aspect-square rounded-3xl overflow-hidden transition {{ $cardClass }} {{ $statusClass }}">
                                                <div class="absolute inset-0 bg-gradient-to-br from-white/5 via-transparent to-white/10 opacity-70"></div>
                                                <div class="relative flex h-full w-full items-center justify-center text-white">
                                                    <svg viewBox="0 0 64 64" class="w-14 h-14 drop-shadow-sm">
                                                        <path d="M20 10 L32 4 L44 10 L52 6 L60 18 L60 38 C60 52 46 60 32 60 18 60 4 52 4 38 L4 18 L12 6 Z" fill="currentColor"/>
                                                        <circle cx="26" cy="32" r="4" fill="#0f172a"/>
                                                        <circle cx="38" cy="32" r="4" fill="#0f172a"/>
                                                        <rect x="28" y="40" width="8" height="3" rx="1.5" fill="#0f172a"/>
                                                    </svg>
                                                </div>
                                            </a>
                                        @else
                                            {{-- locked card (tidak bisa diklik) --}}
                                            <div class="group relative w-full aspect-square rounded-3xl overflow-hidden transition {{ $cardClass }} {{ $statusClass }}">
                                                <div class="absolute inset-0 bg-gradient-to-br from-white/5 via-transparent to-white/10 opacity-70"></div>
                                                <div class="relative flex h-full w-full items-center justify-center text-white">
                                                    <svg viewBox="0 0 64 64" class="w-14 h-14 drop-shadow-sm">
                                                        <path d="M20 10 L32 4 L44 10 L52 6 L60 18 L60 38 C60 52 46 60 32 60 18 60 4 52 4 38 L4 18 L12 6 Z" fill="currentColor"/>
                                                        <circle cx="26" cy="32" r="4" fill="#0f172a"/>
                                                        <circle cx="38" cy="32" r="4" fill="#0f172a"/>
                                                        <rect x="28" y="40" width="8" height="3" rx="1.5" fill="#0f172a"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif
                                        @endforeach
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
